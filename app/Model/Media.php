<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $primaryKey = 'media_id';
    public $timestamps = false;
    protected $guarded = [];//黑名单
}
