function getAge(ymd) {
	//引数を分解
	var ymd_arr = ymd.split('-');
	var year = ymd_arr[0];
	var month = ymd_arr[1];
	var day = ymd_arr[2];

	//今日の日付データを取得
	const today = new Date();

	//生年月日の日付データを取得
	const birthdate = new Date(year, month - 1, day);

	//今年の誕生日の日付データを取得
	const currentYearBirthday = new Date(today.getFullYear(), birthdate.getMonth(), birthdate.getDate());

	//生まれた年と今年の差を計算
	let age = today.getFullYear() - birthdate.getFullYear();

	//今日の日付と今年の誕生日を比較
	if (today < currentYearBirthday) {
		//今年誕生日を迎えていない場合、1を引く
		age--;
	}

	// 年齢の値を返す
	return age;
}