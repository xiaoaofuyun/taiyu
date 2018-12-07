<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TreeMenu extends Authenticatable
{
    protected $table = 'tree_menu';
    protected $primaryKey='tree_menu_id';
    public $timestamps=false;


    public function getCategory($sourceItems, $targetItems, $pid=0){
        foreach ($sourceItems as $k => $v) {
            if($v->pid == $pid){
                $targetItems[] = $v;
                $this->getCategory($sourceItems, $targetItems, $v->tree_menu_id);
            }
        }
    }




    public function getCategoryInfo(){

        $sourceItems = $this->get();
        //print_r($sourceItems);
        $targetItems = new Collection;
        $this->getCategory($sourceItems, $targetItems, 0);
        return $targetItems;
    }


}
