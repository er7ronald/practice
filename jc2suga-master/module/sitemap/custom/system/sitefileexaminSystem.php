<?php
class fileexamin {
	static $path = './sitemap/';
	// ファイル生成、
    function fileWrite( $file_name , $html ){
		if(!$f = fopen($file_name,'w')){
			return;
		}

		if(fwrite($f,$html) === FALSE ){
			fclose($f);
			return;
		}

		fclose($f);
		chmod( $file_name, 0766 );
	}
	// ファイル追記
	function fileAddWrite($filename,$html){
		$f=fopen($filename,"a");
		fwrite($f,$html);
		fclose($f);
	}
	// ファイルサイズurl数取得し上限に達した場合、ファイル名をリネームして新たなファイルを生成する
	function file_Size($filename,$html){
        $count = count(file($filename));
			$count = $count / 3;
            $f= filesize($filename);
            if(($f>5000000 || $count >4000)&&(preg_match("/Index/",$filename))){
				self::fileAddWrite($filename,'</sitemapindex>'."\n");
				$filename = "_".SitemapLogic::$g.$filename;
				SitemapLogic::$g++;
                self::fileWrite( $filename, $html );
			}
			else if(($f>5000000 || $count >4000)&&(!preg_match("/Index/",$filename))){
				self::fileAddWrite($filename,'</urlset>'."\n");
				$filename = "_".SitemapLogic::$g.$filename;
				SitemapLogic::$g++;
                self::fileWrite( $filename, $html );
	}else{
		self::fileAddWrite($filename,$html);
	}
}
	function fileExamine($filename,$html){
        if(file_exists($filename)){
			self::file_Size($filename,$html);
            }else{
            self::fileWrite($filename , $html );
        }
	}
	
	function createIndexXml($filename){
		$protocol = empty($_SERVER["HTTPS"]) ? 'http://' : 'https://';
		$host = $_SERVER['HTTP_HOST']; 
		if(!file_exists(self::$path.SitemapLogic::$ixml)){
			$buffer  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
			$buffer .= '  <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
			self::fileWrite(self::$path.SitemapLogic::$ixml , $buffer );
			// $buffer = "";
		}else{
			$buffer .= '  <sitemap>'."\n";
			$buffer .= '   <loc>'.$protocol.$host.dirname(realpath("./sitemap.xml")).'/jc2suga-master/sitemap/'.$filename.'</loc>'."\n";
			$buffer .= '  </sitemap>'."\n";
			self::fileExamine(self::$path.SitemapLogic::$ixml,$buffer);
			// $buffer = "";
		}
    }
}
        ?>
