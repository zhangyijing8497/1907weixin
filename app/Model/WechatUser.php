<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WechatUser extends Model
{
    protected $table = 'wechat_user';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $guarded = [];//黑名单
}
