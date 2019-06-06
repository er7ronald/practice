
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

		$kind['mid']			 = array( 'name' => '求人一覧','url' => 'index.php?app_controller=search&type=mid&run=true');
		$kind['interview']	 = array( 'name' => 'インタビュー一覧',	'url' => 'index.php?app_controller=search&type=interview&run=true');
        $kind['user']	 = array( 'name' => '企業検索',	'url' => 'index.php?app_controller=search&type=cUser&run=true');
		$kind['fresh']			 = array( 'name' => '求人一覧','url' => 'index.php?app_controller=search&type=fresh&run=true');
		$kind['info']	 = array( 'name' => 'お知らせ',	'url' => 'index.php?app_controller=search&type=news&run=true&category=news&category_PAL[]=match+comp');
        $kind['question']	 = array( 'name' => 'よくある質問',	'url' => 'index.php?app_controller=page&p=qanda' );
        $kind['login']			 = array( 'name' => 'ログインページ','url' => 'login.php');
        $kind['adds']			 = array( 'name' => '会員登録',	'url' => 'index.php?app_controller=other&key=CheckValidityForm&type=nUser');
        $kind['attention']		 = array( 'name' => 'おすすめ求人',	'url' => 'index.php?app_controller=search&run=true&type=mid&sort=regist&sort_PAL[]=desc&attention[]=1&attention_CHECKBOX=&attention_PAL[]=match+or');
        
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
        ＄n;
		$buffer  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$buffer .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		foreach( $kind as $name => $data ) {
			if( strlen($data['url']) > 0 )
			{

				$buffer .= '  <url>'."\n";
				$buffer .= '   <loc>'.$HOME.h($data['url']).'</loc>'."\n";
                $buffer .= '  </url>'."\n";
                n++;
			}
            foreach( $list[$name] as $data ) 
            {

				$buffer .= '  <url>'."\n";
				$buffer .= '   <loc>'.$HOME.h($data['url']).'</loc>'."\n";
				$buffer .= '  </url>'."\n";
                n++;
                
            }
            if(n>45){
                $buffer .= '</urlset>'."\n";
            
                    SystemUtil::fileWrite( $file , $buffer );
            }
		}
		$buffer .= '</urlset>'."\n";

		SystemUtil::fileWrite( $file , $buffer );
    }


}
    
    
    // function fileWrite( $file_name , $html ){
	// 	if(!$f = fopen($file_name,'w')){
	// 		return;
	// 	}

	// 	if(fwrite($f,$html) === FALSE ){
	// 		fclose($f);
	// 		return;
	// 	}

	// 	fclose($f);
	// 	chmod( $file_name, 0766 );
    // }
    
