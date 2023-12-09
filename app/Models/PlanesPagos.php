<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pagos;
use App\Models\Prestamos;

class PlanesPagos extends Model
{
    use HasFactory;

    protected $table = 'planes_pago';
    protected $fillable = ['id_pago', 'id_prestamo'];
    public $timestamps = false;

    public function getPago()
    {
        return $this->hasOne(Pagos::class, 'id', 'id_pago');
    }

    public function getPrestamo()
    {
        return $this->hasOne(Prestamos::class, 'id', 'id_prestamo');
    }
}