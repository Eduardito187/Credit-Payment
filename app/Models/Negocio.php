<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CargoNegocio;
use App\Models\RubroNegocio;
use App\Models\TipoNegocio;
use App\Models\Address;

class Negocio extends Model
{
    use HasFactory;

    protected $table = 'negocios';
    protected $fillable = ['name', 'id_cargo_negocio', 'id_rubro_negocio', 'id_tipo_negocio', 'id_address'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    public function getCargoNegocio()
    {
        return $this->hasOne(CargoNegocio::class, 'id', 'id_cargo_negocio');
    }
    
    public function getRubroNegocio()
    {
        return $this->hasOne(RubroNegocio::class, 'id', 'id_rubro_negocio');
    }

    public function getTipoNegocio()
    {
        return $this->hasOne(TipoNegocio::class, 'id', 'id_tipo_negocio');
    }
    
    public function getAddress()
    {
        return $this->hasOne(Address::class, 'id', 'id_address');
    }
}
