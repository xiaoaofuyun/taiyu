<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationLog extends Model
{

    protected $table = "operation_log";


    protected $primaryKey = "operation_log_id";
    public function getDateFormat()
    {
        return time();
    }
}
