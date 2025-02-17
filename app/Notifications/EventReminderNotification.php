<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification implements ShouldQueue // the ShouldQueue will tell the Laravel that this notification logic should be run in the background, not during the request
// Now everytime this is being run, this notification is dispatched.
{
    use Queueable;

    /**
     * We can pass extra data in the constructor
     */
    public function __construct(public Event $event) // Now this class have this event property which would be public
    // This public Event $event we need this event to know how to consturct the email
    {
       
    }

//  Via method defines all the channels on which this notrification should be delivered, and it will be delivered on all of them.
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Reminder: You have an upcoming Event!')
                    ->action('View Event', route('events.show', $this->event->id))
                    ->line("The event {$this->event->name} starts at {$this->event->start_time}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'event_start_time' => $this->event->start_time
        ];
    }
}
