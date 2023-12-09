<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Negocio;

class CustomerNegocio extends Model
{
    use HasFactory;

    protected $table = 'customer_negocio';
    protected $fillable = ['id_customer', 'id_negocios'];
    public $timestamps = false;

    public function getCustomer() {
        return $this->hasOne(Customer::class, 'id', 'id_customer');
    }

    public function getNegocio() {
        return $this->hasOne(Negocio::class, 'id', 'id_negocios');
    }
}