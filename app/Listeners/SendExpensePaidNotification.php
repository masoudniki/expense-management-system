<?php

namespace App\Listeners;

use App\Events\ExpensePaid;
use App\Events\ExpenseRejected;
use App\Notifications\ExpensePaidNotification;

class SendExpensePaidNotification
{
    public function handle(ExpensePaid $event){
        $event->expense->user->notify(
            new ExpensePaidNotification($event->expense,$event->payment)
        );
    }
}
