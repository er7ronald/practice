<?php
class EntryView extends command_base{

	//指定ユーザーが指定企業へのエントリーが一件以上あるかどうか
	function haveUserEntry(&$gm, $rec, $args){
		List($cID,$userID) = $args;
		if(!is_null(Entry::getApplyItemsID($cID,$userID)))
			$this->addBuffer("TRUE");
		else
			$this->addBuffer("FALSE");
	}


	function drawWaitApplicantCnt( &$gm, $rec, $args ){
		List($itemsID) = $args;
		$row = Entry::getWaitApplicant($itemsID);
		$this->addBuffer($row);
	}

	function hasEntry( &$gm, $rec, $args ){
		List($itemsID) = $args;
		$result = entryLogic::existsApply($itemsID) ? "TRUE":"FALSE";
		$this->addBuffer($result);
	}

	function drawIsApply( &$gm, $rec, $args ){
		List($userID,$itemsID) = $args;
		$result = entryLogic::isApply($userID,$itemsID) ? "TRUE" : "FALSE";
		$this->addBuffer($result);
	}

	function drawOtherEntryJobList( &$gm, $rec, $args ){
		global $loginUserType;
		global $loginUserRank;

		List($itemsID,$type) = $args;

		$gm = GMList::getGM($this->getType());
		$db = $gm->getDB();
		$table=$db->getTable();
		$table=$db->searchTable($table,"items_id","=",$itemsID);
		$row=$db->getRow($table);

		if($row>0){

			for($i=0;$i<$row;$i++){
				$rec=$db->getRecord($table,$i);
				$entry_user[]=$db->getData($rec,"entry_user");
				$items_type=$db->getData($rec,"items_type");
			}

			$total=0;
			$list_id = array();
			foreach($entry_user as $val){
				$table=$db->getTable();
				$table=$db->searchTable($table,"entry_user","=",$val);
				$table=$db->searchTable($table,"items_id","!",$itemsID);
				$row=$db->getRow($table);
				$total+=$row;

				$rec_job=$db->getRecord($table,0);
				if($db->getData($rec_job,"items_id")){
					$list_id[$db->getData($rec_job,"items_id")]=$db->getData($rec_job,"items_type");
				}
				if(count($list_id)>6) break;
			}

		}
		$template = Template::getTemplate($loginUserType, $loginUserRank, $type, "OTHER_ENTRY_LIST");
		$gm_job = GMList::getGM($type);
		$db_job = $gm_job->getDB();
		$buffer = $gm_job->getString($template,null,"head");

		if($list_id){

			foreach($list_id as $job_id=>$db_name){
				$gm_job->setVariable('items_type',$db_name);
				$db_tmp = GMList::getDB($db_name);
				$rec_job=$db_tmp->selectRecord($job_id);
				$buffer .= $gm_job->getString($template,$rec_job,"list");
				unset($rec_job);
			}
			$buffer .= $gm_job->getString($template,null,"foot");

		}else{
			$buffer = $gm_job->getString($template,$rec_job,"failed");
		}

		$this->addBuffer($buffer);
	}

    /**
     * 求人に応募可能かどうかを出力する
     *
     * @param job_id 求人ID
     * @return TRUE(応募可能)/LIMIT_OVER(定員オーバー)/NOT_PUBLISHED(閲覧権限なし)/
     *         APPLIED(応募済み)/NOT_GRANT_REGIST_PAGE_DESIGN(応募権限なし)
     */
    function canEntry(&$gm, $rec, $args)
    {
        List ($job_id) = $args;
        $result = entryLogic::checkApply(SystemUtil::getJobType($job_id), $job_id);
        
        if ($result === true) {
            $result = "TRUE";
        }
        
        $this->addBuffer($result);
    }

	function getType(){
		return "entry";
	}
}