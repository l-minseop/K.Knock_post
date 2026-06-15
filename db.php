<?php
//게시물 작성,수정, 삭제 등을 하기 위해 데이터베이스로 가는 문을 열어주는 php
$host = 'localhost'; //주소
$user = 'board_lms'; //누가 들어갈건지
$password = '1234'; 
$dbname = 'board'; //사용할 데이터베이스

$db = new mysqli('localhost','board_lms','1234','board'); //sql에 정보를 넘기고  공간 생성 및 sql접속시도,sql 문 사용가능
$db->set_charset("utf8mb4");
if($db->connect_error){ //$db에 담긴 변수 connect_error -> 연결 성공 시 NULL또는 비어있음, 실패 시 에러 문자열 (참)
  die("연결 실패". $db->connect_error);
}
?>
