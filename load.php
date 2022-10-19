<?php
function getConnection()
{
$dbhost="192.168.119.131";
$dbuser="prod";
$dbpass="prod#123";
$dbname="prod_db_man";
$dbh = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
return $dbh;
}
$sql = "SELECT * FROM `organogram` ORDER BY `REPORTS_TO`";
try {
	$db = getConnection();
	$sql_query = mysqli_query($db, $sql);

	$result = [];
	while ($row = mysqli_fetch_array($sql_query)) {
		$temp = [];
		$temp['id'] = $row['id'];
		$temp['NAME'] = $row['NAME'];
		$temp['REPORTS_TO'] = $row['REPORTS_TO'];
		$temp['DESIGNATION'] = $row['DESIGNATION'];
		$data =  file_get_contents("http://192.168.150.232:8080/pb/hrms_apps_v_1_0_3/employee_basic/emb_id.php?id=".$row['EMP_CODE']);
		$data = json_decode($data);
		$temp['image'] = $data->PIC_URL_;
		$result[] = $temp;
	}

	





	$db = null;
	$all_emp = [];
	$all_emp_res = [];
	$only_pre = [];
	$chk = '-x';
	$yyy = 1;

	$all_emp[''] = '';
	foreach ($result as $key => $value) {
		$all_emp[$value['NAME']] = $value['id'];
	}

	foreach ($result as $key => $value) {
		if ($chk != $value['REPORTS_TO']) {
			$only_pre[$value['REPORTS_TO']] = $all_emp[$value['REPORTS_TO']];
			$chk = $value['REPORTS_TO'];
		}
	}
	foreach ($result as $key => $value) {
		$temp = [];
		$temp['memberId'] = $value['id'];
		$temp['parentId'] = $only_pre[$value['REPORTS_TO']];
		$temp['nameInfo'] = $value['NAME'];
		$temp['deginfo'] = $value['DESIGNATION'];
		 $temp['image'] = $value['image'];
		$all_emp_res[] = $temp;
	};

	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
	{
		$sort_col = array();
		foreach ($arr as $key => $row) {
			$sort_col[$key] = $row[$col];
		}

		array_multisort($sort_col, $dir, $arr);
	}
	array_sort_by_column($all_emp_res, 'memberId');

	echo json_encode($all_emp_res);
} catch (Exception $e) {
	echo '{"error":{"text":' . $e->getMessage() . '}}';
}
