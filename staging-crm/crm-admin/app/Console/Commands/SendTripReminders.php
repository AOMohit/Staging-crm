<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;
use App\Models\CarbonInfo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Events\SendMailEvent;

class SendTripReminders extends Command
{
    protected $signature = 'send:trip-reminders';
    protected $description = 'Send reminders 3, 2, 1 weeks before trip if Carbon Info is missing';

    public function handle()
    {
        $today = Carbon::today();

        $trips = Trip::whereDate('start_date', '>', $today)->get();
       
        if ($trips->isEmpty()) {
            Log::info("No upcoming trips found for reminders on {$today->toDateString()}");
            return 0;
        }
       
        foreach ($trips as $trip) {
            
            $startDate = Carbon::parse($trip->start_date);

            // Calculate reminder dates
            $reminderDates = [
                $startDate->copy()->subWeeks(3)->toDateString(),
                $startDate->copy()->subWeeks(2)->toDateString(),
                $startDate->copy()->subWeeks(1)->toDateString(),
            ];
            if (in_array($today->toDateString(), $reminderDates)) {
                $carbonInfoExists = CarbonInfo::where('trip_id', $trip->id)->exists();
                if (!$carbonInfoExists) {
                    Log::info("Carbon Info is missing for Trip ID {$trip->id} on {$today->toDateString()}");
                    // Send reminder email
                    $adminEmail = setting('admin_mail');
                    $opsEmail = setting('operation_mail');
                   //mail to admin
                    if($adminEmail){
                        $data = [
                            'name' => 'Admin',
                            'trip_id' => $trip->id,
                            'trip_start_date' => $trip->start_date,
                            'trip_end_date' => $trip->end_date,
                            'trip_name' => $trip->name ?? "Trip",
                            'admin_email'=>$adminEmail,
                        ];
                        if(setting('mail_status') == 1){
                            event(new SendMailEvent("$adminEmail", 'Carbon Info is Missing for'.$trip->name, 'emails.trip-reminders-carbon-info', $data));
                        }
                    }
                    //mail to ops
                    if($opsEmail){
                        $data = [
                            'name' => 'Operations',
                            'trip_id' => $trip->id,
                            'trip_start_date' => $trip->start_date,
                            'trip_end_date' => $trip->end_date,
                            'trip_name' => $trip->name ?? "Trip",
                            'ops_email'=>$opsEmail,
                        ];
                        if(setting('mail_status') == 1){
                            event(new SendMailEvent("$opsEmail", 'Carbon Info is Missing for'.$trip->name, 'emails.trip-reminders-carbon-info', $data));
                        }
                    }
                    Log::info("Reminder sent for Trip ID {$trip->id} on {$today->toDateString()}");
                } else {
                    Log::info("Carbon Info already exists for Trip ID {$trip->id}");
                }
            }
            else {
                Log::info("No reminder needed for Trip ID {$trip->id} on {$today->toDateString()}");
            }
            
        }

        return 0;
    }
}
