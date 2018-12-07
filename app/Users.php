<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:30
 */

namespace App;

use Illuminate\Database\Eloquent\Model;


class Users extends Model
{
    protected $table = "users";
    protected $primaryKey = "user_id";
    public $timestamps = true;
    protected $fillable=["username","password",'api_token'];
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at','is_admin','company_id','department_id','status','last_login','access_time'
    ];

    public function getDateFormat()
    {
        return time();
    }

//    protected function asDateTime($val)
//    {
//        return date($val, "Y-m-d H:i:s");
//    }

}