<?php
error_reporting(E_ALL);
ini_set('display_errors','1');

include "db.php";

if(isset($_POST['username'])&&isset($_POST['password'])){
	$user = $_POST['username'];
	$pass = $_POST['password'];
}
else{
	echo "<script>alert('잘못된 접근입니다.'); location.href = 'register.php';</script>";
	exit;
}

$check_sql = "SELECT id FROM users WHERE username = '" . $db->real_escape_string($user) . "'";
$check_res = $db->query($check_sql);

if ($check_res->num_rows > 0) {
    echo "<script>alert('이미 존재하는 아이디입니다.'); history.back();</script>";
    exit;
}
//비밀번호 해싱 PASSWORD_DEFAULT = 현재 가장안전한 보안 알고리즘(Bcrypt 60자리 암호문)
$hashed_pass = password_hash($pass,PASSWORD_DEFAULT);

//유저 아이디와 비밀번호 db에 넣기
$sql_insert = "INSERT INTO users (username, password) VALUES ('" . $db->real_escape_string($user) . "','" . $db->real_escape_string($hashed_pass) . "')";

$result = $db->query($sql_insert);

if ($result) {
    echo "<script>alert('회원가입이 완료되었습니다! 로그인해 주세요.'); location.href = 'login.php'; </script>";
}
else {
    echo "회원가입 데이터 저장 중 오류 발생: " . $db->error;
}
?>
