<?php

namespace App\Console\Commands;

use App\Announcement;
use App\Helpers\AnnouncementRecipient;
use App\Http\Controllers\AnnouncementController;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessScheduledAnnouncements extends Command
{
    protected $signature = 'announcements:process-scheduled';

    protected $description = 'Send due scheduled announcement slots and reminders';

    public function handle()
    {
        @set_time_limit(0);
        ignore_user_abort(true);

        $controller = app(AnnouncementController::class);
        $now = Carbon::now();

        Announcement::where('is_active', true)
            ->where(function ($q) {
                $q->whereNotNull('schedules_json')
                    ->orWhereNotNull('reminders_json')
                    ->orWhere('status', 'queued');
            })
            ->orderBy('id')
            ->chunk(50, function ($items) use ($controller, $now) {
                foreach ($items as $announcement) {
                    if ($announcement->status === 'queued' && empty(AnnouncementRecipient::parseSlots($announcement->schedules_json))) {
                        $announcement->schedules_json = json_encode([[
                            'at' => $now->toDateTimeString(),
                            'status' => 'pending',
                            'sent_at' => null,
                        ]]);
                        $announcement->save();
                    }
                    $this->processSlots($announcement, 'schedules_json', $controller, $now);
                    $this->processSlots($announcement, 'reminders_json', $controller, $now);
                }
            });

        return 0;
    }

    private function processSlots(Announcement $announcement, string $column, AnnouncementController $controller, Carbon $now): void
    {
        $slots = AnnouncementRecipient::parseSlots($announcement->{$column});
        if (empty($slots)) {
            return;
        }

        $changed = false;
        foreach ($slots as $i => $slot) {
            if (($slot['status'] ?? '') === 'sent') {
                continue;
            }
            try {
                $due = Carbon::parse($slot['at']);
            } catch (\Exception $e) {
                continue;
            }
            if ($due->gt($now)) {
                continue;
            }

            $controller->deliverAnnouncement($announcement);
            $slots[$i]['status'] = 'sent';
            $slots[$i]['sent_at'] = $now->toDateTimeString();
            $changed = true;
        }

        if (!$changed) {
            return;
        }

        $announcement->{$column} = json_encode($slots);
        $pending = 0;
        foreach (['schedules_json', 'reminders_json'] as $col) {
            foreach (AnnouncementRecipient::parseSlots($announcement->{$col}) as $s) {
                if (($s['status'] ?? '') !== 'sent') {
                    $pending++;
                }
            }
        }
        if ($pending === 0) {
            $announcement->is_sent = true;
            $announcement->status = 'sent';
        } else {
            $announcement->status = 'scheduled';
        }
        $announcement->save();
    }
}
