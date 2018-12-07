<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Btns extends Model
{
    protected $table = "btns";
    protected $primaryKey = "btns_id";
    public $timestamps = false;
    protected $fillable=[ 'btns_id'];
    protected $hidden = [

    ];

    public function getDateFormat()
    {
        return time();
    }



}