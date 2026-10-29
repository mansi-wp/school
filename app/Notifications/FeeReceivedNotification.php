<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FeeReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $payment) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Fee Receipt Acknowledgment: ' . $this->payment->receipt_number)
            ->line("Received fee payment of ₹{$this->payment->amount_paid} via {$this->payment->payment_method}.")
            ->line("Receipt No: {$this->payment->receipt_number}");
    }
}
