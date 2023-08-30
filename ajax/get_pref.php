<?php
	header("Content-Type: application/json; charset=UTF-8");

	$conn = "host=13.208.77.197 dbname=user_info user=postgres password=postgres";
	$link = pg_connect($conn);
	if (!$link) {
	    die('接続失敗です。'.pg_last_error());
	}

	//地域IDを元に都道府県マスタ取得
	$prefs = pg_query('SELECT id, name FROM prefs WHERE region_id = '.$_POST['region_id'].' AND deleted IS NULL ORDER BY id');
	if( !$prefs ){
		die(pg_last_error());
	}

	$ret = [];
	for( $i = 0 ; $i < pg_num_rows($prefs) ; $i++ ){
		$pref = pg_fetch_array($prefs, NULL, PGSQL_ASSOC);
		$ret[$pref['id']] = $pref['name'];
	}

	echo json_encode($ret);

	pg_close($link);
?>