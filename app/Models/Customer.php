<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use App\Models\CustomerNegocio;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $fillable = ['name', 'email', 'telefono', 'id_address', 'status'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;
    
    public function getAddress()
    {
        return $this->hasOne(Address::class, 'id', 'id_address');
    }
    
    public function getCustomerNegocio()
    {
        return $this->hasMany(CustomerNegocio::class, 'id_customer', 'id');
    }
}
