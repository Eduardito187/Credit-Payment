<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Financiamientos;
use App\Models\Intereses;
use App\Models\Plazos;

class PlanCuotas extends Model
{
    use HasFactory;

    protected $table = 'plan_cuota';
    protected $fillable = ['monto_base', 'monto_interes', 'monto_total', 'id_financiamiento', 'id_interes', 'id_plazos'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;
    
    public function getFinanciamiento()
    {
        return $this->hasOne(Financiamientos::class, 'id', 'id_financiamiento');
    }

    public function getInteres()
    {
        return $this->hasOne(Intereses::class, 'id', 'id_interes');
    }

    public function getPlazo()
    {
        return $this->hasOne(Plazos::class, 'id', 'id_plazos');
    }
}