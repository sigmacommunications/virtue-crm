<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'id');
    }
    public function company()
    {
        return $this->hasMany(Company::class, 'company_id', 'id');
    }
    /**
     * Get the user associated with the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    public function companyLogo()
    {
        return $this->belongsTo(Company::class, 'company', 'id');
    }
    public function payment_method_detail()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'id');
    }
}
