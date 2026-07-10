<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\AnnouncementAttachment;
use App\Customer;
use App\GeneralSetting;
use App\Helpers\AnnouncementRecipient;
use App\User;
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
        $clone = null;
        if ($request->filled('clone')) {
            $clone = Announcement::with('attachmentLib')->find($request->clone);
        }

        return view('announcement.create', compact('categories', 'clone'));
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
            }
            $data['recipients_json'] = null;
        } elseif (is_array($recipientPayload) && count($recipientPayload)) {
            $data['recipients_json'] = AnnouncementRecipient::storePayload($recipientPayload);
            $data['to'] = implode(',', array_map(function ($r) {
                return AnnouncementRecipient::recipientKey($r);
            }, json_decode($data['recipients_json'], true)));
        } elseif ($audience === 'everyone') {
            $all = AnnouncementRecipient::listForCategory('everyone');
            $data['recipients_json'] = AnnouncementRecipient::storePayload($all);
            $data['to'] = implode(',', array_map([AnnouncementRecipient::class, 'recipientKey'], $all));
        } else {
            return back()->with('not_permitted', 'Please select at least one recipient.');
        }

        $scheduleLater = $request->boolean('schedule_later');
        $scheduleTimes = array_filter((array) $request->input('schedule_times', []));
        $reminderTimes = array_filter((array) $request->input('reminder_times', []));
        $data['schedules_json'] = $scheduleLater ? json_encode(AnnouncementRecipient::normalizeSlots($scheduleTimes)) : null;
        $data['reminders_json'] = count($reminderTimes) ? json_encode(AnnouncementRecipient::normalizeSlots($reminderTimes)) : null;

        $sendNow = $request->boolean('send_now');
        if ($scheduleLater && count($scheduleTimes)) {
            $data['status'] = 'scheduled';
            $data['is_sent'] = false;
        } else {
            $data['status'] = $sendNow ? 'draft' : 'draft';
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
        $announcement = Announcement::create($data);

        if ($attachments) {
            foreach ($attachments as $key => $attachment) {
                $attachmentName = date('Ymdhis') . $attachments[$key]->getClientOriginalName();
                $attachments[$key]->move('public/announcement/attachment', $attachmentName);
                AnnouncementAttachment::create(['announcement_id' => $announcement->id, 'attachment' => $attachmentName]);
            }
        }

        if ($sendNow && !$scheduleLater) {
            $this->deliverAnnouncement($announcement);
            $announcement->update(['is_sent' => true, 'status' => 'sent']);
        }

        return redirect()->route('announcement.index')->with('message', $sendNow ? 'Announcement sent successfully' : 'Announcement saved successfully');
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
        $this->deliverAnnouncement($announcement);
        $announcement->update(['is_sent' => true, 'status' => 'sent']);

        return redirect()->back()->with('message', 'Announcement has been sent');
    }

    public function deliverAnnouncement(Announcement $announcement): void
    {
        $announcement->load('attachmentLib');
        $recipients = AnnouncementRecipient::resolveForAnnouncement($announcement);

        foreach ($recipients as $row) {
            $recipient = AnnouncementRecipient::toRecipientObject($row);
            if (empty($recipient->phone)) {
                continue;
            }
            $this->sendAnnouncementMsg($announcement, $recipient);
            usleep(500000);
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

    public function sendAnnouncementMsg($announcement, $lims_customer_data)
    {
        $msg = strip_tags(html_entity_decode($announcement->header)) . "\r\n\n";
        $msg .= "Ref: " . $announcement->id . "\r\n";
        $msg .= "Date: " . $announcement->created_at . "\r\n\n";
        $msg .= "Subject: " . $announcement->subject . "\r\n\n";
        $msg .= "Dear: " . $lims_customer_data->name . "\r\n\n";
        $msg .= strip_tags(html_entity_decode($announcement->body)) . "\r\n\n";
        $msg .= strip_tags(html_entity_decode($announcement->footer)) . "\r\n";

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
