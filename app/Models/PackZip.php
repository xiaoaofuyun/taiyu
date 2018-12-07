<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackZip extends Model
{
    protected $table = "pack_zip";
    protected $primaryKey = "pack_zip_id";
    public $timestamps = true;
    protected $fillable=[];
    protected $hidden = [
        "created_at","updated_at"
    ];

    public function getDateFormat()
    {
        return time();
    }
}
