<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estados;
use App\Models\Customer;
use App\Models\PlanCuotas;

class Prestamos extends Model
{
    use HasFactory;

    protected $table = 'prestamos';
    protected $fillable = ['monto_base', 'monto_interes', 'monto_total', 'monto_pago', 'mora', 'fecha_prestamo', 'fecha_finalizacion', 'id_estado', 'id_customer', 'id_plan_cuota'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    public function getEstado()
    {
        return $this->hasOne(Estados::class, 'id', 'id_estado');
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, 'id', 'id_customer');
    }

    public function getPlanCuota()
    {
        return $this->hasOne(PlanCuotas::class, 'id', 'id_plan_cuota');
    }
}