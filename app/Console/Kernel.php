<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // This must be run every single day and just sends all the reminders for the next day.
    //  THIS IS TASK SCHEDULER!!!
        $schedule->command('send-event-reminders')
        ->daily();
        // we can store the output of the task to a file. we can do that by changing this interval wit hthe send output to and specifying a file path and we can append the output of a command to a file.
        // we can even email the outpiut to a specific email address.
        // php artisan schedule:work
        // Always remeber thgat during the development to check the schedule, the scheduler working, you have to run this command. It has to run in the background in a separate tab and it will run all the schedule jobs using the settings that we provide. 
        // Once we deploy the app in the server we'll have to use this cron that will automatically run our command every single minute, lets say. And then run a different command. So this cron automatically runs a different command, which is called scheduled run.
   

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
