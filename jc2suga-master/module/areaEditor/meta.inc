<?php

	$moduleName = 'areaEditor';

	$MODULES[ $moduleName ] = Array(
		'version' => '1.0', ///<このモジュールのバージョン情報。
		'summary' => '都道府県編集。'  ///<このモジュールの説明。
	);

	$ADD_LST[ "adds" ][ 'adds' ]    			 = '[areaEditor]/add/adds.csv';
	$ADD_LST[ "add_sub" ][ 'add_sub' ]   			 = '[areaEditor]/add/add_sub.csv';

	$js_file_paths['admin']['areaEditor']			= './module/areaEditor/common/js/areaEditor.js';

	include "./module/areaEditor/areaEditor.php";
	include "./module/areaEditor/custom/api/areaApi.php";