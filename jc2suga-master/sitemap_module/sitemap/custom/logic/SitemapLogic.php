<?php

class SitemapLogic
{
	static $xml  = 'sitemap.xml';
	static $html = 'sitemap.html';
	static $STATIC_URL_FLG = false;

	/**
	 * テーブルデータをcsvフォーマットで出力
	 * 
	 * @param table csvフォーマットで出力するテーブル
	 * @return csvフォーマットデータ
	 */
	function create()
	{
		self::$STATIC_URL_FLG = isset($GLOBALS['STATIC_URL_FLG']) ? $GLOBALS['STATIC_URL_FLG'] : false;

		$kind['mid']			 = array( 'name' => '求人一覧',			'url' => 'index.php?app_controller=search&type=mid&run=true' );
		$kind['user']			 = array( 'name' => '求人企業一覧',		'url' => '' );
		$kind['adds']			 = array( 'name' => '勤務地から探す',	'url' => '' );
		$kind['items_form']		 = array( 'name' => '勤務形態から探す',	'url' => '' );
		$kind['items_type']		 = array( 'name' => '職種から探す',	'url' => '' );
		$kind['job_addition']	 = array( 'name' => '特徴から探す',	'url' => '' );
		$kind['interview']		 = array( 'name' => '花業界に携わる企業・店舗インタビュー',	'url' => '' );
		$kind['fresh']			 = array( 'name' => '求人一覧',			'url' => 'index.php?app_controller=search&type=fresh&run=true' );
		$kind['f_adds']			 = array( 'name' => '勤務地から探す',	'url' => '' );
		$kind['f_items_type']	 = array( 'name' => '職種から探す',	'url' => '' );
		$kind['f_job_addition']	 = array( 'name' => '特徴から探す',	'url' => '' );

		$list['mid']			 = self::getMidList();
		$list['user']			 = self::getUserList();
		$list['adds']			 = self::getAddsList();
		$list['items_form']		 = self::getItemsFormList();
		$list['items_type']		 = self::getItemsTypeList();
		$list['job_addition']	 = self::getJobAdditionList();
		$list['interview']		 = self::getInterviewList();

		$list['fresh']			 = self::getFreshList();
		$list['f_adds']			 = self::getAddsList("fresh");
		$list['f_items_type']	 = self::getItemsTypeList("fresh");
		$list['f_job_addition']	 = self::getJobAdditionList("fresh");
		self::createXml( self::$xml, $kind, $list );
		//self::createHtml( self::getHtmlFile(), $kind, $list );		
	}


	/**
	 * xmlファイルを作成
	 * 
	 * @param file ファイル名
	 * @return list データ配列
	 */
	function createXml( $file, $kind, $list )
	{
		// ** conf.php で定義した定数の中で、利用したい定数をココに列挙する。 *******************
		global $HOME;
		// **************************************************************************************

		if( file_exists($file) )
		{
			$time = filemtime($file); 
			if( $time > time()-300 ) { return; } // 5分以内に再作成された場合はスキップ
		}

		$buffer  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$buffer .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		foreach( $kind as $name => $data ) {
			if( strlen($data['url']) > 0 )
			{
				$buffer .= '  <url>'."\n";
				$buffer .= '   <loc>'.$HOME.h($data['url']).'</loc>'."\n";
				$buffer .= '  </url>'."\n";
			}
			foreach( $list[$name] as $data ) {
				$buffer .= '  <url>'."\n";
				$buffer .= '   <loc>'.$HOME.h($data['url']).'</loc>'."\n";
				$buffer .= '  </url>'."\n";
	
			}
		}
		$buffer .= '</urlset>'."\n";

		SystemUtil::fileWrite( $file , $buffer );
	}


