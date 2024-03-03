<?php

namespace App\Jobs;

use App\Models\Enums\PaymentStatus;
use App\Models\Expense;
use App\Models\Payment;
use App\Services\Payment\Interfaces\PaymentGatewayInterface;
use App\Services\Payment\PaymentProcessorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpensePaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PaymentGatewayInterface $paymentGateway;
    /**
     * Create a new job instance.
     */
    public function __construct(
        public Expense $expense,
        public Payment $payment
    )
    {
        // create payment gateway according to iban
        $this->paymentGateway = PaymentProcessorService::create($this->expense->iban);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // check expense has been paid?
        if($this->expense->is_paid){
            Log::debug('expense :expense_uuid has been paid by another payment.try to fail current payment',['expense_uuid'  => $this->expense->uuid]);
            $this->payment->update(['status' => PaymentStatus::FAILED]);
            return;
        }

        // set a lock to handle race condition on concurrent payments
        $lock=Cache::lock($this->expense->uuid,150);

        if(!$lock->get()){
            Log::debug('another job has acquired a lock on expense :expense_uuid try some other time',['expense_uuid'  => $this->expense->uuid]);
            $this->release($this->attempts()*150);
            return;
        }

        // lets pay the payment via correct gateway
        try {
            DB::transaction(function (){
                $this->paymentGateway->processPayment(
                    $this->expense->amount,
                    $this->expense->iban
                );

                $this->payment->update(['status' => PaymentStatus::SUCCESS]);

                $this->expense->update(['is_paid' => true]);

            });
            Log::debug("payment :payment_uuid for expense :expense_uuid has been successfully processed.",['payment_uuid' => $this->payment->uuid, 'expense_uuid' => $this->expense->uuid]);
        }catch (\Exception $exception){
            $this->payment->update(['status' => PaymentStatus::FAILED]);
            Log::error("error while processing payment :payment_uuid for expense :expense_uuid has been failed.exception message: :message :trace",['payment_uuid' => $this->payment->uuid, 'expense_uuid' => $this->expense->uuid,'message' => $exception->getMessage(), 'trace'=>$exception->getTraceAsString()]);
        }

        $lock->release();
    }
}
