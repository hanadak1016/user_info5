<?php include("../default.php"); ?>

<!DOCTYPE html>
<head>
	<title>ユーザー情報確認</title>
</head>
<body>
<?php include("../header.php"); ?>

<?php
	$user = $_POST;
	if( empty($user) ){
		header("Location:../error.php");
		exit();
	}

	session_start();
	$_SESSION['user'] = $user;

	//都道府県マスタ取得
	$prefs = pg_query('SELECT id, name FROM prefs WHERE deleted IS NULL ORDER BY id');
	if( !$prefs ){
		die('クエリーが失敗しました。'.pg_last_error());
	}

	$pref_list = [];
	for( $i = 0 ; $i < pg_num_rows($prefs) ; $i++ ){
		$pref = pg_fetch_array($prefs, NULL, PGSQL_ASSOC);
		$pref_list[$pref['id']] = $pref['name'];
	}

	//趣味マスタ取得
	$hobbies = pg_query('SELECT id, name FROM hobbies WHERE deleted IS NULL ORDER BY id');
	if( !$hobbies ){
		die('クエリーが失敗しました。'.pg_last_error());
	}

	$hobby_list = [];
	for( $i = 0 ; $i < pg_num_rows($hobbies) ; $i++ ){
		$hobby = pg_fetch_array($hobbies, NULL, PGSQL_ASSOC);
		$hobby_list[$hobby['id']] = $hobby['name'];
	}

	$hobby_arr = [];
	foreach( $user['hobby'] as $value ){
		$hobby_arr[] = $hobby_list[$value];
	}
	$hobby_str = implode("、", $hobby_arr);
 ?>

<form method="post" action="complete.php">
	<div class="card">
		<h5 class="card-header">ユーザー情報確認</h5>
		<div class="card-body">
			<p class="text-danger">以下の内容で保存します。</p>
			<table class="table table-striped">
				<tr><th>名前</th><td><?= $user['name'] ?></td></tr>
				<tr><th>名前（ふりがな）</th><td><?= $user['kana'] ?></td></tr>
				<tr><th>生年月日</th><td><?= $user['birthday'] ?></td></tr>
				<tr><th>郵便番号</th><td><?= $user['zip1'] ?>-<?= $user['zip2'] ?></td></tr>
				<tr><th>住所</th><td><?= $user['address'] ?></td></tr>
				<tr><th>電話番号</th><td><?= $user['tel1'] ?>-<?= $user['tel2'] ?>-<?= $user['tel3'] ?></td></tr>
				<tr><th>出身地</th><td><?= $pref_list[$user['pref']] ?></td></tr>
				<tr><th>趣味</th><td><?= $hobby_str ?></td></tr>
			</table>
			<button type="button" class="btn btn-secondary back" onclick="history.back()">戻る</button>
			<input type="submit" class="btn btn-primary submit" value="保存">
		</div>
	</div>
</form>
<?php include("../footer.php"); ?>
