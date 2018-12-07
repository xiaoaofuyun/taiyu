<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DataType extends Model
{
    protected $table = "data_type";
    protected $primaryKey = "data_type_id";
    public $timestamps = false;
    protected $fillable=[];
    protected $hidden = [
        'data_type_id', 'type'
    ];

    public function getDateFormat()
    {
        return time();
    }



}