<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Faq;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function site()
    {
        $data = Setting::first();
        return view('admin.settings.site', compact('data'));
    }
    public  function loyaltyPointTermsCondition()
    {
        $data = Setting::first() ?? null;
        return view('admin.settings.loyaltyPoints_terms&Conditons', compact('data'));
    }
    public function loyaltyPointFaq()
    {
        $data = Faq::all();
        return view('admin.settings.loyalty_point_faq', compact('data'));
    }

    public function thirdParty()
    {
        $data = Setting::first();
        return view('admin.settings.third-party', compact('data'));
    }

    public function contact()
    {
        $data = Setting::first();
        return view('admin.settings.contact', compact('data'));
    }

    public function terms()
    {
        $data = Setting::first();
        return view('admin.settings.terms', compact('data'));
    }

    public function privacy()
    {
        $data = Setting::first();
        return view('admin.settings.privacy', compact('data'));
    }

    public function about()
    {
        $data = Setting::first();
        return view('admin.settings.about', compact('data'));
    }

    public function membership()
    {
        $data = Setting::first();
        return view('admin.settings.membership', compact('data'));
    }

    public function important()
    {
        $data = Setting::first();
        return view('admin.settings.important', compact('data'));
    }

    public function tier()
    {
        $data = Setting::first();
        return view('admin.settings.tier', compact('data'));
    }

    public function earn()
    {
        $data = Setting::first();
        return view('admin.settings.earn', compact('data'));
    }

    public function redeem()
    {
        $data = Setting::first();
        return view('admin.settings.redeem', compact('data'));
    }

    public function transfer()
    {
        $data = Setting::first();
        return view('admin.settings.transfer', compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function siteUpdate(Request $request)
    {
        // dd($request->birthday_email);
        $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_theme' => ['required'],
            'copyright' => ['required'],
        ]);


        $data = Setting::find(1);
        if($request->hasfile('logo')){
            @unlink('storage/app/'.$data->logo);
            $data->logo = $request->file('logo')->store('admin/setting');
        }
        $data->site_name = $request->site_name;
        $data->copyright = $request->copyright;
        $data->site_theme = $request->site_theme;
        $data->terms_link = $request->terms_link;
        $data->faq_link = $request->faq_link;
        $data->merchandise_email = $request->merchandise_email;
        $data->stationary_email = $request->stationary_email;
        $data->admin_mail = $request->admin_mail;
        $data->account_mail = $request->account_mail;
        $data->operation_mail = $request->operation_mail;
        $data->sales_email = $request->sales_email;
        $data->birthday_email = $request->birthday_email;

        $data->trip_ongoing_report = $request->trip_ongoing_report;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function thirdPartyUpdate(Request $request)
    {

        $data = Setting::find(1);
        $data->mail_status = $request->mail_status;
        $data->whatsapp_status = $request->whatsapp_status;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function contactUpdate(Request $request)
    {

        $data = Setting::find(1);

        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function termsUpdate(Request $request)
    {

        $data = Setting::find(1);

        $data->terms_condition = $request->terms_condition;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }
    public function loyaltyPointTermsConditionUpdate(Request $request)
    {

        $data = Setting::find(1);

        $data->loyalty_points_terms = $request->loyalty_points_terms;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function privacyUpdate(Request $request)
    {

        $data = Setting::find(1);

        $data->privacy_policy = $request->privacy_policy;

        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function aboutUpdate(Request $request)
    {

        $data = Setting::find(1);

        $data->about_us = $request->about_us;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function importantUpdate(Request $request)
    {

        $data = Setting::find(1);

        $data->important_notes = $request->important_notes;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function tierUpdate(Request $request)
    {

        $data = Setting::find(1);

        $data->discovery = $request->discovery;
        $data->explorer = $request->explorer;
        $data->legends = $request->legends;
        $data->adventurer = $request->adventurer;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function earnUpdate(Request $request)
    {
        $data = Setting::find(1);

        $data->how_to_earn_title = $request->how_to_earn_title;
        $data->how_to_earn = $request->how_to_earn;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function redeemUpdate(Request $request)
    {
        $data = Setting::find(1);

        $data->redeem_points_title = $request->redeem_points_title;
        $data->redeem_points = $request->redeem_points;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function transferUpdate(Request $request)
    {
        $data = Setting::find(1);
        $data->transfer_points = $request->transfer_points;
        $data->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function loyaltypointfaqstore(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|string|max:255',
            'answers' => 'required|array',
        ]);
    
        foreach ($request->questions as $index => $question) {
            $id = $request->ids[$index] ?? null;
            $answer = $request->answers[$index];
    
            // Check uniqueness of question
            $existing = Faq::where('question', $question);
            if ($id) {
                $existing->where('id', '!=', $id);
            }
    
            if ($existing->exists()) {
                return redirect()->back()->with('error',
                  "The question \"$question\" has already been taken."
                );
            }
    
            if ($id) {
                // Update existing FAQ
                $faq = Faq::find($id);
                if ($faq) {
                    $faq->question = $question;
                    $faq->answer = $answer;
                    $faq->save();
                }
            } else {
                // Create new FAQ
                Faq::create([
                    'question' => $question,
                    'answer' => $answer,
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'FAQs saved successfully!');
    }

   public function loyaltypointfaqdelete(Request $request)
    {
        $faq = Faq::find($request->id);
        if ($faq) {
            $faq->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }


}