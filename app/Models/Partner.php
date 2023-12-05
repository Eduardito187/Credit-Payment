<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\AccountPartner;

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

    public function accountsPartners()
    {
        return $this->hasMany(AccountPartner::class, 'id_partner', 'id');
    }

    public function accountMaster()
    {
        return $this->hasOne(Account::class, 'id', 'id_account');
    }
}
