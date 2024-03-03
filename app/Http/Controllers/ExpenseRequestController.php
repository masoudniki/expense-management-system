<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseConfirmRequest;
use App\Http\Requests\ExpenseRejectRequest;
use App\Http\Requests\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\ExpenseRequestType;
use App\Models\Attachment;
use App\Models\Expense;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ExpenseRequestController extends Controller
{
    public function create(ExpenseRequest $expenseRequest){

        $expense = Expense::query()->create($expenseRequest->validated());

        $attachmentData = [];
        foreach ($expenseRequest->file('attachments') as $file){
            $uuid = Str::uuid();
            $filePath = $file->storeAs('attachments', $uuid . '.' . $file->extension());
            $attachmentData[] = [
                'uuid' => $uuid,
                'path' => $filePath,
                'extension' => $file->extension(),
                'expense_id' => $expense->id,
            ];
        }

        Attachment::query()->insert($attachmentData);

        return new ExpenseResource($expense);
    }
    public function list(){
        return Expense::collection(ExpenseRequest::byRole()->get());
    }
    public function types(): AnonymousResourceCollection
    {
        return ExpenseRequestType::collection(ExpenseRequestType::all());
    }

    public function confirm(ExpenseConfirmRequest $expenseConfirmRequest){
        ExpenseRequest::query()->whereIn('id',$expenseConfirmRequest->only('expense_request_ids'));
        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
    public function reject(ExpenseRejectRequest $expenseRejectRequest){
        $validatedExpenseRequests=$expenseRejectRequest->only('expense_requests');
        foreach ($validatedExpenseRequests as $validatedExpenseRequest){
            ExpenseRequest::query()->where('id',$validatedExpenseRequest['id'])->update(
                [
                    'is_confirmed' => false,
                    'reject_reason' => $validatedExpenseRequest['reject_reason']
                ]
            );
        }
        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }

}
