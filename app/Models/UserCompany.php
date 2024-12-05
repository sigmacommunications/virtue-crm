<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    use HasFactory;
    protected $guarded =  [];

    /**
     * Get the user associated with the UserCompany
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function companydetails()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
