<?php
session_start();
//로그인 시 세션을 통해 아이디확인
error_reporting(E_ALL);
ini_set('display_errors','1');

include "db.php";

if(isset($_POST['username'])&&isset($_POST['password'])){
	$user = $_POST['username'];
	$pass = $_POST['password'];
}
else{
	echo "<script>alert('잘못된 접근입니다'); location.href = 'login.php';</script>";
	exit;
}

$sql = "SELECT * FROM users WHERE username = '" . $db->real_escape_string($user) . "'";
$res = $db->query($sql);

if($res->num_rows == 1){
	//줄 불러오기
	$row = $res->fetch_assoc();
	//받은 비밀번호 해시해서 같은지 확인
	if(password_verify($pass,$row['password'])){
		$_SESSION['username'] = $row['username'];
		$_SESSION['user_id'] = $row['id'];
		echo "<script>alert('로그인에 성공하였습니다.'); location.href = 'postlist.php';</script>";
		exit;
	}
	else{
		echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
	}
}
//계정없을 때
else{
	echo "<script>alert('존재하지 않는 아이디 입니다.'); history.back();</script>";
}


?>
