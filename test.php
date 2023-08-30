test
<?= date("Y-m-d H:i:s") ?>
<?php
	$data = ["data"];
	print_r($data);
/*
	$link = pg_connect("host=13.208.77.197 dbname=dvdrental user=postgres password=postgres");
	if (!$link) {
	    print(pg_last_error());
	}
	pg_close($link);
	print_r('DB:'.$data);
*/

	try {
		// 各種パラメータを指定して接続
		$pdo_conn = new PDO( 'pgsql:host=13.208.77.197; dbname=user_info;', 'postgres', 'postgres' );	
		var_dump("接続に成功しました");

	} catch(PDOException $e) {
		var_dump($e->getMessage());
	}

	// データベースとの接続を切断
	$pdo_conn = null;
?>
