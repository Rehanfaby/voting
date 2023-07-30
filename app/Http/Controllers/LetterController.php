<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Employee;
use App\GeneralSetting;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Letter;
use App\LetterCategory;
use App\LetterTemplate;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Role;

class LetterController extends Controller
{
    private $user;

    public function __construct() {


        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $role = Role::find($this->user->role_id);
            $permissions = Role::findByName($role->name)->permissions;

            foreach ($permissions as $permission) {
                $all_permission[] = $permission->name;
            }
            View::share ( 'all_permission', $all_permission);

            return $next($request);
        });
    }

    private function checkOtp($request, $letter) {
        if ($this->user->otp_verify == 1) {
            return true;
        }
        if($request->otp == $letter->otp && $letter->otp_time > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {
            return true;
        }
        return false;
    }

    public function next($id) {
        $letter = Letter::find($id);
        $data = Letter::where('is_edit',  $letter->is_edit)
                ->where('is_approve',  $letter->is_approve)
                ->where('is_sign',  $letter->is_sign)
                ->where('is_sent',  $letter->is_sent)
                ->where('is_rejected', $letter->is_rejected)
                ->where('is_active', true)
                ->where('id', '<', $id)
                ->orderBy('id', 'desc')
                ->first();
        if ($data == null) {
            return back()->with('not_permitted', 'No more letter found');
        }

        return view('letter.show', compact('data'));

    }

    public function prev($id) {
        $letter = Letter::find($id);
        $data = Letter::where('is_edit',  $letter->is_edit)
            ->where('is_approve',  $letter->is_approve)
            ->where('is_sign',  $letter->is_sign)
            ->where('is_sent',  $letter->is_sent)
            ->where('is_rejected', $letter->is_rejected)
            ->where('is_active', true)
            ->where('id', '>', $id)
            ->first();
        if ($data == null) {
            return back()->with('not_permitted', 'No more letter found');
        }

        return view('letter.show', compact('data'));

    }
    public function index()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->where('is_edit', 0)
            ->where('is_approve', 0)
            ->where('is_sign', 0)
            ->where('is_sent', 0)
            ->where('is_rejected', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.index', compact('data'));
    }

    public function all()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.all', compact('data'));
    }

    public function rejected()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->where('is_rejected', 1)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.rejected', compact('data'));
    }

    public function approved()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->where('is_approve', 1)
            ->where('is_sign', 0)
            ->where('is_sent', 0)
            ->where('is_rejected', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.approved', compact('data'));
    }

    public function edited()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->where('is_edit', 1)
            ->where('is_approve', 0)
            ->where('is_sign', 0)
            ->where('is_sent', 0)
            ->where('is_rejected', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.edited', compact('data'));
    }

    public function signed()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->where('is_approve', 1)
            ->where('is_sign', 1)
            ->where('is_sent', 0)
            ->where('is_rejected', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.signed', compact('data'));
    }

    public function sent()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->where('is_approve', 1)
            ->where('is_sign', 1)
            ->where('is_sent', 1)
            ->where('is_rejected', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.sent', compact('data'));
    }

    public function sentPrint()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->where('is_approve', 1)
            ->where('is_sign', 1)
            ->where('is_sent', 1)
            ->where('is_rejected', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.sent_print', compact('data'));
    }

    public function sentDownload()
    {
        $data = Letter::with('category')
            ->where('is_active', true)
            ->where('is_approve', 1)
            ->where('is_sign', 1)
            ->where('is_sent', 1)
            ->where('is_rejected', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view('letter.sent_download', compact('data'));
    }


    public function create()
    {
        $category = LetterCategory::where('is_active', true)->get();
        $template = LetterTemplate::where('is_active', true)->get();
        $user = Employee::where('is_active', true)->get();
        $customer = Customer::where('is_active', true)->get();
        return view('letter.create', compact('category', 'template', 'user', 'customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $image = $request->attachment;
        if (isset($image)) {
            $imageName = date("Ymdhis").$image->getClientOriginalName();
            $image->move('public/letter/attachment', $imageName);
            $data['attachment'] = $imageName;
        }

        if($data['people_type'] == "customer") {
            $data['to'] = implode(",", $data['to_customer']);
            $data['cc'] = isset($data['cc_customer']) ? implode(",", $data['cc_customer']) : null;
        } else {
            $data['to'] = implode(",", $data['to']);
            $data['cc'] = isset($data['cc']) ? implode(",", $data['cc']) : null;
        }
        $is_template = false;
        $data['created_by'] = Auth::user()->id;

        $letter_id = Letter::count('id');
        if(!$letter_id) {
            $letter_id = 0;
        }
        $zero = substr('0000000', strlen($letter_id));
        $letter_id++;
        $data['reference'] = GeneralSetting::first()->letter_serial_no . '/' . date('y') . '/' . $zero . $letter_id;

        if(isset($data['is_template'])) {
            $is_template = true;
        }
        unset($data['is_template']);
        unset($data['to_customer']);
        unset($data['cc_customer']);
        Letter::create($data);

        if($is_template == true) {
            unset($data['people_type']);
            unset($data['template_id']);
            unset($data['reference']);
            unset($data['is_approve']);
            unset($data['is_sign']);
            unset($data['to']);
            unset($data['cc']);
            LetterTemplate::create($data);
        }

        return redirect()->route('letter.create')->with('message', 'Letter created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Letter  $letter
     * @return \Illuminate\Http\Response
     */
    public function show(Letter $letter, $id)
    {
        $data = Letter::with('category', 'createdBy', 'approvedBy')->where('id', $id)->first();
        return view('letter.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Letter  $letter
     * @return \Illuminate\Http\Response
     */
    public function edit(Letter $letter, $id)
    {
        $category = LetterCategory::where('is_active', true)->get();
        $template = LetterTemplate::where('is_active', true)->get();
        $data = $letter->findorfail($id);
        if ($data->people_type == 'user') {
            $user = Employee::where('is_active', true)->get();
        } else {
            $user = Customer::where('is_active', true)->get();
        }
        return view('letter.edit', compact('category', 'template', 'user', 'data'));
    }

    public function editLast(Letter $letter, $id)
    {
        $data = $letter->findorfail($id);
        if ($data->people_type == 'user') {
            $user = Employee::where('is_active', true)->get();
        } else {
            $user = Customer::where('is_active', true)->get();
        }
        return view('letter.edit_last', compact( 'user', 'data'));
    }

    public function updateLast(Request $request, Letter $letter, $id)
    {
        $data = $request->all();

        $image = $request->attachment;
        if (isset($image)) {
            $imageName = date("Ymdhis").$image->getClientOriginalName();
            $image->move('public/letter/attachment', $imageName);
            $data['attachment'] = $imageName;
        }

        $data['to'] = implode(",", $data['to']);
        $data['cc'] = isset($data['cc']) ? implode(",", $data['cc']) : null;
        $data['edit_by'] = Auth::user()->id;
        $letter->find($id)->update($data);

        return redirect()->route('letter.index.signed')->with('message', 'Letter updated successfully');
    }
    public function update(Request $request, Letter $letter, $id)
    {
        $data = $request->all();

        $image = $request->attachment;
        if (isset($image)) {
            $imageName = date("Ymdhis").$image->getClientOriginalName();
            $image->move('public/letter/attachment', $imageName);
            $data['attachment'] = $imageName;
        }

        $data['to'] = implode(",", $data['to']);
        $data['cc'] = isset($data['cc']) ? implode(",", $data['cc']) : null;
        $data['is_rejected'] = 0;
        $data['reject_by'] = null;
        $data['is_edit'] = 1;
        $data['edit_by'] = Auth::user()->id;
        $letter->find($id)->update($data);

        return redirect()->route('letter.index')->with('message', 'Letter updated successfully');
    }

    public function editOk(Letter $letter, $id)
    {
        $data = [];
        $data['is_rejected'] = 0;
        $data['reject_by'] = null;
        $data['is_edit'] = 1;
        $data['edit_by'] = Auth::user()->id;
        $letter->find($id)->update($data);

        return back()->with('message', 'Letter updated successfully');
    }

    private function sendOTP($data) {
        if ($this->user->otp_verify == 1) {
            return true;
        }
        if ($data->otp_time == null || $data->otp_time < date('Y-m-d H:i:s', strtotime('-1 minutes'))) {
            $otp = rand(1, 999999);
            $msg = "Your OTP is: " . $otp . "\n That will be expired after 2 minutes";
            try {
                $this->wpMessage(Auth::user()->phone, $msg);
                $data->update(['otp'=>$otp, 'otp_time'=>date('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                return $otp;
            }
            return $otp;
        }
    }

    public function approve(Letter $letter, $id)
    {
        $data = $letter->findorfail($id);
        $this->sendOTP($data);
        return view('letter.approve', compact('data'));
    }

    public function approveStore(Request $request, Letter $letter, $id)
    {
        $letter = $letter->find($id);

        if ($this->checkOtp($request, $letter) == true) {
            $letter->find($id)->update(['is_approve'=>true, 'approved_by'=>Auth::user()->id, 'otp' => null]);
            return redirect()->route('letter.index.edited')->with('message', 'Letter Approved successfully');
        }
        $letter->find($id)->update(['otp' => null]);
        return back()->with('not_permitted', 'OTP is wrong or Expired');

    }

    public function reject(Letter $letter, $id)
    {
        $data = $letter->findorfail($id);
        $this->sendOTP($data);
        return view('letter.reject', compact('data'));
    }

    public function rejectStore(Request $request, Letter $letter, $id)
    {
        $letter = $letter->find($id);

        if ($this->checkOtp($request, $letter) == true) {
            $letter->find($id)->update(['is_rejected'=>true, 'is_edit' => 0, 'edit_by' => null, 'reject_by'=>Auth::user()->id, 'otp' => null]);
            return redirect()->back()->with('message', 'Letter Rejected successfully');
        }
        $letter->find($id)->update(['otp' => null]);
        return back()->with('not_permitted', 'OTP is wrong or Expired');

    }

    public function send(Letter $letter, $id)
    {
        $data = $letter->findorfail($id);
        $this->sendOTP($data);
        return view('letter.send', compact('data'));
    }

    public function sendStore(Request $request, Letter $letter, $id)
    {
        $letter = $letter->find($id);
        if($letter->people_type == 'customer') {
            $customer = Customer::class;
        } else {
            $customer = Employee::class;
        }

        if ($this->checkOtp($request, $letter) == true) {

            foreach (explode(",", $letter->to) as $to) {
                $lims_customer_data = $customer::find($to);
                $message = $this->sendPDF($letter, $lims_customer_data, $to);
                $message = $this->sendMail($letter, $lims_customer_data, $to);
                // return $message;
                // dd('mail sent');
            }

            $letter->find($id)->update(['is_sent'=>true, 'sent_by'=>Auth::user()->id, 'otp' => null]);
            return redirect()->back()->with('message', 'Letter Sent successfully');
        }
        $letter->find($id)->update(['otp' => null]);
        return back()->with('not_permitted', 'OTP is wrong or Expired');

    }

    public function download(Letter $letter, $id)
    {
        $letter = $letter->find($id);
        if($letter->people_type == 'customer') {
            $customer = Customer::class;
        } else {
            $customer = Employee::class;
        }
        $data = [
            'data' => $letter,
            'user' => $customer,
            'people_type' => $letter->people_type
        ];

        $pdf = PDF::loadView('pdf.letter_download_pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->download('letter.pdf');

    }

    public function print(Letter $letter, $id)
    {
        $letter = $letter->find($id);
        if($letter->people_type == 'customer') {
            $customer = Customer::class;
        } else {
            $customer = Employee::class;
        }
        $data = [
            'data' => $letter,
            'user' => $customer,
            'people_type' => $letter->people_type
        ];

        $pdf = PDF::loadView('pdf.letter_download_pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->stream('letter.pdf');

    }

    public function sendWhatsapp(Letter $letter, $id)
    {
        $letter = $letter->find($id);
        if($letter->people_type == 'customer') {
            $customer = Customer::class;
        } else {
            $customer = Employee::class;
        }

        foreach (explode(",", $letter->to) as $to) {
            $lims_customer_data = $customer::find($to);
            $message = $this->sendPDF($letter, $lims_customer_data, $to);
//            $message = $this->sendMail($letter, $lims_customer_data, $to);

        }

        $letter->find($id)->update(['is_sent'=>true, 'sent_by'=>Auth::user()->id, 'otp' => null]);
        return redirect()->back()->with('message', 'Letter Sent successfully');
    }

    public function sendEmail(Letter $letter, $id)
    {
        $letter = $letter->find($id);
        if($letter->people_type == 'customer') {
            $customer = Customer::class;
        } else {
            $customer = Employee::class;
        }

        foreach (explode(",", $letter->to) as $to) {
            $lims_customer_data = $customer::find($to);
//            $message = $this->sendPDF($letter, $lims_customer_data, $to);
            $message = $this->sendMail($letter, $lims_customer_data, $to);

        }

        $letter->find($id)->update(['is_sent'=>true, 'sent_by'=>Auth::user()->id, 'otp' => null]);
        return redirect()->back()->with('message', 'Letter Sent successfully');
    }

    private function sendMail($letter, $lims_customer_data, $to) {
        $cc_emails = [];
        if($letter->people_type == 'customer') {
            $customer = Customer::class;
        } else {
            $customer = Employee::class;
        }
        if ($letter->cc != null) {
            foreach (explode(",", $letter->cc) as $cc) {
                $lims_customer_data_cc = $customer::find($cc);
                if($lims_customer_data_cc->email) {
                    $cc_emails []= $lims_customer_data_cc->email;
                }
            }
        }

        $data = [
            'to' => $to,
            'data' => $letter,
            'mail' => $lims_customer_data->email,
            'subject' => $letter->subject,
            'cc_emails' => $cc_emails
        ];

        $message = 'Letter notification sent successfully';
        try{
            Mail::send( 'mail.letter_details', $data, function( $message ) use ($data)
            {
                $message->to($data['mail'])->subject($data['subject'])->cc($data['cc_emails']);
            });
        }
        catch(\Exception $e){
            $message = 'Letter is not sent. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        }
        return $message;
    }

    private function sendPDF($letter, $lims_customer_data, $to) {
        $data = [
            'to' => $to,
            'data' => $letter
        ];
        // return view('pdf.letter_pdf', $data);
        $pdf = PDF::loadView('pdf.letter_pdf', $data)->setPaper('A4', 'portrait');

        $content = $pdf->download()->getOriginalContent();

        Storage::put('public/letter/letter.pdf',$content);
        $path = storage_path('app/public/letter/letter.pdf');
        $attachment_path = public_path('letter/attachment/' . $letter->attachment);
        $message = 'Letter notification sent successfully';
        try{
            $this->wpPDFMessage($path, $lims_customer_data, 'letter.pdf');
        }
        catch(\Exception $e){
            $message = 'Letter not sent. Please setup your whatsapp setting.';
        }


        if($letter->attachment) {
            $attachment_name = 'attachment-'.$letter->attachment;
            try{
                $this->wpPDFMessage($attachment_path, $lims_customer_data, $attachment_name);
            }
            catch(\Exception $e){
                $message = 'Letter not sent. Please setup your whatsapp setting.';
            }
        }
        return $message;
    }

    public function sign(Letter $letter, $id)
    {
        $data = $letter->findorfail($id);
        $this->sendOTP($data);
        return view('letter.sign', compact('data'));
    }

    public function signStore(Request $request, Letter $letter, $id)
    {
        $letter = $letter->find($id);

        if ($this->checkOtp($request, $letter) == true) {
            $letter->find($id)->update(['is_sign'=>true, 'signed_by'=>Auth::user()->id, 'otp' => null]);
            return redirect()->route('letter.index.approved')->with('message', 'Letter Signed successfully');
        }
        $letter->find($id)->update(['otp' => null]);
        return back()->with('not_permitted', 'OTP is wrong or Expired');
    }


    public function signSend(Letter $letter, $id)
    {
        $data = $letter->findorfail($id);
        $this->sendOTP($data);
        return view('letter.signSend', compact('data'));
    }
    public function signSendStore(Request $request, Letter $letter, $id)
    {
        $letter = $letter->find($id);

        if ($this->checkOtp($request, $letter) == true) {
            $letter->find($id)->update(['is_sign'=>true, 'signed_by'=>Auth::user()->id, 'otp' => null]);

            if($letter->people_type == 'customer') {
                $customer = Customer::class;
            } else {
                $customer = Employee::class;
            }
            foreach (explode(",", $letter->to) as $to) {
                $lims_customer_data = $customer::find($to);
                $message = $this->sendPDF($letter, $lims_customer_data, $to);
                $message = $this->sendMail($letter, $lims_customer_data, $to);
            }

            $letter->find($id)->update(['is_sent'=>true, 'sent_by'=>Auth::user()->id, 'otp' => null]);
            return redirect()->back()->with('message', 'Letter Signed & Sent Successfully');
        }

        $letter->find($id)->update(['otp' => null]);
        return back()->with('not_permitted', 'OTP is wrong or Expired');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Letter  $letter
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Letter::find($id);
        $data->is_active = false;
        $data->save();
        return back()->with('not_permitted','Data deleted successfully');
    }

    public function templateInfo ($id) {
        return LetterTemplate::find($id);
    }


    private function sendMultiOTP($id_array) {
        if (Auth::user()->otp_verify == 1) {
            return true;
        }
        $data = Letter::find($id_array[0]);

        if ($data->otp_time == null || $data->otp_time < date('Y-m-d H:i:s', strtotime('-30 seconds'))) {
            $otp = rand(1, 999999);
            $msg = "Your OTP is: " . $otp . "\n That will be expired after 2 minutes";
            foreach ($id_array as $id) {
                $letter = Letter::find($id);
                $letter->update(['otp'=>$otp, 'otp_time'=>date('Y-m-d H:i:s')]);
            }
            try {
                $this->wpMessage(Auth::user()->phone, $msg);
            } catch (\Exception $e) {
                return $otp;
            }
            return $otp;
        }
    }


    public function multipleApprove(Letter $letter, Request $request)
    {
        $id_array = [];
        $ids = $request->ids;
        if($ids == null) {
            return redirect()->back()->with('not_permitted', 'No letter is selected');
        }

        foreach ($ids as $key => $option) {
            $id_array[] = $key;
        }

        $this->sendMultiOTP($id_array);
        return view('letter.multiApprove', compact('id_array'));
    }


    public function multipleApproveStore(Request $request, Letter $letter)
    {
        $letter = $letter->find($request->ids[0]);
        if ($this->checkOtp($request, $letter) == true) {
            foreach ($request->ids as $id) {
                Letter::find($id)->update(['is_approve'=>true, 'approved_by'=>Auth::user()->id, 'otp' => null]);
            }
            return redirect()->route('letter.index.edited')->with('message', 'Letter Approved successfully');
        }

        $letter->update(['otp' => null]);
        return redirect()->route('letter.index.edited')->with('not_permitted', 'OTP is wrong or Expired');

    }


    public function multipleOk(Letter $letter, Request $request)
    {
        $id_array = [];
        $ids = $request->ids;
        if($ids == null) {
            return redirect()->back()->with('not_permitted', 'No letter is selected');
        }

        foreach ($ids as $key => $option) {
            $id_array[] = $key;
        }

        $this->sendMultiOTP($id_array);
        return view('letter.multiOk', compact('id_array'));
    }


    public function multipleOkStore(Request $request, Letter $letter)
    {
        $letter = $letter->find($request->ids[0]);
        if ($this->checkOtp($request, $letter) == true) {
            foreach ($request->ids as $id) {
                Letter::find($id)->update(['is_edit'=>true, 'edit_by'=>Auth::user()->id, 'otp' => null]);
            }
            return redirect()->route('letter.index')->with('message', 'Letter Ok successfully');
        }

        $letter->update(['otp' => null]);
        return redirect()->route('letter.index')->with('not_permitted', 'OTP is wrong or Expired');

    }

    public function multipleSign(Letter $letter, Request $request)
    {
        $id_array = [];
        $ids = $request->ids;
        if($ids == null) {
            return redirect()->back()->with('not_permitted', 'No letter is selected');
        }

        foreach ($ids as $key => $option) {
            $id_array[] = $key;
        }

        $this->sendMultiOTP($id_array);
        return view('letter.multiSign', compact('id_array'));
    }


    public function multipleSignStore(Request $request, Letter $letter)
    {
        $letter = $letter->find($request->ids[0]);
        if ($this->checkOtp($request, $letter) == true) {
            foreach ($request->ids as $id) {
                Letter::find($id)->update(['is_sign'=>true, 'signed_by'=>Auth::user()->id, 'otp' => null]);
            }
            return redirect()->route('letter.index.approved')->with('message', 'Letter Signed successfully');
        }

        $letter->update(['otp' => null]);
        return redirect()->route('letter.index.approved')->with('not_permitted', 'OTP is wrong or Expired');

    }

    public function multipleSend(Letter $letter, Request $request)
    {
        $id_array = [];
        $ids = $request->ids;
        if($ids == null) {
            return redirect()->back()->with('not_permitted', 'No letter is selected');
        }
        foreach ($ids as $key => $option) {
            $id_array[] = $key;
        }

        $this->sendMultiOTP($id_array);
        return view('letter.multiSend', compact('id_array'));
    }


    public function multipleSendStore(Request $request, Letter $letter)
    {
        $letter = $letter->find($request->ids[0]);
        if ($this->checkOtp($request, $letter) == true) {
        foreach ($request->ids as $id) {
            $letter = Letter::find($id);
            if ($letter->people_type == 'customer') {
                $customer = Customer::class;
            } else {
                $customer = Employee::class;
            }
            foreach (explode(",", $letter->to) as $to) {
                $lims_customer_data = $customer::find($to);
                $message = $this->sendPDF($letter, $lims_customer_data, $to);
                    $message = $this->sendMail($letter, $lims_customer_data, $to);
                }
                $letter->find($id)->update(['is_sent' => true, 'sent_by' => Auth::user()->id, 'otp' => null]);

        }
        return redirect()->route('letter.index.sent')->with('message', $message);
        }

        $letter->update(['otp' => null]);
        return redirect()->route('letter.index.sent')->with('not_permitted', 'OTP is wrong or Expired');
    }


    public function multipleDownloadStore(Request $request, Letter $letter)
    {
        if($request->ids == null) {
            return redirect()->back()->with('not_permitted', 'No letter is selected');
        }

        if (isset($request->ids[0]) == false ) {
        foreach ($request->ids as $key => $id) {
            $ids[] = $key;
        }
        } else {
            $ids = $request->ids;
        }


//        if ($this->checkOtp($request, $letter) == true) {
                $data = [
                    'ids' => $ids
                ];
                $pdf = PDF::loadView('pdf.multiple_letter_download_pdf', $data)->setPaper('A4', 'portrait');
                return $pdf->download('letter.pdf');
//        }
//
//        $letter->update(['otp' => null]);
//        return redirect()->route('letter.index.signed')->with('not_permitted', 'OTP is wrong or Expired');
    }

    public function multiplePrintStore(Request $request, Letter $letter)
    {
        if($request->ids == null) {
            return redirect()->back()->with('not_permitted', 'No letter is selected');
        }

        if (isset($request->ids[0]) == false ) {
            foreach ($request->ids as $key => $id) {
                $ids[] = $key;
            }
        } else {
            $ids = $request->ids;
        }

//        $letter = $letter->find($ids[0]);
//        if ($this->checkOtp($request, $letter) == true) {
            $data = [
                'ids' => $ids
            ];
            $pdf = PDF::loadView('pdf.multiple_letter_download_pdf', $data)->setPaper('A4', 'portrait');
            return $pdf->stream('letter.pdf');
//        }

//        $letter->update(['otp' => null]);
//        return redirect()->route('letter.index.signed')->with('not_permitted', 'OTP is wrong or Expired');
    }

}
