<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function bookLoans()
    {
        return $this->hasMany(BookLoan::class);
    }
    public function bookLoan() {
        return $this->belongsTo(BookLoan::class);
    }
}
