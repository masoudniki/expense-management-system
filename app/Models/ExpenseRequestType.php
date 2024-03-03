<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseRequestType extends Model
{
    use HasFactory;

    protected $table = 'expense_request_types';
    protected $fillable = ['name'];

    public function expenseRequests(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

}
