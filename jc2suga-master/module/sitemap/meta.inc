<?php

	//■モジュールパック定義

	$moduleName = 'sitemap';

	$MODULES[ $moduleName ] = Array(
		'version' => '1.0'                          , ///<このモジュールのバージョン情報。
		'summary' => 'サイトマップ生成' , ///<このモジュールの説明。
	);

	//■テーブル定義
	$EDIT_TYPE                            = 'sitemap';

	//■その他の処理
	include_once './custom/cron/core.inc'; 
	include_once './module/' . $moduleName . '/custom/logic/SitemapLogic.php';
	include_once './module/' . $moduleName . '/custom/view/SitemapView.php';
	include_once './module/' . $moduleName . '/custom/cron/sitefile.php';
	include_once './module/' . $moduleName . '/custom/system/sitefileexaminSystem.php';

