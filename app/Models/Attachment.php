<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory,HasUuids;
    protected $table = 'attachments';
    protected $fillable = [
        'uuid',
        'path',
        'extension'
    ];
}