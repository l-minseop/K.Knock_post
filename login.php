<DOCTYPE! html>
<html lang = "ko">
<head>
	<meta char = "UTF-8">
	<title>로그인</title>
	<style>
		body { font-family: sans-serif; margin: 40px; }
        	.form-container { max-width: 400px; margin: 0 auto; border: 1px solid #ddd; padding: 30px; border-radius: 8px; }
        	.form-group { margin-bottom: 15px; }
        	.form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        	.form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        	.btn { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        	.btn:hover { background-color: #218838; }
        	.link-group { text-align: center; margin-top: 15px; font-size: 14px; }
        	.link-group a { color: #007BFF; text-decoration: none; }
	</style>
</head>

<body>
	<div class = "form-container">
		<h1>로그인</h1>
		<form action = "login_action.php" method = "POST">
			<div class = "form-group">
				<label for = "username">아이디</label>
				<input type = "text" id = "username" name = "username" class = "form-control" placeholder = "아이디 입력" required>
			</div>

			<div class = "form-group">
				<label for = "password">비밀번호</label>
				<input type = "password" id = "password" name = "password" class = "form-control" placeholder = "비밀번호  입력" required>
			</div>

			<button type = "submit" class = "btn">로그인</button>

			<div class = "link-group">
				아직 회원이 아니신가요?<a href = "register.php">회원가입</a>
			</div>
		</form>
	</div>
</body>
</html>
