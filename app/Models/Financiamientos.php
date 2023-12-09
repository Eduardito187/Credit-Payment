<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financiamientos extends Model
{
    use HasFactory;

    protected $table = 'financiamientos';
    protected $fillable = ['name', 'code', 'value', 'status'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;
}
