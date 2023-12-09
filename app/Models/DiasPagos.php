<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pagos;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Prestamos;

class DiasPagos extends Model
{
    use HasFactory;

    protected $table = 'dia_pago';
    protected $fillable = ['id_pago', 'id_cobrador', 'id_customer', 'id_prestamo', 'pagado', 'mora'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    public function getPago()
    {
        return $this->hasOne(Pagos::class, 'id', 'id_pago');
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