<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
    protected $table = "company";
    protected $primaryKey = "company_id";
    public $timestamps = true;
    protected $fillable=[];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getDateFormat()
    {
        return time();
    }



}