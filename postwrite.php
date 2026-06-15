<?php
session_start();
if(!isset($_SESSION['user_id'])){
	echo "<script>alert('로그인이 필요합니다.'); location.href = 'login.php';</script>";
	exit;
}
?>
<!DOCTYPE html>
<!-- html파일로 사용-->
<html lang = "ko"> <!--메인언어 한글 영어는 en-->
<html>
<head>
	<meta charset="UTF-8"> <!--인코딩 UTF-8로 설정-->
	<title>게시물 작성</title>
	<style>
		body {font-family: sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 40px; margin: 0; box-sizing: border-box;}
		.write-container {background-color: white; width: 100%; max-width: 600px; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); box-sizing: border-box;}
		h1{text-align: center; color: #333; margin-bottom: 30px;}
		label{display: block; margin-bottom: 8px; font-weight: bold; color: #555;}
		input [type = "text"], textarea{width: 100px; padding: 12px; width: 100%; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: sans-serif; font-size: 14px;}
		input[type="text"]:focus, textarea:focus {border-color: #007BFF; outline: none; box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);}
		textarea {height: 200px; resize: vertical;}
		.submit-btn {width: 100%; padding: 12px; background-color: #007BFF; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px;}
		.submit-btn:hover {background-color: #0056b3;}
	</style>
</head>

<body>
<div class = "write-container">
	<h1>새 게시물 작성</h1>

	<form action="write.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="board_type" value="<?php echo isset($_GET['board']) ? htmlspecialchars($_GET['board']) : 'free'; ?>">
	<label for="title">제 목: </label>
	<input type="text" id="title" name="posttitle" placeholder="제목을 입력하세요" maxlength="50">

	<label for="contents">내 용: </label>
	<textarea id="contents" name="postcontents" maxlength="255" placeholder="내용을 입력하세요" required></textarea>

	<div style="margin-top: 10px;">
		<input type="file" name="upload_file">
	</div>

	<button type="submit" class="submit-btn">등록</button>

	</form>
</div>
</body>
</html>
