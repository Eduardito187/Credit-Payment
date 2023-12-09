<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Partner;

class CustomerPartner extends Model
{
    use HasFactory;

    protected $table = 'customer_partner';
    protected $fillable = ['id_customer', 'id_partner'];
    public $timestamps = false;

    public function getCustomer() {
        return $this->hasOne(Customer::class, 'id', 'id_customer');
    }

    public function getPartner() {
        return $this->hasOne(Partner::class, 'id', 'id_partner');
    }
}