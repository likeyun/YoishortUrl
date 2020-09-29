<?php
header("Content-type:application/json");

//GET URL
$url = $_GET["url"];

//过滤数据
if (trim(empty($url))) {
	$result = array(
		"code" => "201",
		"msg" => "请传入长链接"
	);
}else if (strpos($url,'http') !== false){

	// 数据库配置
	include './db_connect.php';

	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 检测连接
	if ($conn->connect_error) {
		$result = array(
			"code" => "203",
			"msg" => "数据库连接失败，原因：".$conn->connect_error
		);
	} else {
		
		// 查询URL
		$checkUrl = "SELECT dwzkey FROM $table WHERE long_url = '$url'";
		$result_checkUrl = $conn->query($checkUrl);

		//检查数据库是否已经存在该URL
		if ($result_checkUrl->num_rows > 0) {

			// 获得当前url下的dwzkey
			while($row_checkUrl = $result_checkUrl->fetch_assoc()) {
				$dwzkey = $row_checkUrl["dwzkey"];
			}

			// 返回数据库的dwzkey
			$result = array(
				"code" => "200",
				"msg" => "获取成功",
				"short_url" => $server_path."/".$dwzkey
			);

		}else{

			//生成4位数的dwzkey
			$key_str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
			$dwzkey = substr(str_shuffle($key_str),mt_rand(0,strlen($key_str)-11),4);

			// 插入数据库
			$sql_creatdwz = "INSERT INTO $table (dwzkey, long_url) VALUES ('$dwzkey', '$url')";

			// 验证插入结果
			if ($conn->query($sql_creatdwz) === TRUE) {
			    $result = array(
					"code" => "200",
					"msg" => "创建成功",
					"short_url" => $server_path."/".$dwzkey
				);
			} else {
			    $result = array(
					"code" => "205",
					"msg" => "创建失败，数据库配置发生错误"
				);
			}

			// 断开数据库连接
			$conn->close();
		}
	}
}else{
	$result = array(
		"code" => "202",
		"msg" => "你传入的不是链接"
	);
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>
