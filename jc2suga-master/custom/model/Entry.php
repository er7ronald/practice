<?php
class Entry{

	/*
	 * 求人の応募数を取得する
	 */
	static function getTotalEntry($itemsID,$userID = null){
		$db = GMList::getDB(self::getType());
		$table = $db->getTable();
		$table = $db->searchTable($table,"items_id","=",$itemsID);
		if(!is_null($userID))
			{ $table = $db->searchTable( $table , 'entry_user' , '=' , $userID ); }

		return $db->getRow($table);
	}

	//面接に至らず、不採用が決定していない応募数を取得
	static function getWaitApplicant($itemsID){
		$db = GMList::getDB(self::getType());
		$table = $db->getTable();
		$table = $db->searchTable($table,"items_id","=",$itemsID);
		$table = $db->searchTable($table,"status","not in",array("EP001","FAILE"));
		return $db->getRow($table);
	}

	static function getID($itemsID,$userID){
		$db = GMList::getDB(self::getType());
		$table = $db->getTable();
		$table = $db->searchTable($table,"items_id","=",$itemsID);
		$table = $db->searchTable( $table , 'entry_user' , '=' , $userID );
		if($db->existsRow($table)){
			$rec = $db->getFirstRecord($table);
			return $db->getData($rec,"id");
		}
		return false;
	}

	//ユーザーが指定企業への応募した案件IDを返す
	static function getApplyItemsID($cID,$userID){
		$db = GMList::getDB(self::getType());
		$table = $db->getTable();
		$table = $db->searchTable( $table , 'items_owner' , '=' , $cID );
		$table = $db->searchTable( $table , 'entry_user' , '=' , $userID );

		return $db->getDataList($table,"items_id");
	}

	function getType(){
		return "entry";
	}
}