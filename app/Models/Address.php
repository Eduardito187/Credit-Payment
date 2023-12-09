<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Municipality;
use App\Models\Country;
use App\Models\City;
use App\Models\AddressExtra;
use App\Models\Localization;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';
    protected $fillable = ['id_municipality', 'id_country', 'id_city', 'id_address_extra', 'id_localization'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    public function getMunicipality()
    {
        return $this->hasOne(Municipality::class, 'id', 'id_municipality');
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, 'id', 'id_country');
    }
    public function getCity()
    {
        return $this->hasOne(City::class, 'id', 'id_city');
    }

    public function getAddressExtra()
    {
        return $this->hasOne(AddressExtra::class, 'id', 'id_address_extra');
    }

    public function getLocalization()
    {
        return $this->hasOne(Localization::class, 'id', 'id_localization');
    }
}
