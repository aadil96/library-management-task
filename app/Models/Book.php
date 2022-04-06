<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'name', 'author', 'cover_image',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function rentedBy() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'transactions');
    }
}
