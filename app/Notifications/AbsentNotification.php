<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AbsentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $student, public $date, public $batch) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Absence Notice: ' . $this->student->full_name)
            ->line("Dear Parent, {$this->student->full_name} was marked ABSENT today ({$this->date}) in {$this->batch->name}.")
            ->line('Please contact the front desk if unexpected.');
    }
}
