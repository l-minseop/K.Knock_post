<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='login.php';</script>";
    exit;
}

$comment_id = intval($_GET['id']);
$post_id = intval($_GET['post_id']);
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM comments WHERE id=$comment_id AND author_id=$user_id";
$db->query($sql);

echo "<script>location.href='postview.php?id=$post_id';</script>";
?>
