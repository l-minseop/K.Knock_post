<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "db.php";


if(!isset($_SESSION['user_id'])) {
	echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
	exit;
}

if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['contents'])){

	$post_id = intval($_POST['id']);
	$new_title = $_POST['title'];
	$new_contents = $_POST['contents'];
	$user_id = $_SESSION['user_id']; // 내 로그인 번호

	$safe_title = $db->real_escape_string($new_title);
	$safe_contents = $db->real_escape_string($new_contents);


	$file_update_sql = "";

	if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
		$tmp_name = $_FILES['upload_file']['tmp_name'];
		$original_name = $_FILES['upload_file']['name'];

		$new_file_name = time() . "_" . $original_name;
		$upload_path = "./uploads/" . $new_file_name;

		if(move_uploaded_file($tmp_name, $upload_path)) {

			$file_update_sql = ", file_name = '$new_file_name'";
		}
	}

$sql_query = "UPDATE posts SET title = '$safe_title', content = '$safe_contents' $file_update_sql WHERE id = $post_id AND author_id = $user_id";

	$res = $db->query($sql_query);



	if($res){
		echo "<script>alert('글이 수정되었습니다.'); location.href = 'postview.php?id=" . $post_id . "';</script>";
	}
	else{
		echo "오류 : " . $db->error;
	}
}
else {
	echo "<script>alert('잘못된 접근입니다.');location.href = 'postlist.php';</script>";
	exit;
}
?>
