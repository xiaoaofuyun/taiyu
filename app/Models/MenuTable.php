<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MenuTable extends Model
{
    protected $table = "menu_table";
    protected $primaryKey = "menu_table_id";
//    const CREATED_AT = 'created';
//    const UPDATED_AT = 'updated_at';
    public $timestamps = true;
    protected $fillable=["menu_id","table_name","show_name","created_at","updated_at"];
    protected $hidden = [''
       // "menu_id","table_name","show_name","created_at","updated_at"
    ];


    public function getDateFormat()
    {
        return time();
    }
}