<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    protected $table = "role";
    protected $primaryKey = "role_id";
    public $timestamps = false;
    protected $fillable=[];
    protected $hidden = [

    ];
}