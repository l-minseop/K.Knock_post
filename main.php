<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>메인 화면</title>
</head>
<body style="text-align: center; padding-top: 100px; font-family: sans-serif;">
	<h1>* 환영합니다! *</h1>
	<p>원하시는 게시판을 선택해 주세요.</p>

	<div style="margin-top: 30px; display: flex; justify-content: center; gap: 20px;">
		<a href="postlist.php?board=free" style="padding: 20px 40px; background: #007BFF; color: white; text-decoration: none; border-radius: 8px; font-size: 18px; font-weight: bold;">자유게시판</a>

		<a href="postlist.php?board=guest" style="padding: 20px 40px; background: #28a745; color: white; text-decoration: none; border-radius: 8px; font-size: 18px; font-weight: bold;">방명록</a>
	</div>
</body>
</html>
