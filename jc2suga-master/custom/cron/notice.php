<?php
cron_master::setCron('attentionNoticeMid','noticeCron','attentionMid');
cron_master::setCron('attentionNoticeFresh','noticeCron','attentionFresh');
cron_master::setCron('userLimitNoticeMid','noticeCron','userLimitMid');
cron_master::setCron('userLimitNoticeFresh','noticeCron','userLimitFresh');

class noticeCron{

	function userLimitMid(){
		self::userLimitCommon("mid");
	}

	function userLimitFresh(){
		self::userLimitCommon("fresh");
	}

	function userLimitCommon($type){
		if(Conf::checkData("charges", "user_limit", "off"))
			return;

		$conf = Conf::getData("charges", "user_limit_ad_notice");
		if(empty($conf)) return;
		$dayList = explode("/",$conf);

		$db = GMList::getDB("pay_job");
		$table = pay_jobLogic::getLsatTerm($type);

		$time	 = time();
		$y		 = date( 'Y', $time );
		$m		 = date( 'm', $time );
		$d		 = date( 'd', $time );

		if( strlen($dayList[0]) == 0 ) {
			return;
		}

		foreach( $dayList as $day )
		{
			$limitTable = DateUtil::setSearchDay( $db, $table, $y, $m, $d+$day, 'limits' );
			if($db->existsRow($limitTable))
				MailLogic::userLimitNotice( $type,$limitTable, $day );
		}
	}

	function attentionMid(){
		self::attentionCommon("mid");
	}

	function attentionFresh(){
		self::attentionCommon("fresh");
	}

	function attentionCommon($type){
		$use = Conf::getData("charges", "attention");
		if($use == "off") return;

		$conf = Conf::getData("charges", "attention_ad_notice");
		if(empty($conf)) return;
		$dayList = explode("/",$conf);

		$db = GMList::getDB($type);
		$table = JobLogic::getTable($type);

		$time	 = time();
		$y		 = date( 'Y', $time );
		$m		 = date( 'm', $time );
		$d		 = date( 'd', $time );

		if( strlen($dayList[0]) == 0 ) {
			return;
		}

		foreach( $dayList as $day )
		{
			$limitTable = DateUtil::setSearchDay( $db, $table, $y, $m, $d+$day, 'attention_time' );
			if($db->existsRow($limitTable)){
				MailLogic::attentionAdNotice( $type,$limitTable, $day );
			}
		}

	}
}