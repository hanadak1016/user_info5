<?php
	define("ROOT", (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST']);
?>

<link rel ="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<link rel ="stylesheet" href="<?= ROOT ?>/vendor/twbs/bootstrap/dist/css/bootstrap.css">
<link rel ="stylesheet" href="<?= ROOT ?>/common.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<script src="<?= ROOT ?>/js/get_age.js"></script>

<?php
	$conn = "host=13.208.77.197 dbname=user_info user=postgres password=postgres";
	$link = pg_connect($conn);
	if (!$link) {
	    die('【'.__FILE__.'('.__LINE__.')】'.'接続失敗です。'.pg_last_error());
	}
?>
