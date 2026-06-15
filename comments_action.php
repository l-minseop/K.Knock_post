<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// 폼에서 넘어온 데이터 받기
$post_id = intval($_POST['post_id']);
$content = $db->real_escape_string($_POST['content']);
$author_id = $_SESSION['user_id'];

// 댓글을 DB에 밀어넣기
$sql = "INSERT INTO comments (post_id, author_id, content) VALUES ($post_id, $author_id, '$content')";
$db->query($sql);

// 댓글 등록이 끝나면 방금 보던 그 글로 다시 돌아가기
header("Location: postview.php?id=$post_id");
?>
