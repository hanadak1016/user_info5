<?php include("../default.php"); ?>

<?php
	//削除処理
	$msg = '';
	if( !empty($_GET['delete']) ){
		//削除対象検索
		$res = pg_query("SELECT * FROM users WHERE deleted IS NULL AND id = {$_GET['delete']};");
		if( false !== pg_fetch_result($res, 0) ){
			$user = pg_fetch_assoc( $res, 0 );

			//削除
			$datetime = date('Y-m-d H:i:s');
			$sql  = <<<EOF
				UPDATE users SET
				(
					deleted
					,modified
				) = (
					'{$datetime}'
					,'{$datetime}'
				)
				WHERE id = {$user['id']}
			EOF;
			$result_flag = pg_query($sql);

			if( !$result_flag ){
				die('クエリーが失敗しました。'.pg_last_error());
			}
			$msg = 'ユーザーの削除が完了しました。';
		}
	}

	//ユーザー一覧取得
	$res = pg_query('SELECT * FROM users WHERE deleted IS NULL ORDER BY id');
	if( !$res ){
		die('クエリーが失敗しました。'.pg_last_error());
	}
?>

<style>
	.card {
		max-width: 1200px;
	}
</style>

<!DOCTYPE html>
<head>
	<title>ユーザー情報一覧</title>
</head>
<body>
<?php include("../header.php"); ?>

<form method="get" action="add_edit.php">
	<div class="card">
		<div class="card-header">
			<h5 style="display: inline">ユーザー情報一覧</h5>
			<button type="button" class="btn btn-success" onclick="location.href='add_edit.php'" style="float: right;">新規作成</button>
		</div>
		<div class="card-body">
			<p class="text-danger"><?= $msg ?></p>
			<table class="table table-striped">
				<tr><th>ID</th><th>名前</th><th>名前（ふりがな）</th><th>郵便番号</th><th>住所</th><th>電話番号</th><th>操作</th></tr>
				<?php for( $i = 0 ; $i < pg_num_rows($res) ; $i++ ): ?>
					<tr>
						<?php $user = pg_fetch_array($res, NULL, PGSQL_ASSOC); ?>
						<td><?= $user['id'] ?></td>
						<td id="name<?= $user['id'] ?>"><?= $user['name'] ?></td>
						<td><?= $user['kana'] ?></td>
						<td><?= $user['zip'] ?></td>
						<td><?= $user['address'] ?></td>
						<td><?= $user['tel'] ?></td>
						<td>
							<button type="submit" name="edit" value="<?= $user['id'] ?>" class="btn btn-success">編集</button>
							<button type="button" id="<?= $user['id'] ?>" class="btn btn-danger delete">削除</button>
						</td>
					</tr>
				<?php endfor; ?>
			</table>
		</div>
	</div>
</form>

<div id="div3" style="display:none;">
	<p id="msg"></p>
	<p>よろしいですか？</p>
</div>

<script>
	$(function(){
		$(".delete").click(function() {
			var id = this.id;
			var name = $('#name'+id).text();
			$("#div3").dialog({
				modal:true,
				title:"削除確認",
				width: 400,
				buttons:[
					{
						text: '削除',
						class:'btn btn-danger',
						click: function() {
							var url = new URL(window.location.href);
							var params = url.searchParams;
							params.delete('delete');
							params.append('delete',id);
							location.href = url;
						}
					},
					{
						text: '閉じる',
						class:'btn btn-Secondary',
						click: function() {
							$(this).dialog("close");
						}
					}
				],
				open: function() {
					$('.ui-button').removeClass('ui-button ui-corner-all ui-widget');
					$('#msg').text('ID:'+id+'【'+name+'】を削除します。');
				}
			});
		});
	});
</script>

<?php include("../footer.php"); ?>
