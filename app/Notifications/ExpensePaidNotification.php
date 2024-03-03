<?php

namespace App\Notifications;

use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpensePaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Expense $expense,
        public Payment $payment
    ){}

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
                    ->success()
                    ->line('EXPENSE PAID')
                    ->line($this->expense->uuid)
                    ->line('at:')
                    ->line($this->payment->updated_at);
    }

}
