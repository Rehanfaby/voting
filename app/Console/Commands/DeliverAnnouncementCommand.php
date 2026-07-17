<?php

namespace App\Console\Commands;

use App\Announcement;
use App\Helpers\AnnouncementRecipient;
use App\Http\Controllers\AnnouncementController;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeliverAnnouncementCommand extends Command
{
    protected $signature = 'announcements:deliver {id : Announcement id to deliver now}';

    protected $description = 'Deliver one queued announcement over WhatsApp (throttled)';

    public function handle()
    {
        @set_time_limit(0);
        ignore_user_abort(true);

        $id = (int) $this->argument('id');
        $announcement = Announcement::where('is_active', true)->find($id);
        if (!$announcement) {
            $this->error('Announcement not found');
            return 1;
        }

        $controller = app(AnnouncementController::class);
        $controller->deliverAnnouncement($announcement);

        $now = Carbon::now()->toDateTimeString();
        foreach (['schedules_json', 'reminders_json'] as $column) {
            $slots = AnnouncementRecipient::parseSlots($announcement->{$column});
            if (empty($slots)) {
                continue;
            }
            foreach ($slots as $i => $slot) {
                if (($slot['status'] ?? '') !== 'sent') {
                    $slots[$i]['status'] = 'sent';
                    $slots[$i]['sent_at'] = $now;
                }
            }
            $announcement->{$column} = json_encode($slots);
        }

        $announcement->is_sent = true;
        $announcement->status = 'sent';
        $announcement->save();

        $this->info('Delivered announcement #' . $id);
        return 0;
    }
}
