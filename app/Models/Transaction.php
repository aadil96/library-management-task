<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'book_id',
        'rented_on',
        'rent_due_on',
        'returned_on',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