	/**
	 * htmlファイルを作成
	 * 
	 * @param file ファイル名
	 * @return list データ配列
	 */
	function createHtml( $file, $kind, $list )
	{
		// ** conf.php で定義した定数の中で、利用したい定数をココに列挙する。 *******************
		global $HOME;
		global $loginUserType;
		global $loginUserRank;
		// **************************************************************************************

		if( file_exists($file) )
		{
			$time = filemtime($file); 
			if( $time > time()-300 ) { return; } // 5分以内に再作成された場合はスキップ
		}		

		$gm = GMList::getGM('system');
		$design = Template::getTemplate( $loginUserType, $loginUserRank, 'sitemap', 'TEMPLATE_DESIGN' );

		$buffer .= $gm->getString( $design, null, 'head' );
		foreach( $kind as $name => $data )
		{
			$gm->setVariable('url', $data['url']);
			$gm->setVariable('name', $data['name']);
			$buffer .= $gm->getString( $design, null, 'kind' );
			foreach( $list[$name] as $data )
			{
				$gm->setVariable('url', $data['url']);
				$gm->setVariable('name', $data['name']);
				$buffer .= $gm->getString( $design, null, 'list' );
			}
		}
		$buffer .= $gm->getString( $design, null, 'foot' );

		SystemUtil::fileWrite( $file , $buffer );
	}


	function getHtmlFile()
	{
		$directory	 = 'file/sitemap/';
		if(!is_dir($directory)) { mkdir( $directory, 0777, true ); chmod( $directory, 0777 ); } //ディレクトリが存在しない場合は作成

		return $directory.self::$html;		
	}


	/**
	* 求人情報一覧を配列で取得
	* 
	* @return URL用配列
	*/
	function getMidList()
	{
		$db =  GMList::getDB('mid');
		$table = JobLogic::getTable( "mid", null, null, "nobody" );
		$table = $db->sortTable( $table, 'edit', 'desc');
		$table = $db->limitOffset( $table , 0 , 40000 );
		
		$row = $db->getRow($table);
		$list = array();
		for( $i=0; $i<$row; $i++ )
		{
			$rec = $db->getRecord( $table, $i );
			$list[] = array( 'url' => "index.php?app_controller=info&type=mid&id=".$db->getData( $rec, 'id' ), 'name' => $db->getData( $rec, 'name' ) );
		}
 
		return $list;
	}

	/**
	* 求人情報一覧を配列で取得
	* 
	* @return URL用配列
	*/
	function getFreshList()
	{
		$db =  GMList::getDB('fresh');
		$table = JobLogic::getTable( "fresh", null, null, "nobody" );
		$table = $db->sortTable( $table, 'edit', 'desc');
		$table = $db->limitOffset( $table , 0 , 40000 );
		
		$row = $db->getRow($table);
		$list = array();
		for( $i=0; $i<$row; $i++ )
		{
			$rec = $db->getRecord( $table, $i );
			$list[] = array( 'url' => "index.php?app_controller=info&type=fresh&id=".$db->getData( $rec, 'id' ), 'name' => $db->getData( $rec, 'name' ) );
		}
 
		return $list;
	}


	/**
	* 求人企業を配列で取得
	* 
	* @return URL用配列
	*/
   function getUserList()
   {
	   $db =  GMList::getDB('cUser');
	   $table = cUserLogic::getTable(null,null,"nobody");
	   $table = $db->sortTable( $table, 'regist', 'desc');
	   $table = $db->limitOffset( $table , 0 , 1000 );
	   
	   $row = $db->getRow($table);
	   $list = array();
	   for( $i=0; $i<$row; $i++ )
	   {
		   $rec = $db->getRecord( $table, $i );
		   $list[] = array( 'url' => 'index.php?app_controller=info&type=cUser&id='.$db->getData( $rec, 'id' ), 'name' => $db->getData( $rec, 'name' ) );
	   }

	   return $list;
   }


   /**
	* 勤務形態から探すを配列で取得
	* 
	* @return URL用配列
	*/
	function getItemsFormList()
	{
		$db =  GMList::getDB('items_form');
		$table = $db->getTable();
		$table = $db->sortTable( $table, 'sort_rank', 'asc');
		
		$row = $db->getRow($table);
		$list = array();
		for( $i=0; $i<$row; $i++ )
		{
			$rec = $db->getRecord( $table, $i );
			if (self::$STATIC_URL_FLG) {
				$list[] = array('url' => SystemUtil::getStaticURL($db->getData($rec, 'id'), 'mid'), 'name' => $db->getData($rec, 'name'));
			} else {
				$list[] = array( 'url' => 'index.php?app_controller=search&type=mid&run=true&work_style='.$db->getData( $rec, 'id' ).'&work_style_PAL[]=match+or', 'name' => $db->getData( $rec, 'name' ) );
			}
		}
 
		return $list;
	}


