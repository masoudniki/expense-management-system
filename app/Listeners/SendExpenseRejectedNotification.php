<?php

namespace App\Listeners;

use App\Events\ExpenseRejected;
use App\Notifications\ExpensePaidNotification;
use App\Notifications\ExpenseRejectedNotification;

class SendExpenseRejectedNotification
{
    public function handle(ExpenseRejected $event){
        $event->expense->user->notify(
            new ExpenseRejectedNotification($event->expense)
        );
    }
}
