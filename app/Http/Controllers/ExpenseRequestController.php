<?php

namespace App\Http\Controllers;

use App\Events\ExpenseRejected;
use App\Http\Requests\ExpenseConfirmRequest;
use App\Http\Requests\ExpenseRejectRequest;
use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\ManualExpensePaymentRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\ExpenseRequestTypeResource;
use App\Jobs\ExpenseDispatcherPaymentJob;
use App\Models\Attachment;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ExpenseRequestController extends Controller
{
    public function create(ExpenseRequest $expenseRequest): JsonResponse
    {
        $expense=null;
        DB::transaction(function () use ($expenseRequest) {
            $expense = Expense::query()->create($expenseRequest->validated());

            $attachmentData = [];
            foreach ($expenseRequest->file('attachments') as $file){
                $uuid = Str::uuid();
                $filePath = $file->storeAs('attachments', $uuid . '.' . $file->extension());
                $attachmentData[] = [
                    'uuid' => $uuid,
                    'path' => $filePath,
                    'extension' => $file->extension(),
                    'expense_request_id' => $expense->id,
                    'user_id' => Auth::id()
                ];
            }

            Attachment::query()->insert($attachmentData);
        });

        return (new ExpenseResource($expense))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    public function list(){
        return Expense::collection(ExpenseRequest::byRole()->get());
    }
    public function types(): AnonymousResourceCollection
    {
        return ExpenseRequestTypeResource::collection(ExpenseRequestTypeResource::all());
    }
    public function confirm(ExpenseConfirmRequest $expenseConfirmRequest){
        Expense::query()->whereIn('id',$expenseConfirmRequest->only('expense_request_ids'));

        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
    public function reject(ExpenseRejectRequest $expenseRejectRequest){
        $validatedExpenseRequests=$expenseRejectRequest->only('expense_requests');
        foreach ($validatedExpenseRequests as $validatedExpenseRequest){
            Expense::query()->where('id',$validatedExpenseRequest['id'])->update(
                [
                    'is_confirmed' => false,
                    'reject_reason' => $validatedExpenseRequest['reject_reason'] ?? null
                ]
            );

            $expense=Expense::query()->where('id',$validatedExpenseRequest['id'])->first();

            event(new ExpenseRejected($expense));
        }

        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
    public function payNow(ManualExpensePaymentRequest $manualExpensePaymentRequest): JsonResponse
    {
        ExpenseDispatcherPaymentJob::dispatch($manualExpensePaymentRequest->only('expense_request_ids'));

        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }

}
