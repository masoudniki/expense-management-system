<?php

namespace App\Models;

use App\Models\Enums\RoleEnums;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Attachment extends Model
{
    use HasFactory,HasUuids;
    protected $table = 'attachments';
    protected $fillable = [
        'uuid',
        'path',
        'extension'
    ];
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function expense(){
        return $this->belongsTo(Expense::class,'expense_request_id');
    }

    public function resolveRouteBinding($value, $field = null){
        if (Auth::user()->role()==RoleEnums::EMPLOYER){
            return $this->where('user_id', Auth::id())->firstOrFail();
        }
        return parent::resolveRouteBinding($value, $field);
    }

}
