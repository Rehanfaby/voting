<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\AnnouncementAttachment;
use App\AnnouncementTemplate;
use App\Customer;
use App\GeneralSetting;
use App\Helpers\AnnouncementRecipient;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Mail;

class AnnouncementController extends Controller
{
    public function index()
    {
        $data = Announcement::where('is_active', true)->orderBy('id', 'desc')->get();
        return view('announcement.index', compact('data'));
    }
    public function create(Request $request)
    {
        $categories = AnnouncementRecipient::categories();
        AnnouncementTemplate::seedDefaults();
        $templates = AnnouncementTemplate::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $clone = null;
        if ($request->filled('clone')) {
            $clone = Announcement::with('attachmentLib')->find($request->clone);
        }

        return view('announcement.create', compact('categories', 'clone', 'templates'));
    }

    public function templates()
    {
        AnnouncementTemplate::seedDefaults();
        $templates = AnnouncementTemplate::orderBy('sort_order')->orderBy('id')->get();

        return view('announcement.templates', compact('templates'));
    }

    public function templateEdit($id)
    {
        $template = AnnouncementTemplate::findOrFail($id);

        return view('announcement.template_edit', compact('template'));
    }

    public function templateUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'subject' => 'nullable|string|max:255',
        ]);

        $template = AnnouncementTemplate::findOrFail($id);
        $template->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'header' => $request->header,
            'body' => $request->body,
            'footer' => $request->footer,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('announcement.templates')->with('message', 'Template updated successfully');
    }

    public function templateContent($id)
    {
        $template = AnnouncementTemplate::findOrFail($id);

        return response()->json([
            'subject' => $template->subject,
            'header' => $template->header,
            'body' => $template->body,
            'footer' => $template->footer,
        ]);
    }

    public function cloneAnnouncement($id)
    {
        $source = Announcement::with('attachmentLib')->findOrFail($id);

        return redirect()->route('announcement.create', ['clone' => $source->id]);
    }

    public function recipients(Request $request)
    {
        $category = (string) $request->query('category', 'contestants');
        $query = $request->query('q');

        return response()->json([
            'items' => AnnouncementRecipient::listForCategory($category, $query),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except(['attachments', 'to_csv', 'recipients', 'schedule_times', 'reminder_times', 'send_now', 'schedule_later']);

        $audience = $request->input('audience_category', $request->input('people_type', 'users'));
        $data['audience_category'] = $audience;
        $data['people_type'] = in_array($audience, ['csv'], true) ? 'csv' : 'user';

        if (empty(trim((string) ($data['name'] ?? '')))) {
            $data['name'] = optional(Auth::user())->name ?: 'Admin';
        }
        if (!array_key_exists('cc', $data) || $data['cc'] === null) {
            $data['cc'] = '';
        }

        $recipientPayload = $request->input('recipients');
        if (is_string($recipientPayload)) {
            $recipientPayload = json_decode($recipientPayload, true);
        }
        if ($audience === 'csv') {
            $toCsv = $request->file('to_csv');
            if ($toCsv) {
                $csvName = date('Ymdhis') . $toCsv->getClientOriginalName();
                $toCsv->move('public/announcement/csv', $csvName);
                $data['to'] = $csvName;
            } else {
                return $this->announcementStoreError($request, 'Please upload a CSV file.', 422);
            }
            $data['recipients_json'] = null;
        } elseif (is_array($recipientPayload) && count($recipientPayload)) {
            $data['recipients_json'] = AnnouncementRecipient::storePayload($recipientPayload);
            $data['to'] = implode(',', array_map(function ($r) {
                return AnnouncementRecipient::recipientKey($r);
            }, json_decode($data['recipients_json'], true)));
        } elseif (in_array($audience, ['everyone', 'contestants', 'voters', 'users', 'judges', 'ambassadors'], true)) {
            $all = AnnouncementRecipient::listForCategory($audience);
            if (!count($all)) {
                return $this->announcementStoreError($request, 'No recipients found in this category.', 422);
            }
            $data['recipients_json'] = AnnouncementRecipient::storePayload($all);
            $data['to'] = implode(',', array_map([AnnouncementRecipient::class, 'recipientKey'], $all));
        } else {
            return $this->announcementStoreError($request, 'Please select at least one recipient.', 422);
        }

        $scheduleLater = $request->boolean('schedule_later');
        $scheduleTimes = array_filter((array) $request->input('schedule_times', []));
        $reminderTimes = array_filter((array) $request->input('reminder_times', []));
        $data['reminders_json'] = count($reminderTimes) ? json_encode(AnnouncementRecipient::normalizeSlots($reminderTimes)) : null;

        $sendNow = $request->boolean('send_now');
        if ($scheduleLater && count($scheduleTimes)) {
            $data['schedules_json'] = json_encode(AnnouncementRecipient::normalizeSlots($scheduleTimes));
            $data['status'] = 'scheduled';
            $data['is_sent'] = false;
        } elseif ($sendNow) {
            // Queue for the minute cron — never send synchronously (6s throttle would time out the browser).
            $data['schedules_json'] = json_encode(AnnouncementRecipient::normalizeSlots([
                now()->toDateTimeString(),
            ]));
            $data['status'] = 'queued';
            $data['is_sent'] = false;
        } else {
            $data['schedules_json'] = null;
            $data['status'] = 'draft';
            $data['is_sent'] = false;
        }

        $image = $request->attachment;
        if (isset($image)) {
            $imageName = date('Ymdhis') . $image->getClientOriginalName();
            $image->move('public/announcement/attachment', $imageName);
            $data['attachment'] = $imageName;
        }

        $data['created_by'] = Auth::user()->id;
        unset($data['customer_type'], $data['to_customer_group'], $data['is_template'], $data['to_customer'], $data['cc_customer']);

        $attachments = $request->file('attachments', []);
        try {
            $announcement = Announcement::create($data);
        } catch (\Throwable $e) {
            \Log::error('Announcement save failed', ['error' => $e->getMessage()]);
            return $this->announcementStoreError($request, 'Could not save announcement: ' . $e->getMessage(), 500);
        }

        if ($attachments) {
            foreach ($attachments as $key => $attachment) {
                $attachmentName = date('Ymdhis') . $attachments[$key]->getClientOriginalName();
                $attachments[$key]->move('public/announcement/attachment', $attachmentName);
                AnnouncementAttachment::create(['announcement_id' => $announcement->id, 'attachment' => $attachmentName]);
            }
        }

        if ($sendNow && !$scheduleLater) {
            $message = 'Announcement saved and queued for WhatsApp delivery (about 6 seconds between each recipient).';
        } elseif ($scheduleLater) {
            $message = 'Announcement scheduled successfully';
        } else {
            $message = 'Announcement saved successfully';
        }

        if ($this->isAnnouncementAjax($request)) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'id' => $announcement->id,
                'redirect' => route('announcement.index'),
            ]);
        }

        return redirect()->route('announcement.index')->with('message', $message);
    }

    private function isAnnouncementAjax(Request $request): bool
    {
        return $request->ajax()
            || $request->wantsJson()
            || $request->header('X-Requested-With') === 'XMLHttpRequest';
    }

    private function announcementStoreError(Request $request, string $message, int $status = 422)
    {
        if ($this->isAnnouncementAjax($request)) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }

        return back()->with('not_permitted', $message);
    }

    public function edit($id)
    {
        $data = Announcement::findorfail($id);
        $user = User::where('is_active', true)->get();
        return view('announcement.edit', compact('user', 'data'));
    }

    public function announcementAttachmentDelete($id)
    {
        $attachment = AnnouncementAttachment::where('id', $id)->first();
        @unlink('public/announcement/attachment/'.$attachment->attachment);
        $attachment->delete();
        return back();
    }

    public function letterAttachmentDeleteFirst($id)
    {
        $letter = Announcement::where('id', $id)->first();
        @unlink('public/announcement/attachment/'.$letter->attachment);
        $letter->update(['attachment' => null]);
        return back()->with('not_permitted', 'Announcement attachment deleted successfully');
    }

    public function update(Request $request, Announcement $announcement, $id)
    {
        $data = $request->all();
        $announcement = $announcement->find($id);
        $attachments = $request->attachments;
        if ($attachments) {
            foreach ($attachments as $key => $attachment) {
                $attachmentName = date("Ymdhis").$attachments[$key]->getClientOriginalName();
                $attachments[$key]->move('public/announcement/attachment', $attachmentName);
                AnnouncementAttachment::create(['announcement_id' => $id, 'attachment' => $attachmentName]);
            }
        }

        if($announcement->people_type == "csv") {
            $to_csv = $request->to_csv;
            if (isset($to_csv)) {
                $imageName = date("Ymdhis").$to_csv->getClientOriginalName();
                $to_csv->move('public/announcement/csv', $imageName);
                $data['to'] = $imageName;
            }
        } else {
            $data['to'] = implode(",", $data['to']);
            $data['cc'] = isset($data['cc']) ? implode(",", $data['cc']) : null;
        }
        unset($data['attachments']);
        unset($data['to_csv']);
        $announcement->update($data);
        return redirect()->route('announcement.index')->with('message', 'Letter updated successfully');
    }

    public function imageUpload(Request $request)
    {
        $image = $request->file('image')->store('public/images/announcement');
        $url = Storage::url($image);

        return response()->json(['location' => $url]);
    }

    public function show(Announcement $announcement, $id)
    {
        $data = $announcement->with( 'createdBy', 'attachmentLib')->where('id', $id)->first();
        return view('announcement.show', compact('data'));
    }

    public function destroy($id)
    {
        $data = Announcement::find($id);
        $data->is_active = false;
        $data->save();
        return back()->with('not_permitted','Data deleted successfully');
    }

    public function send(Announcement $announcement, $id)
    {
        $announcement = $announcement->findOrFail($id);
        $slots = AnnouncementRecipient::parseSlots($announcement->schedules_json);
        $slots[] = [
            'at' => now()->toDateTimeString(),
            'status' => 'pending',
            'sent_at' => null,
        ];
        $announcement->update([
            'schedules_json' => json_encode($slots),
            'status' => 'queued',
            'is_sent' => false,
        ]);

        return redirect()->back()->with('message', 'Announcement queued for WhatsApp delivery. Messages go out about every 6 seconds.');
    }

    /**
     * Allocate a persistent, incrementing reference such as MGT/S02/ADMIN/L-001.
     * The prefix, season and next counter live on general_settings so they persist.
     */
    private function assignReference(Announcement $announcement): void
    {
        if (!empty($announcement->reference)) {
            return;
        }

        $reference = DB::transaction(function () use ($announcement) {
            $setting = GeneralSetting::lockForUpdate()->first();
            $prefix = $setting->announcement_ref_prefix ?? 'MGT';
            $season = $setting->announcement_ref_season ?? 'S02';
            $next = (int) ($setting->announcement_ref_next ?? 1);
            if ($next < 1) {
                $next = 1;
            }

            $sender = strtoupper(optional($announcement->createdBy)->name ?: optional(Auth::user())->name ?: 'ADMIN');
            $sender = preg_replace('/[^A-Z0-9]+/', '', $sender) ?: 'ADMIN';

            $ref = sprintf('%s/%s/%s/L-%03d', $prefix, $season, $sender, $next);

            $setting->announcement_ref_next = $next + 1;
            $setting->save();

            return $ref;
        });

        $announcement->reference = $reference;
        $announcement->save();
    }

    public function deliverAnnouncement(Announcement $announcement): void
    {
        @set_time_limit(0);
        ignore_user_abort(true);

        $this->assignReference($announcement);
        $announcement->load('attachmentLib');
        $recipients = AnnouncementRecipient::resolveForAnnouncement($announcement);

        foreach ($recipients as $row) {
            $recipient = AnnouncementRecipient::toRecipientObject($row);
            if (empty($recipient->phone)) {
                continue;
            }
            // Spacing between recipients is enforced centrally in Controller::withWhatsAppThrottle (6s).
            $this->sendAnnouncementMsg($announcement, $recipient);
        }
    }


    public function download(Announcement $announcement, $id)
    {
        $announcement = $announcement->find($id);
        $customer = User::class;

        $data = [
            'data' => $announcement,
            'user' => $customer,
            'people_type' => $announcement->people_type
        ];

        $pdf = PDF::loadView('pdf.announcement_download_pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->download('announcement.pdf');

    }

    public function print(Announcement $announcement, $id)
    {
        $announcement = $announcement->find($id);
        $customer = User::class;

        $data = [
            'data' => $announcement,
            'user' => $customer,
            'people_type' => $announcement->people_type
        ];

        $pdf = PDF::loadView('pdf.announcement_download_pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->stream('announcement.pdf');

    }

    public function sendWhatsapp(Announcement $announcement, $id)
    {
        $announcement = $announcement->find($id);
        $customer = User::class;


        ProcessQueue::dispatch($announcement, $id, $customer);

        $announcement->find($id)->update(['is_sent'=>true, 'sent_by'=>Auth::user()->id, 'otp' => null]);
        return redirect()->back()->with('message', 'Announcement will send soon');
    }

    public function sendEmail(Announcement $announcement, $id)
    {
        $announcement = $announcement->find($id);
        $customer = User::class;

        foreach (explode(",", $announcement->to) as $to) {
            $lims_customer_data = $customer::find($to);
            $message = $this->sendMail($announcement, $lims_customer_data, $to);

        }

        $announcement->find($id)->update(['is_sent'=>true, 'sent_by'=>Auth::user()->id, 'otp' => null]);
        return redirect()->back()->with('message', 'Announcement Sent successfully');
    }

    public function sendMail($announcement, $lims_customer_data, $to) {
        $cc_emails = [];
        $attachments = [];
        $customer = User::class;

        if ($announcement->cc != null) {
            foreach (explode(",", $announcement->cc) as $cc) {
                $lims_customer_data_cc = $customer::find($cc);
                if($lims_customer_data_cc->email) {
                    $cc_emails []= $lims_customer_data_cc->email;
                }
            }
        }
        if($announcement->attachment) {
            $attachment_path = public_path('announcement/attachment/');
            $attachments[] = $attachment_path.$announcement->attachment;
        }
        if(isset($announcement->attachmentlib[0])) {
            foreach ($announcement->attachmentlib as $key => $attachment) {
                if ($key == 0) {
                    continue;
                }
                $attachments[] = $attachment_path.$attachment->attachment;
            }
        }
        if ($lims_customer_data == null) {
            return true;
        }
        $data = [
            'to' => $to,
            'data' => $announcement,
            'mail' => $lims_customer_data->email,
            'subject' => $announcement->subject,
            'cc_emails' => $cc_emails,
            'attachments' => $attachments
        ];

        $message = 'Announcement notification sent successfully';
        try{
            Mail::send( 'mail.announcement_details', $data, function( $message ) use ($data)
            {
                $message->to($data['mail'])->subject($data['subject'])->cc($data['cc_emails']);

                foreach ($data['attachments'] as $attachment) {
                    $message->attach($attachment);
                }
            });
        }
        catch(\Exception $e){
            $message = 'Announcement is not sent. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        }
        return $message;
    }

    /** Build a nicely formatted bilingual WhatsApp announcement, matching other system messages. */
    private function buildAnnouncementMessage($announcement, $lims_customer_data): string
    {
        $title = \App\Helpers\WhatsAppFormatter::announcementTitle();
        $name = trim((string) ($lims_customer_data->name ?? '')) ?: 'Recipient';

        $header = trim(strip_tags(html_entity_decode((string) $announcement->header)));
        $body = trim(strip_tags(html_entity_decode((string) $announcement->body)));
        $footer = trim(strip_tags(html_entity_decode((string) $announcement->footer)));

        $date = $announcement->created_at ? $announcement->created_at->format('d M Y, H:i') : date('d M Y, H:i');

        $msg = "🔗 *{$title}*\n\n";
        $msg .= \App\Helpers\WhatsAppFormatter::bilingualHeading('📢', 'ANNONCE', 'ANNOUNCEMENT');
        if ($header !== '') {
            $msg .= $header . "\n\n";
        }
        $msg .= "*Réf / Ref:* " . ($announcement->reference ?: $announcement->id) . "\n";
        $msg .= "*Date:* " . $date . "\n\n";
        if (!empty($announcement->subject)) {
            $msg .= "*Objet / Subject:* " . $announcement->subject . "\n\n";
        }
        $msg .= \App\Helpers\WhatsAppFormatter::bilingualGreeting($name);
        if ($body !== '') {
            $msg .= $body . "\n\n";
        }
        if ($footer !== '') {
            $msg .= $footer . "\n";
        }
        $msg .= "\n🌐 " . $title;

        return $msg;
    }

    public function sendAnnouncementMsg($announcement, $lims_customer_data)
    {
        $msg = $this->buildAnnouncementMessage($announcement, $lims_customer_data);

        try{
            $this->wpMessage($lims_customer_data->phone, $msg);
        }
        catch(\Exception $e){
        }

//        $attachment_path = public_path('public/announcement/attachment/'); // for local
        $attachment_path = public_path('announcement/attachment/');
//        if($announcement->attachment) {
//            $attachment_name = 'attachment-'.$announcement->attachment;
//            try{
//                $this->wpPDFAnnouncement($attachment_path . $announcement->attachment, $lims_customer_data, $attachment_name);
//            }
//            catch(\Exception $e){
//                $message = 'Announcement not sent. Please setup your whatsapp setting.';
//            }
//        }

        if(isset($announcement->attachmentlib[0])) {
            foreach ($announcement->attachmentlib as $key => $attachment) {
//                if($key == 0) {
//                    continue;
//                }
                $attachment_name = 'attachment-'.$attachment->attachment;
//                dd($attachment_path . $attachment->attachment);
                try{
                    $this->wpPDFAnnouncement($attachment_path . $attachment->attachment, $lims_customer_data, $attachment_name);
                }
                catch(\Exception $e){
                    $message = 'Announcement not sent. Please setup your whatsapp setting.';
                }
            }

        }
        return true;
    }

    public function sendPDF($announcement, $lims_customer_data, $to) {
        $data = [
            'to' => $to,
            'data' => $announcement
        ];
        // return view('pdf.announcement_pdf', $data);
        $pdf = PDF::loadView('pdf.announcement_pdf', $data)->setPaper('A4', 'portrait');

        $content = $pdf->download()->getOriginalContent();

        Storage::put('public/announcement/announcement.pdf',$content);
        $path = storage_path('app/public/announcement/announcement.pdf');
        $attachment_path = public_path('announcement/attachment/');
        $message = 'Announcement notification sent successfully';
        try{
            $this->wpPDFAnnouncement($path, $lims_customer_data, $lims_customer_data->name.'_announcement.pdf');
        }
        catch(\Exception $e){
            $message = 'Announcement not sent. Please setup your whatsapp setting.';
        }


        if($announcement->attachment) {
            $attachment_name = 'attachment-'.$announcement->attachment;
            try{
                $this->wpPDFAnnouncement($attachment_path . $announcement->attachment, $lims_customer_data, $attachment_name);
            }
            catch(\Exception $e){
                $message = 'Announcement not sent. Please setup your whatsapp setting.';
            }
        }
        if(isset($announcement->attachmentlib[0])) {
            foreach ($announcement->attachmentlib as $key => $attachment) {
                if($key == 0) {
                    continue;
                }
                $attachment_name = 'attachment-'.$attachment->attachment;
                try{
                    $this->wpPDFAnnouncement($attachment_path . $attachment->attachment, $lims_customer_data, $attachment_name);
                }
                catch(\Exception $e){
                    $message = 'Announcement not sent. Please setup your whatsapp setting.';
                }
            }

        }
        return $message;
    }

    public function sendSMS($announcement, $lims_customer_data)
    {
        $message = 'Announcement notification sent successfully';
        $account_sid = env('ACCOUNT_SID');
        $auth_token = env('AUTH_TOKEN');
        $twilio_phone_number = env('TWILIO_NUMBER');

        $data['message'] = $announcement->subject . "<br><br>";
        $data['message'] .= $announcement->header . "<br><br>";
        $data['message'] .= $announcement->body . "<br><br>";
        $data['message'] .= $announcement->footer . "<br><br><br>";
        $data['message'] .= request()->getSchemeAndHttpHost;
        try{
            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                $lims_customer_data->phone_number,
                array(
                    "from" => $twilio_phone_number,
                    "body" => $data['message']
                )
            );
        }
        catch(\Exception $e){
            $message = 'Announcement is not sent. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        }

        return $message;
    }

    public function sendPDFToCC($announcement, $lims_customer_data, $to) {
        $data = [
            'to' => $to,
            'data' => $announcement
        ];
        // return view('pdf.announcement_pdf', $data);
        $pdf = PDF::loadView('pdf.cc_announcement_pdf', $data)->setPaper('A4', 'portrait');

        $content = $pdf->download()->getOriginalContent();


        Storage::put('public/announcement/announcement.pdf',$content);
        $path = storage_path('app/public/announcement/announcement.pdf');
        $attachment_path = public_path('announcement/attachment/');
        $message = 'Announcement notification sent successfully';
        try{
            $this->wpPDFAnnouncement($path, $lims_customer_data, $lims_customer_data->name.'-announcement.pdf');
        }
        catch(\Exception $e){
            $message = 'Announcement not sent. Please setup your whatsapp setting.';
        }


        if($announcement->attachment) {
            $attachment_name = 'attachment-'.$announcement->attachment;
            try{
                $this->wpPDFAnnouncement($attachment_path . $announcement->attachment, $lims_customer_data, $attachment_name);
            }
            catch(\Exception $e){
                $message = 'Announcement not sent. Please setup your whatsapp setting.';
            }
        }
        if(isset($announcement->attachmentlib[0])) {
            foreach ($announcement->attachmentlib as $key => $attachment) {
                if($key == 0) {
                    continue;
                }
                $attachment_name = 'attachment-'.$attachment->attachment;
                try{
                    $this->wpPDFAnnouncement($attachment_path . $attachment->attachment, $lims_customer_data, $attachment_name);
                }
                catch(\Exception $e){
                    $message = 'Announcement not sent. Please setup your whatsapp setting.';
                }
            }

        }
        return $message;
    }

    public function announcementAttachmentDeleteFirst($id)
    {
        $letter = Announcement::where('id', $id)->first();
        @unlink('public/announcement/attachment/'.$letter->attachment);
        $letter->update(['attachment' => null]);
        return back()->with('not_permitted', 'Announcement attachment deleted successfully');
    }
}
