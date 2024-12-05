<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function company()
    {
        return $this->hasMany(Company::class, 'company_id', 'id');
    }

    public function ClientCompanies()
    {
        return $this->hasMany(UserCompany::class, 'user_id', 'id');
    }
}
