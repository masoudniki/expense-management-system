<?php

namespace App\Jobs;

use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpenseDispatcherPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $selectedExpenseIds = []
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // according to selectedExpense ids we find the expenses or use the selected values
        $unpaidExpenseRequests=[];
        if($this->selectedExpenseIds){
            // load confirmed and unpaid expense-requests and create payment job for each expense
            $unpaidExpenseRequests=Expense::query()->where('is_confirmed',false)->where('is_paid',false)->get();
        }else{
            $unpaidExpenseRequests=Expense::query()->whereIn('id',$this->selectedExpenseIds)->get();
        }


        foreach ($unpaidExpenseRequests as $unpaidExpenseRequest){
            ExpensePaymentJob::dispatch(
                $unpaidExpenseRequest,
                Payment::query()->create()
            );
        }

    }




}
