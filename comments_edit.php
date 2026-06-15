<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])) {
	echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
	exit;
}

$comment_id = intval($_GET['id'] ?? $_POST['id']);
$user_id = $_SESSION['user_id'];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$content = $db->real_escape_string($_POST['content']);
	$post_id = intval($_POST['post_id']);
	$sql = "UPDATE comments SET content='$content' WHERE id=$comment_id AND author_id=$user_id";
	$db->query($sql);

	echo "<script>location.href='postview.php?id=$post_id';</script>";
	exit;
}

$sql = "SELECT * FROM comments WHERE id=$comment_id AND author_id=$user_id";
$res = $db->query($sql);
$row = $res->fetch_assoc();

if(!$row) {
	echo "<script>alert('권한이 없거나 삭제된 댓글입니다.'); history.back();</script>";
	exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>댓글 수정</title>
</head>
<body style="padding: 50px; background-color: #f4f4f4; font-family: sans-serif;">

	<div style="background: white; max-width: 500px; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
		<h3 style="margin-top:0;">댓글 수정</h3>
		<form method="POST">
			<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
			<input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
			<textarea name="content" required style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical;"><?php echo htmlspecialchars($row['content']); ?></textarea>

			<div style="margin-top: 20px; text-align: right;">
				<button type="button" onclick="history.back();" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">취소</button>
                		<button type="submit" style="padding: 10px 20px; background: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">수정완료</button>
            </div>
        </form>
    </div>

</body>
</html>

