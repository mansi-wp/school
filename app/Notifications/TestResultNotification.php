<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TestResultNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $test, public $result) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Examination Result Declared: ' . $this->test->name)
            ->line("Score: {$this->result->marks_obtained} / {$this->test->total_marks}")
            ->line("Rank: #{$this->result->rank} (Percentile: {$this->result->percentile}%)");
    }
}
