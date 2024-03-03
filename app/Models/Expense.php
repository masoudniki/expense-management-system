<?php

namespace App\Models;

use App\Models\Enums\RoleEnums;
use App\Models\interfaces\RoleInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Mail\Attachment;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    use HasFactory,HasUuids;
    protected $table = 'expense_requests';

    protected $fillable = [
        'expense_request_type_id',
        'description',
        'amount',
        'iban',
        'national_code',
        'reject_reason',
        'is_confirmed',
        'is_paid'
    ];

    public function uniqueIds()
    {
        return ['uuid'];

    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expenseRequestType(): BelongsTo
    {
        return $this->belongsTo(ExpenseRequestType::class,'expense_request_type_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function scopeByRole($query,RoleInterface $roleAbleEntity){
        if($roleAbleEntity->role() == RoleEnums::EMPLOYER){
            $query->where("user_id",Auth::id());
        }
        return $query;
    }

}
