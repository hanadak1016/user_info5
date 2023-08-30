function getAge(ymd) {
	//�����𕪉�
	var ymd_arr = ymd.split('-');
	var year = ymd_arr[0];
	var month = ymd_arr[1];
	var day = ymd_arr[2];

	//�����̓��t�f�[�^���擾
	const today = new Date();

	//���N�����̓��t�f�[�^���擾
	const birthdate = new Date(year, month - 1, day);

	//���N�̒a�����̓��t�f�[�^���擾
	const currentYearBirthday = new Date(today.getFullYear(), birthdate.getMonth(), birthdate.getDate());

	//���܂ꂽ�N�ƍ��N�̍����v�Z
	let age = today.getFullYear() - birthdate.getFullYear();

	//�����̓��t�ƍ��N�̒a�������r
	if (today < currentYearBirthday) {
		//���N�a�������}���Ă��Ȃ��ꍇ�A1������
		age--;
	}

	// �N��̒l��Ԃ�
	return age;
}