<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Picture;
use App\Models\Address;
use App\Models\AccountPartner;
use App\Models\StorePartner;
use App\Models\Campaign;
use App\Models\SocialPartner;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partner';

    protected $fillable = ['alias', 'code', 'name', 'email', 'telefono', 'token', 'status', 'id_account', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'id_account', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    public function AccountPartner()
    {
        return $this->hasOne(AccountPartner::class, 'id_partner', 'id');
    }
}
