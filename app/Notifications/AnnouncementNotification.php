<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $announcement) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Academy Announcement: ' . $this->announcement->title)
            ->line($this->announcement->message);
    }
}
