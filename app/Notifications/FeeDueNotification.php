<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FeeDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $student, public $dueAmount, public $dueDate) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Fee Installment Due Notice')
            ->line("Fee installment of ₹{$this->dueAmount} for {$this->student->full_name} is due on {$this->dueDate}.")
            ->action('View Ledger', url('/student/fees'));
    }
}
