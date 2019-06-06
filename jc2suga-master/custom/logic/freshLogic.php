<?php
class freshLogic{

    static function addLimits($items_id,$addSec){
        $db = GMList::getDB(self::getType());
        $rec = $db->selectRecord($items_id);
        $attention = (int)$db->getData($rec,"attention_time");

        $db->setData($rec, "attention", true);
        if($attention > time()){
            $db->setData($rec, "attention_time", $attention+$addSec);
        }else{
            $db->setData($rec, "attention_time", time()+$addSec);
        }
        $db->updateRecord($rec);
    }

    private function getType(){
        return "fresh";
    }
}