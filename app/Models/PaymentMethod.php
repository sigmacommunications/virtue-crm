<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function MethodCompanies()
    {
        return $this->hasMany(PaymentMethodCompany::class, 'payment_method_id', 'id');
    }
}
