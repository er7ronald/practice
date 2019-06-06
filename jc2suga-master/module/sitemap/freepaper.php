if(file_exists($directory)){
    foreach(glob("sitemap/*.xml") as $xfilename){
         unlink($xfilenam);
     }
     rmdir('sitemap');
}
function delete_directory.php(){
     
}

$protocol = empty($_SERVER["HTTPS"]) ? 'http://' : 'https://';
$host = $_SERVER['HTTP_HOST']; 
$protocol.$host.'/jc2suga-master/sitemap/'.$filename

function createIndexXml($filename){
		
		
		
		$buffer .= '</sitemapindex>'."\n";
		// $buffer .= '  </sitemap>'."\n";
		// $buffer .= '</sitemapindex>'."\n";
		
		$buffer = "";
        
    }


function indexexamination($filename){
    $protocol = empty($_SERVER["HTTPS"]) ? 'http://' : 'https://';
	$host = $_SERVER['HTTP_HOST']; 
    if(!file_exists(self::$path.SitemapLogic::$ixml)){
        $buffer  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$buffer .= '  <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		self::fileWrite( SitemapLogic::$ixml , $buffer );
        $buffer = "";
    }else{
        $buffer .= '  <sitemap>'."\n";
        $buffer .= '   <loc>'.$protocol.$host.'/jc2suga-master/sitemap/'.$filename.'</loc>'."\n";
        $buffer .= '  </sitemap>'."\n";
        $f=fopen(self::$path.SitemapLogic::$ixml,"a");
        fwrite($f,$buffer);
        fclose($f);
        $buffer = "";
    }
}

fileAddWrite($filename,$html){
    $f=fopen(self::$path.$filename,"a");
            fwrite($f,$html);
            fclose($f);
}

static $path = './sitemap/';
    function fileWrite( $file_name , $html ){
		if(!$f = fopen(self::$path.$file_name,'w')){
			return;
		}

		if(fwrite($f,$html) === FALSE ){
			fclose($f);
			return;
		}

		fclose($f);
		chmod( self::$path.$file_name, 0766 );
	}