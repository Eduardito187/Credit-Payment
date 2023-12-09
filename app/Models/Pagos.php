<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estados;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Prestamos;

class Pagos extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $fillable = ['num_pago', 'monto', 'mora', 'fecha_pago', 'fecha_pagado', 'id_estado', 'id_cobrador', 'id_customer', 'id_prestamo'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    public function getEstado()
    {
        return $this->hasOne(Estados::class, 'id', 'id_estado');
    }

    public function getCobrador()
    {
        return $this->hasOne(Account::class, 'id', 'id_cobrador');
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, 'id', 'id_customer');
    }

    public function getPrestamo()
    {
        return $this->hasOne(Prestamos::class, 'id', 'id_prestamo');
    }
}