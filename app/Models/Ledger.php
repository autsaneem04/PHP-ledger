<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Ledger extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'note',
        'image',
        'created_at',
        'updated_at',
    ];

    // Ledger เป็นของ User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
