<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model

{

    use HasFactory;

    const EXTENDED_YES = 'YES';
    const EXTENDED_NOT = 'NOT';
    const STATUS_PENDING = 'PENDING';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_APPROVED = 'APPROVED';
    const PENALTY_STATUS_INACTIVE = 'INACTIVE';
    const PENALTY_STATUS_ACTIVE = 'ACTIVE';

    protected $fillable = [
        'user_id ',
        'book_id ',
        'loan_date ',
        'due_date ',
        'return_date',
        'extended',
        'extension_date ',
        'penalty_amount ',
        'penalty_status',
        'status',
    ];

    //helper methods to get enum values
    public function getExtendedAttribute($value){
        return match ($value){
            self::EXTENDED_YES => 'Yes',
            self::EXTENDED_NOT => 'No',
            default => 'No',
        };
    }

    public function  getStatusAttribute($value){
        return match ($value){
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_APPROVED => 'Approved',
            default => 'Approved',
        };
    }

    public function getPenaltyStatusAttribute($value)
    {
        return match($value) {
            self::PENALTY_STATUS_INACTIVE => 'Inactive',
            self::PENALTY_STATUS_ACTIVE => 'Active',
            default => 'Inactive',
        };
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function books() {
        return $this->hasMany(Book::class);
    }

}