	/**
	 * 勤務形態から探すを配列で取得
	 * 
	 * @return URL用配列
	 */
	function getItemsTypeList($type = "mid")
	{
		$db = GMList::getDB('items_type');
		$table = $db->getTable();
		$table = $db->sortTable($table, 'sort_rank', 'asc');

		$row = $db->getRow($table);
		$list = array();
		for ($i = 0; $i < $row; $i++) {
			$rec = $db->getRecord($table, $i);
			if (self::$STATIC_URL_FLG) {
				$list[] = array('url' => SystemUtil::getStaticURL($db->getData($rec, 'id'), $type), 'name' => $db->getData($rec, 'name'));
			} else {
				$list[] = array('url' => 'index.php?app_controller=search&type=' . $type . '&run=true&category=' . $db->getData($rec, 'id') . '&category_PAL[]=match+or', 'name' => $db->getData($rec, 'name'));
			}
		}

		return $list;
	}


	/**
	 * 勤務形態から探すを配列で取得
	 * 
	 * @return URL用配列
	 */
	function getJobAdditionList($type = "mid")
	{
		$db = GMList::getDB('job_addition');
		$table = $db->getTable();
		$table = $db->sortTable($table, 'sort_rank', 'asc');

		$row = $db->getRow($table);
		$list = array();
		for ($i = 0; $i < $row; $i++) {
			$rec = $db->getRecord($table, $i);
			if (self::$STATIC_URL_FLG) {
				$list[] = array('url' => SystemUtil::getStaticURL($db->getData($rec, 'id'), $type), 'name' => $db->getData($rec, 'name'));
			} else {
				$list[] = array('url' => 'index.php?app_controller=search&type=' . $type . '&run=true&addition=' . $db->getData($rec, 'id') . '&addition_PAL[]=match+or', 'name' => $db->getData($rec, 'name'));
			}
		}

		return $list;
	}


	/**
	 * 勤務地から探すを配列で取得
	 * 
	 * @return URL用配列
	 */
	function getAddsList($type = "mid")
	{
		$db = GMList::getDB('adds');
		$table = $db->getTable();
		$table = $db->sortTable($table, 'id', 'asc');

		$row = $db->getRow($table);
		$list = array();
		for ($i = 0; $i < $row; $i++) {
			$rec = $db->getRecord($table, $i);
			if (self::$STATIC_URL_FLG) {
				$list[] = array('url' => SystemUtil::getStaticURL($db->getData($rec, 'id'), $type), 'name' => $db->getData($rec, 'name'));
			} else {
				$list[] = array('url' => 'index.php?app_controller=search&type=' . $type . '&run=true&work_place_adds=' . $db->getData($rec, 'id') . '&work_place_adds_PAL[]=match+comp', 'name' => $db->getData($rec, 'name'));
			}
		}

		return $list;
	}


	/**
	 * インタビューを配列で取得
	 * 
	 * @return URL用配列
	 */
	function getInterviewList()
	{
		global $ACTIVE_ACCEPT;

		$db = GMList::getDB('interview');
		$table = $db->getTable();
		$table = $db->searchTable($table, 'activate', '=', $ACTIVE_ACCEPT);
		$table = $db->sortTable($table, 'regist', 'desc');

		$row = $db->getRow($table);
		$list = array();
		for ($i = 0; $i < $row; $i++) {
			$rec = $db->getRecord($table, $i);
			$list[] = array('url' => 'index.php?app_controller=info&type=interview&id=' . $db->getData($rec, 'id'), 'name' => $db->getData($rec, 'name'));
		}

		return $list;
	}
}

?>