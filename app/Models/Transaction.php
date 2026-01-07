<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaction';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_transaction',
        'client_name',
        'client_phone_number',
        'amount',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'id_transaction', 'id_transaction');
    }
}
