<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mews\Purifier\Facades\Purifier;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;
        $data['subscs'] = Subscriber::when($term, function ($query, $term) {
            return $query->where('email', 'LIKE', '%' . $term . '%');
        })->orderBy('id', 'DESC')->paginate(10);

        return view('backend.subscribers.index', $data);
    }

    public function mailsubscriber()
    {
        return view('backend.subscribers.mail');
    }

    public function subscsendmail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        $sub = $request->subject;
        $msg = Purifier::clean($request->message, 'youtube');

        $subscs = Subscriber::all();

        $settings = DB::table('basic_settings')->select('smtp_host', 'smtp_username', 'smtp_password', 'encryption', 'smtp_port', 'smtp_status', 'from_mail', 'from_name')->first();


        $mail = new PHPMailer(true);

        if ($settings->smtp_status == 1) {
            try {
                //Server settings
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = $settings->smtp_host;                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = $settings->smtp_username;                     // SMTP username
                $mail->Password   = $settings->smtp_password;                               // SMTP password
                $mail->SMTPSecure = $settings->encryption;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = $settings->smtp_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                //Recipients
                $mail->setFrom($settings->from_mail, $settings->from_name);

                foreach ($subscs as $key => $subsc) {
                    $mail->addAddress($subsc->email);     // Add a recipient
                }
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $sub;
                $mail->Body    = $msg;

                $mail->send();

                Session::flash('success', 'Mail sent successfully!');
                return back();
            } catch (Exception $e) {
                Session::flash('warning', 'Mail could not send!');
                return back();
            }
        } else {
            Session::flash('warning', 'Mail could not send. please set smtp first.!');
            return back();
        }
    }


    public function delete(Request $request)
    {

        $subscriber = Subscriber::where('id', $request->subscriber_id)->first();
        $subscriber->delete();

        Session::flash('success', 'Subscriber deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $subscriber = Subscriber::where('id', $id)->first();
            $subscriber->delete();
        }

        Session::flash('success', 'Subscribers deleted successfully!');
        return "success";
    }
}
