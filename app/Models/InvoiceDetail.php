<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_details';
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product_detail()
    {
        return $this->belongsTo(Product::class, 'product', 'id');
    }
    public function tax_detail()
    {
        return $this->belongsTo(Tax::class, 'tax', 'id');
    }
}
