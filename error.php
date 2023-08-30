<?php include("default.php"); ?>

<!DOCTYPE html>
<head>
	<title>エラー</title>
</head>
<body>
<?php include("header.php"); ?>

<div class="card">
	<h5 class="card-header">エラー</h5>
	<div class="card-body">
		<p class="text-danger">処理に失敗しました。</p>
		<p class="text-danger">再度処理を行ってください。</p>
		<button class="btn btn-secondary submit" onclick="location.href='<?= ROOT ?>/user/index.php'">TOPに戻る</button>
	</div>
</div>

<?php include("footer.php"); ?>
