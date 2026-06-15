<?php
session_start();
//db 접근 열쇠
include "db.php";

if(!isset($_SESSION['user_id'])){
	echo "<script>alert(로그인 후 이용하실 수 있습니다.); history.back()</script>;";
	exit;
}

//POST 요청으로 받았는지
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //_POST는 POST로 받은 데이터들
    $title = $_POST['posttitle'];
    $content = $_POST['postcontents'];

    if (strlen($title) > 50) {
        die("제목이 너무 깁니다. (최대 50자)");
    }
    if (strlen($content) > 255) {
        die("내용이 너무 많습니다. (최대 255자)");
    }
	$safe_title = $db->real_escape_string($title);
	$safe_content = $db->real_escape_string($content);
	$board_type = isset($_POST['board_type']) ? $db->real_escape_string($_POST['board_type']) : 'free';

	$author_id = $_SESSION['user_id'];
	$file_name = "";


	if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
		$tmp_name = $_FILES['upload_file']['tmp_name'];
		$original_name = $_FILES['upload_file']['name'];


		$file_name = time() . "_" . $original_name;
		$upload_path = "./uploads/" . $file_name;

		move_uploaded_file($tmp_name, $upload_path);
	}

	$sql_query = "INSERT INTO posts (title, content,author_id,file_name,board_type) VALUES ('$safe_title', '$safe_content','$author_id','$file_name','$board_type');";
	$result = $db->query($sql_query);


	if ($result) {
	//글 등록후 알림 뜨고 창 위치를 postlist.php로 옮김
		echo "<script>alert('글이 등록되었습니다.'); location.href = 'postlist.php?board_type=$board_type';</script>";
		exit;
	}
	else {
		die("데이터 저장 오류: " . $db->error);
	}
}
?>
