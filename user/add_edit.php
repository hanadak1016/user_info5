<?php include("../default.php"); ?>

<?php
	$id = (!empty($_GET['edit']) ? $_GET['edit'] : '');
	$title = (!empty($id) ? '編集' : '新規登録');

	if( !empty($id) ){
		$sql  = <<<EOF
			SELECT	users.*
					,prefs.region_id
					,prefs.name AS pref_name
			FROM	users
			JOIN	prefs
			ON		users.pref_id = prefs.id
			WHERE	users.id = {$id}
		EOF;
		$res = pg_query($sql);
		if( !$res ){
			die('クエリーが失敗しました。'.pg_last_error());
		}
		$user = pg_fetch_assoc( $res, 0 );
		$zip = explode('-', $user['zip']);
		$tel = explode('-', $user['tel']);

		//対象ユーザー地域の都道府県
		$user_prefs = pg_query("SELECT id, name FROM prefs WHERE region_id = {$user['region_id']} AND deleted IS NULL ORDER BY id");
		if( !$user_prefs ){
			die('クエリーが失敗しました。'.pg_last_error());
		}
		$user_pref_list = [];
		for( $i = 0 ; $i < pg_num_rows($user_prefs) ; $i++ ){
			$user_pref = pg_fetch_array($user_prefs, NULL, PGSQL_ASSOC);
			$user_pref_list[$user_pref['id']] = $user_pref['name'];
		}

		//対象ユーザーの趣味編集
		$user_hobby_id = explode('-', $user['hobby_id']);
	}

	//地域マスタ取得
	$regions = pg_query('SELECT id, name FROM regions WHERE deleted IS NULL ORDER BY id');
	if( !$regions ){
		die('クエリーが失敗しました。'.pg_last_error());
	}

	//趣味マスタ取得
	$hobbies = pg_query('SELECT id, name FROM hobbies WHERE deleted IS NULL ORDER BY id');
	if( !$hobbies ){
		die('クエリーが失敗しました。'.pg_last_error());
	}
?>

<style>
.zip, .tel{
	display: inline;
	width: 65px;
}
.region{
	display: inline;
	width: 200px;
}
.hobby_label{
	display: inline-block;
	width: 100px;
	margin-top: 5px;
}
#birthday{
	display: inline;
	width: 115px;
}
#age{
	margin-left: 10px;
}
</style>

<!DOCTYPE html>
<head>
	<title>ユーザー情報<?= $title ?></title>
</head>
<body>
<?php include("../header.php"); ?>

<form method="post" action="confirm.php">
	<div class="card">
		<h5 class="card-header">ユーザー情報<?= $title ?></h5>
		<div class="card-body">
			<input type="hidden" id="id" name="id" value="<?= $id ?>">
			<label for="name">名前<span class="text-danger">※</span></label>
			<input type="text" id="name" name="name" value="<?= !empty($user['name']) ? $user['name'] : '' ?>" class="form-control" required>
			<label for="kana">名前（ふりがな）<span class="text-danger">※</span></label>
			<input type="text" id="kana" name="kana" value="<?= !empty($user['kana']) ? $user['kana'] : '' ?>" class="form-control" required>
			<label for="birthday">生年月日<span class="text-danger">※</span></label>
			<input type="text" id="birthday" name="birthday" value="<?= !empty($user['birthday']) ? $user['birthday'] : '' ?>" class="form-control" required autocomplete="off">
			<span id="age"></span>
			<label for="zip1">郵便番号<span class="text-danger">※</span></label>
			<input type="number" id="zip1" name="zip1" value="<?= !empty($zip[0]) ? $zip[0] : '' ?>" class="form-control zip" required>
			<span>－</span>
			<input type="number" id="zip2" name="zip2" value="<?= !empty($zip[1]) ? $zip[1] : '' ?>" class="form-control zip" required>
			<label for="add">住所<span class="text-danger">※</span></label>
			<input type="text" id="address" name="address" value="<?= !empty($user['address']) ? $user['address'] : '' ?>" class="form-control" required>
			<label for="tel1">電話番号<span class="text-danger">※</span></label>
			<input type="number" id="tel1" name="tel1" value="<?= !empty($tel[0]) ? $tel[0] : '' ?>" class="form-control tel" required>
			<span>－</span>
			<input type="number" id="tel2" name="tel2" value="<?= !empty($tel[1]) ? $tel[1] : '' ?>" class="form-control tel" required>
			<span>－</span>
			<input type="number" id="tel3" name="tel3" value="<?= !empty($tel[2]) ? $tel[2] : '' ?>" class="form-control tel" required>
			<label for="region">出身地<span class="text-danger">※</span></label>
			<select id="region" name="region" class="form-control region" required>
				<option hidden></option>
				<?php for( $i = 0 ; $i < pg_num_rows($regions) ; $i++ ): ?>
					<?php $region = pg_fetch_array($regions, NULL, PGSQL_ASSOC); ?>
					<option value="<?= $region['id'] ?>"><?= $region['name'] ?></option>
				<?php endfor; ?>
			</select>
			<select id="pref" name="pref" class="form-control region" required>
				<option hidden></option>
			</select>
			<label for="hobby">趣味</label>
				<?php for( $i = 0 ; $i < pg_num_rows($hobbies) ; $i++ ): ?>
					<?php $hobby = pg_fetch_array($hobbies, NULL, PGSQL_ASSOC); ?>
					<?php $checked = ((!empty($user_hobby_id) && in_array($hobby['id'], $user_hobby_id)) ? 'checked' : '') ?>
					<span style="white-space: nowrap;">
						<input type="checkbox" id="hobby<?= $hobby['id'] ?>" name="hobby[]" value="<?= $hobby['id'] ?>" <?= $checked ?>>
						<label for="hobby<?= $hobby['id'] ?>" class="hobby_label"><?= $hobby['name'] ?></label>
					</span>
				<?php endfor; ?>
			<input type="submit" class="btn btn-primary submit" value="確認">
		</div>
	</div>
</form>

<script>
	$(function(){
		$("#birthday").datepicker({
			dateFormat : 'yy-mm-dd'
			,changeMonth: true
			,changeYear: true
			,yearRange: '1900:+1'
		});

		$("#birthday").change(function() {
			$("#age").text(getAge($("#birthday").val()) + ' 歳')
		});

		$("#region").change(function() {
			$("#pref").children().remove();
			$("#pref").append($("<option hidden>"));
			$.ajax({
				url:'../ajax/get_pref.php',
				type:'POST',
				data:{
					'region_id':$('#region').val()
				}
			})
			//成功
			.done( (data) => {
				$.each(data, function(id, name) {
					$("#pref").append($("<option>").attr({ value: id}).text(name));
				});
				$('#pref').val(<?= $user['pref_id'] ?>);
			})
			//失敗
			.fail( (jqXHR, textStatus, errorThrown) => {
				alert('Ajax通信に失敗しました。');
				console.log("jqXHR          : " + jqXHR.status);
				console.log("textStatus     : " + textStatus);
				console.log("errorThrown    : " + errorThrown.message);
			})
		});

		//編集時の初期値セット
		<?php if( !empty($id) ): ?>
			$("#birthday").change();
			$('#region').val(<?= $user['region_id'] ?>);
			<?php foreach( $user_pref_list as $key => $pref ): ?>
				$("#pref").append($("<option>").attr({ value: <?= $key ?>}).text("<?= $pref ?>"));
			<?php endforeach; ?>
			$('#pref').val(<?= $user['pref_id'] ?>);
		<?php endif; ?>
	});
</script>

<?php include("../footer.php"); ?>
