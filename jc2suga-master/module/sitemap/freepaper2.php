function fileExamine($filename,$html){
        if(file_exists(self::$path.$filename)){
			$count = count(file(self::$path.$filename));
			$count = $count / 3;
            $f= filesize(self::$path.$filename);
            if($f>5000000 & $count >4000){
                if($filename =! )
				self::fileAddWrite(self::$path.$filename,'</urlset>'."\n");
                // SitemapLogic::$xml = SitemapLogic::$g.SitemapLogic::$xml;
				$filename = "_".SitemapLogic::$g.$filename;
				SitemapLogic::$g++;
                self::fileWrite( self::$path.$filename, $html );
            }else{
            self::fileAddWrite(self::$path.$filename,$html);
            }   
        }else{
            self::fileWrite( self::$path.$filename , $html );
        }
	}

    if(preg_match("/Index/",self::$path.$filename)){
        self::fileAddWrite(self::$path.$filename,'</sitemapindex>'."\n");
    }
    
    if(!preg_match("/Index/",self::$path.$filename))
    self::fileAddWrite(self::$path.$filename,'</urlset>'."\n");

    function fileExamine($filename,$html){
        if(file_exists(self::$path.$filename)){
			self::file_Size($filename);
			// $count = count(file(self::$path.$filename));
			// $count = $count / 3;
            // $f= filesize(self::$path.$filename);
            // if($f>5000000 & $count >4000){
			// 	self::fileAddWrite(self::$path.$filename,'</urlset>'."\n");
            //     // SitemapLogic::$xml = SitemapLogic::$g.SitemapLogic::$xml;
			// 	$filename = "_".SitemapLogic::$g.$filename;
			// 	SitemapLogic::$g++;
            //     self::fileWrite( self::$path.$filename, $html );
            // }else{
            // self::fileAddWrite(self::$path.$filename,$html);
            }else{
            self::fileWrite( self::$path.$filename , $html );
        }
	}

    fileExamine::$path

    dirname(realpath("./sitemap.xml"))
    $buffer .= '   <loc>'.$protocol.$host.__FILE__.'/jc2suga-master/sitemap/'.$filename.'</loc>'."\n";