<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    public $primaryKey='uid';
    protected $guarded = [];
    public $table='user';
    public $timestamps=false;

}
