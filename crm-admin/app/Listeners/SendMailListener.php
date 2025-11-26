<?php

namespace App\Listeners;

use App\Events\SendMailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use App\Models\BookingLog;
use App\Mail\SendMail;
use App\Models\Setting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SendMailListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // 
    }

    /**
     * Handle the event.
     */
    public function handle(SendMailEvent $event)
    {
        try{
            $recipientEmail = $event->recipientEmail;
            $subject = $event->subject;
            $view = $event->view;
            // $data = $event->data;
            $data = $event->data ?? [];
            
            $bccEmail = null;
            if (isset($data['for']) && $data['for'] == 'birthday_mail') {
                $bccEmail = setting('birthday_email');
                if (!empty($bccEmail)) {
                    $bccEmail = array_filter(
                        array_map('trim', explode(',', $bccEmail)),
                        function($email) {
                            return filter_var($email, FILTER_VALIDATE_EMAIL);
                        }
                    );
                    if (empty($bccEmail)) {
                        $bccEmail = null;
                    }
                } else {
                    $bccEmail = null;
                }
            }

            // Send the email
            
                $mail = new SendMail($subject, $view, $data);

                // Attach files only if attachments are provided in $data
                if (!empty($data['attachments']) && is_array($data['attachments'])) {
                    foreach ($data['attachments'] as $filePath) {
                        try {
                            if (is_string($filePath) && file_exists($filePath)) {
                                // attach file to the Mailable instance
                                $mail->attach($filePath);
                            } else {
                                Log::warning("Attachment not found or invalid path: " . (string)$filePath);
                            }
                        } catch (\Throwable $e) {
                            // don't break the mail send if one attachment fails
                            Log::warning("Failed attaching file to email: {$filePath} - " . $e->getMessage());
                        }
                    }
                }

                if ($bccEmail) {
                    Mail::to($recipientEmail)
                        ->bcc($bccEmail)
                        ->send($mail);
                } else {
                    Mail::to($recipientEmail)->send($mail);
                }
            
            
            // create Activity
            if(isset($data) && isset($data['for']) && $data['for'] == 'customer'){
                $viewName = explode(".", $view);
                $viewName = end($viewName);
                
                $action = "<b style='color:blue;cursor:pointer;text-decoration: underline;'>".$viewName." Email</b> has been sent to <b>".$data['name']."<b>";
                $other = view($view, compact('data'))->render();

                $activity = new BookingLog();
                $activity->admin_id = Auth::user()->id;
                $activity->booking_id = $data['booking_id'] ?? "";
                $activity->page = "Send Email";
                $activity->action = $action ?? "";
                $activity->other = $other ?? "";
                $activity->save();
            }

            // create Activity
        } catch (\Throwable $e) {
            Log::error('Error in sending email: ' . $e->getMessage());
            throw new \Exception("Error sending email:".$e->getMessage());
        }
        
    }
}