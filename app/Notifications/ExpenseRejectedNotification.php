<?php

namespace App\Notifications;

use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Expense $expense
    ){}


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
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
                    ->error()
                    ->line('EXPENSE REJECTED')
                    ->line($this->expense->uuid);
    }

}
