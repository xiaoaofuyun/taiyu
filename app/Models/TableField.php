<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TableField extends Model
{
    protected $table = "table_field";
    protected $primaryKey = "table_field_id";
    public $timestamps = true;
    protected $fillable=["menu_id","table_name","show_name","created_at","updated_at"];
    protected $hidden = ["created_at","updated_at"
       // "menu_id","table_name","show_name","created_at","updated_at"
    ];


    public function getDateFormat()
    {
        return time();
    }
}