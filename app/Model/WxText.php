<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxText extends Model
{
    protected $table = 'wx_text';
    protected $primaryKey = 't_id';
    public $timestamps = false;
    protected $guarded = [];//黑名单
}
