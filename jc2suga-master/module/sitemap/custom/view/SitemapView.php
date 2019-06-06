<?php

class SitemapView extends command_base
{

	/*
	 * サイトマップを出力
	*/
	function drawSitemap( &$gm, $rec, $args )
	{
		$design	 = SitemapLogic::getHtmlFile();
		if( !file_exists($design) ) { SitemapLogic::create(); }

		$buffer = $gm->getString( $design, null );
		$this->addBuffer( $buffer );
		
	}

}

?>