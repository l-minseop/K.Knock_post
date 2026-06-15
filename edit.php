<?php
session_start();
include "db.php";


if(!isset($_SESSION['user_id'])){
	echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
	exit;
}


if(isset($_GET['id'])){
	$post_id = intval($_GET['id']);
}
else {
	echo "<script>alert('잘못된 접근입니다.'); location.href='postlist.php';</script>";
	exit;
}

$user_id = $_SESSION['user_id'];


$sql_query = "SELECT * FROM posts WHERE id = $post_id AND author_id = $user_id";
$res = $db->query($sql_query);
$row = $res->fetch_assoc();


if(!$row){
    echo "<script>alert('존재하지 않거나 수정 권한이 없는 게시물입니다.'); history.back();</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>게시물 수정</title>
</head>
<body>
	<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
		<h2>게시물 수정</h2>
		<form action="edit_write.php" method="POST" enctype="multipart/form-data">

			<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

		<div style="margin-bottom: 15px;">
			<label for="title">제 목: </label><br>
			<input type="text" id="title" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" style="width: 100%; padding: 8px;" required>
		</div>

		<div style="margin-bottom: 15px;">
			<label for="contents">내 용: </label><br>
			<textarea id="contents" name="contents" style="width: 100%; height: 200px; padding: 8px;" required><?php echo htmlspecialchars($row['content']); ?></textarea>
		</div>

		<div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
			<?php if(!empty($row['file_name'])): ?>
				<p style="margin: 0 0 10px 0; font-size: 14px; color: #555;">
				<strong>기존 첨부파일:</strong>
				<?php echo htmlspecialchars(substr($row['file_name'], strpos($row['file_name'], '_') + 1)); ?>
				</p>
			<?php else: ?>
				<p style="margin: 0 0 10px 0; font-size: 14px; color: #888;">첨부된 파일이 없습니다.</p>
			<?php endif; ?>

			<label style="font-size: 14px; font-weight: bold;">새로운 파일로 교체 (선택): </label>
			<input type="file" name="upload_file" style="margin-top: 5px; display: block;">
		</div>

			<button type="submit" style="padding: 10px 20px; background: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer;">수정완료</button>
        		<button type="button" onclick="history.back();" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">취소</button>
		</form>
	</div>
</body>
</html>
