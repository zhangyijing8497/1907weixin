<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channel';
    protected $primaryKey = 'c_id';
    public $timestamps = false;
    protected $guarded = [];//黑名单
}
