<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MailSystem;
use App\Models\User;
use App\Models\ExtraDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as FacadesMail;

class MailContorller extends Controller
{
    //
   
    public function index2($enquiry,$type)
    {
       
        
        if(isset($enquiry->customer_id)){
            $user = User::where('id', $enquiry->customer_id)->first();
          
        }
        if($type == 'registartion-user-mail'){
            $user = User::where('id', $enquiry->id)->first();
            $view = 'emails.user-registration';
            $subject = 'Thank you for registration | Adventures Overland';
            $mailData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'profile' => $user->profile ? url('storage/app/' . $user->profile) : '',
                'address' => $user->address,
                'city' => $user->city,
                'country' => $user->country,
                'state' => $user->state,
                'pincode' => $user->pincode,
                'dob' => $user->dob,
                'meal_preference' => $user->meal_preference,
                'blood_group' => $user->blood_group,
                'profession' => $user->profession,
                'emg_contact' => $user->emg_contact,
                't_size' => $user->t_size,
                'medical_condition' => $user->medical_condition,
                'tier' => $user->tier,
                'points' => $user->points,
                'referred_by' => $user->referred_by,
                'parent' => $user->parent,
                'relation' => $user->relation,
                'emg_name' => $user->emg_name,
                'Terms_accepted' =>$user->terms_accepted,
                'passport_front' => $user->passport_front ? url('storage/app/' . $user->passport_front) : '',
                'passport_back' => $user->passport_back ? url('storage/app/' . $user->passport_back) : '',
                'pan_gst' => $user->pan_gst ? url('storage/app/' . $user->pan_gst) : '',  
                'driving' => $user->driving ? url('storage/app/' . $user->driving) : '',
                'adhar_card' => $user->adhar_card ? url('storage/app/' . $user->adhar_card) : '',
               

            ];
            return FacadesMail::to($user->email)->send(new MailSystem($subject, $mailData, $view));

        }
        if ($type == 'registartion-mail') {
            
            $user = User::where('email', $enquiry['user_mail'])->first();
        
            $view = 'emails.registation-mail';
            $subject = 'New Registration from '.$user->first_name.' for ' . $enquiry['trip'] .' ! AO';
            $doc = ExtraDocuments::select('title','image','user_id')->where('user_id',$user->id)->get();
            $mailData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'profile' => $user->profile ? url('storage/app/' . $user->profile) : '',
                'address' => $user->address,
                'city' => $user->city,
                'country' => $user->country,
                'state' => $user->state,
                'pincode' => $user->pincode,
                'dob' => $user->dob,
                'meal_preference' => $user->meal_preference,
                'blood_group' => $user->blood_group,
                'profession' => $user->profession,
                'emg_contact' => $user->emg_contact,
                't_size' => $user->t_size,
                'medical_condition' => $user->medical_condition,
                'tier' => $user->tier,
                'points' => $user->points,
                'referred_by' => $user->referred_by,
                'parent' => $user->parent,
                'relation' => $user->relation,
                'emg_name' => $user->emg_name,
                'Terms_accepted' =>$user->terms_accepted,
                'passport_front' => $user->passport_front ? url('storage/app/' . $user->passport_front) : '',
                'passport_back' => $user->passport_back ? url('storage/app/' . $user->passport_back) : '',
                'pan_gst' => $user->pan_gst ? url('storage/app/' . $user->pan_gst) : '',  
                'driving' => $user->driving ? url('storage/app/' . $user->driving) : '',
                'adhar_card' => $user->adhar_card ? url('storage/app/' . $user->adhar_card) : '',
                'extra_doc'=> $doc
               
            ];
            return FacadesMail::to($enquiry['admin_email'])->send(new MailSystem($subject, $mailData, $view));
        }
        // if($type == 'seeker-mail'){
        //     $user = User::where('id', $enquiry->id)->first();
        //     $view = 'emails.seeker-mail';
        //     $subject = 'Don’t Miss Out! Send Us Your Adventure Seeker Form';
        //     $url = env('APP_URL') . 'seeker?&email=' . $user->email;
        //     $mailData = [
        //         'first_name' => $user->first_name,
        //         'last_name' => $user->last_name,
        //         'email' => $user->email,
        //         "url"=> $url

        //     ];
        //     return FacadesMail::to($user->email)->send(new MailSystem($subject, $mailData, $view));

            
        // }
        
        
        if($type == 'redeem'){

            $view = 'emails.redeem-email';
            $subject = 'Your points redeem inquiry | Adventures Overland!';

            $mailData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email'=>$user->email
            ];
            FacadesMail::to($user->email)->send(new MailSystem($subject, $mailData, $view));

        }
        if($type == 'redeem_sales'){
            $sales_email=setting('sales_email');
            $view = 'emails.redeem-sales';
            $subject = 'Your points redeem inquiry | Adventures Overland!';

            $mailData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email'=>$user->email,
                'points' => $enquiry->redeem_points,
                'trip' => $enquiry->expedition
            ];
            FacadesMail::to($sales_email)->send(new MailSystem($subject, $mailData, $view));
            
        }
        if($type == 'trans-sender-mail'){
            $reciver_data = User::where('email',$enquiry->receiver_email)->first();
            $view = 'emails.transfer-sender';
            $subject = 'Points Transferred Succefully to ' . $reciver_data->first_name. ' | Adventures Overland!';

         
            $mailData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'points' => $enquiry->trans_amt,
               
                'reciver_email'=>$enquiry->receiver_email,
                'email' => $user->email,
                
            ];
        }
        if ($type == 'trans-receiver-mail') {
        
            $reciver_data = User::where('email', $enquiry->receiver_email)->first();
            $sender_data = User::where('email', $enquiry->sender_email)->first();
            $view = 'emails.transfer-reciver';
            $subject = 'You Received Points from '. $sender_data->first_name.' | Adventures Overland!';
           
            $mailData = [
                'first_name' => $reciver_data->first_name,
                'last_name' => $reciver_data->last_name,
                'points' => $enquiry->trans_amt,

                'sender_mail' => $sender_data->email,
                'email' => $reciver_data->email,

            ];
        }

        if($type == 'enquiry'){
            $view = 'emails.enquiry';
            $subject = 'Thanks for your enquiry | Adventures Overland!';
            $mailData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,

            ];
        }


        if ($type == 'trans-receiver-mail') {
            FacadesMail::to($reciver_data->email)->send(new MailSystem($subject, $mailData, $view));
             Log::info('===2====Sending email to receiver: ' .$reciver_data->email);

        }elseif($type == 'trans-sender-mail'){
            Log::info('===1====Sending email to receiver: ' .$user->email);
            FacadesMail::to($user->email)->send(new MailSystem($subject,$mailData, $view));
        }

        // if($type == 'reminder'){

        //     $view = 'emails.redeem-email';
        //     $subject = 'Your points redeem inquery | Adventures Overland!';

        //     $mailData = [
        //         'first_name' => $user->first_name,
        //         'last_name' => $user->last_name,
        //         'email'=>$user->email,

        //     ];
        //     FacadesMail::to($enquiry)->send(new MailSystem($subject, $mailData, $view));

        // }

    }
}