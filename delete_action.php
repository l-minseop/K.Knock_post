<?php
session_start();

include "db.php";

if(!isset($_SESSION['user_id'])){
	echo "<script>alert('로그인이 필요합니다.'); location.href = 'login.php'</script>";
	exit;
}

$post_id = intval($_GET['id']);
$current_user = intval($_SESSION['user_id']);

$check_sql = "SELECT author_id FROM posts WHERE id = $post_id";
$res = $db->query($check_sql);
$row = $res->fetch_assoc();

if ($row && $row['author_id'] == $current_user) {
	$delete_sql = "DELETE FROM posts WHERE id = $post_id";
	$db->query($delete_sql);
	echo "<script>alert('게시글이 삭제되었습니다.'); location.href='postlist.php';</script>";
}
else{
	echo "<script>alert('본인의 글만 삭제할 수 있습니다.') history.back();</script>";
}
?>
