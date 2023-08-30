<?php include("../default.php"); ?>

<?php
	session_start();
	if( !isset($_SESSION['user']) ){
		header("Location:../error.php");
		exit();
	}
	$user = $_SESSION['user'];
	unset($_SESSION['user']);

	//趣味を保存用に文字列化
	$hobby = implode("-", $user['hobby']);

	if( empty($user['id']) ){
		//新規登録
		$sql  = <<<EOF
			INSERT INTO users
			(
				name
				,kana
				,birthday
				,zip
				,address
				,tel
				,pref_id
				,hobby_id
			) VALUES (
				'{$user['name']}'
				,'{$user['kana']}'
				,'{$user['birthday']}'
				,'{$user['zip1']}-{$user['zip2']}'
				,'{$user['address']}'
				,'{$user['tel1']}-{$user['tel2']}-{$user['tel3']}'
				,'{$user['pref']}'
				,'{$hobby}'
			)
		EOF;
	}else{
		//編集
		$datetime = date('Y-m-d H:i:s');
		$sql  = <<<EOF
			UPDATE users SET
			(
				name
				,kana
				,birthday
				,zip
				,address
				,tel
				,pref_id
				,hobby_id
				,modified
			) = (
				'{$user['name']}'
				,'{$user['kana']}'
				,'{$user['birthday']}'
				,'{$user['zip1']}-{$user['zip2']}'
				,'{$user['address']}'
				,'{$user['tel1']}-{$user['tel2']}-{$user['tel3']}'
				,'{$user['pref']}'
				,'{$hobby}'
				,'{$datetime}'
			)
			WHERE id = {$user['id']}
		EOF;
	}

	$result_flag = pg_query($sql);
	if( !$result_flag ){
		die('クエリーが失敗しました。'.pg_last_error());
	}
?>

<!DOCTYPE html>
<head>
	<title>ユーザー情報保存完了</title>
</head>
<body>
<?php include("../header.php"); ?>

<div class="card">
	<h5 class="card-header">ユーザー情報保存完了</h5>
	<div class="card-body">
		<p class="text-danger">ユーザー情報の保存が完了しました。</p>
		<button class="btn btn-secondary submit" onclick="location.href='<?= ROOT ?>/user/index.php'">TOPに戻る</button>
	</div>
</div>

<?php include("../footer.php"); ?>
