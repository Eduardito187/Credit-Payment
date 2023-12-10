<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municipality';

    protected $fillable = ['name', 'id_city'];

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    public function getCity()
    {
        return $this->hasOne(City::class, 'id', 'id_city');
    }
}
