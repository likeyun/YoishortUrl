<?php
header("Content-Type:text/html;charset=utf-8");
//获得当前传过来的dwzkey
$dwzkey = $_GET["id"];
echo "<title>正在跳转</title>";
//过滤数据
if (trim(empty($dwzkey))) {
	echo "链接不存在";
}else{
	// 数据库配置
	include './db_connect.php';

	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 检测连接
	if ($conn->connect_error) {
		echo "数据库连接失败，原因：".$conn->connect_error;
	} else {
		// 更新访问量
		mysqli_query($conn,"UPDATE $table pageview=pageview+1 WHERE dwzkey='$dwzkey'");
		
		//检查数据库是否存在该dwzkey
		$checkKey = "SELECT * FROM $table WHERE dwzkey = '$dwzkey'";
		$result_checkKey = $conn->query($checkKey);
		if ($result_checkKey->num_rows > 0) {
			//如果存在，则解析出长链接并跳转
			while($row_checkKey = $result_checkKey->fetch_assoc()) {
				$long_url = $row_checkKey["long_url"];
				// 跳转到长链接
				header("Location:$long_url");
			}
		}else{
			// 否则输出
			echo "链接不存在";
		}
	}
}
?>
