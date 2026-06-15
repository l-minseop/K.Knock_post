<?php
session_start();
// php 영역
// db 키 받기
include "db.php";

$search = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'title';
$sort = (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'ASC' : 'DESC';
$board = isset($_GET['board']) ? $_GET['board'] : 'free';

$where_clause = "WHERE 1=1";
$where_clause .= " AND posts.board_type = '$board'";
if(isset($_GET['my_posts']) && $_GET['my_posts'] == 1 && isset($_SESSION['user_id'])) {
	$currentUser = $_SESSION['user_id'];
	$where_clause .= " AND posts.author_id = $currentUser";
}

if($search !== '') {
	if($search_type === 'author') {
		// '작성자'로 검색했을 때 (users 테이블의 username에서 찾기!)
		$where_clause .= " AND users.username LIKE '%$search%'";
	}
	else {
	// '제목'으로 검색했을 때 (기본값)
		$where_clause .= " AND posts.title LIKE '%$search%'";
	}
}

$sql_query = "SELECT posts.*, users.username FROM posts LEFT JOIN users ON posts.author_id = users.id $where_clause ORDER BY posts.id $sort";
//posts 테이블에서 내림차순으로 데이터 조회
$result = $db->query($sql_query);

if (!$result) {
    die("데이터 가져오기 오류: " . $db->error);
}
?>
<!--html 영역-->
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시판 목록</title>
    <style>
	/*css*/
	.header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
	/*환영인사 및 제목*/
        body { font-family: sans-serif; margin: 40px; } /*화면 전체 글씨*/
        table { width: 100%; border-collapse: collapse; margin-top: 20px; } /*게시판 목록 표*/
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; } /*표 제목 칸(th),표 내용 칸(td)*/
        th { background-color: #f4f4f4; } /*표 제목 칸 배경 색*/
        tr:hover { background-color: #f9f9f9; } /*마우스 커서 올리면 배경색*/
        .btn { display: inline-block; padding: 8px 16px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px; }
	/*글쓰기 버튼*/
	.btn-my { background-color: #17a2b8; }
	/* 내 글 보기 버튼 색상 */
        .btn-all { background-color: #6c757d; }
	/* 전체 글 보기 버튼 색상 */
	.top-right-menu {
            position: absolute;
            top: 25px;
            right: 40px;
        }
    </style>
</head>
<body>
	<div style="background-color: #f8f9fa; padding: 15px; border-bottom: 2px solid #ddd; margin-bottom: 20px; display: flex; gap: 20px;">
		<a href="main.php" style="text-decoration: none; color: #333; font-weight: bold;">메인화면</a>
		<span style="color: #ccc;">|</span>
		<a href="postlist.php?board=free" style="text-decoration: none; color: <?php echo ($board == 'free') ? '#007BFF' : '#555'; ?>; font-weight: bold;">자유게시판</a>
		<span style="color: #ccc;">|</span>
		<a href="postlist.php?board=guest" style="text-decoration: none; color: <?php echo ($board == 'guest') ? '#28a745' : '#555'; ?>; font-weight: bold;">방명록</a>
	</div>
	<div class="top-right-menu">
        <?php if(isset($_SESSION['username'])): ?>
		<strong><?php echo htmlspecialchars($_SESSION['username']); ?>님</strong>
        	<a href="logout.php" style="margin-left: 15px; color: #dc3545; text-decoration: none; font-weight: bold;">[로그아웃]</a>
        <?php else: ?>
		<a href="login.php" class="btn">로그인</a>
		<a href="register.php" class="btn btn-all">회원가입</a>
        <?php endif; ?>
	</div>
	<h1>게시판 글 목록</h1>
</div>
    <div style = "margin-bottom: 10px;">
	<?php if(isset($_SESSION['user_id'])): ?>
	<h2><?php echo ($board == 'guest') ? '방명록' : '자유게시판'; ?></h2>
		<a href="postwrite.php?board=<?php echo $board; ?>" class="btn">새 글 쓰기</a>
		<?php if(isset($_GET['my_posts'])): ?>
			<a href = "postlist.php" class = "btn btn-all">전체 게시물 보기</a>
		<?php else: ?>
			<a href = "postlist.php?my_posts=1" class = "btn btn-all">내 게시물 보기</a>
		<?php endif; ?>
	<?php endif; ?>
    </div>
	<form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; justify-content: flex-end;">
		<?php if(isset($_GET['my_posts'])): ?>
			<input type="hidden" name="my_posts" value="<?php echo $_GET['my_posts']; ?>">
		<?php endif; ?>

	<select name="sort" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
		<option value="desc" <?php echo $sort == 'DESC' ? 'selected' : ''; ?>>최신순</option>
		<option value="asc" <?php echo $sort == 'ASC' ? 'selected' : ''; ?>>오래된순</option>
	</select>

	<select name="search_type" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
		<option value="title" <?php echo $search_type == 'title' ? 'selected' : ''; ?>>제목</option>
		<option value="author" <?php echo $search_type == 'author' ? 'selected' : ''; ?>>작성자</option>
	</select>

	<input type="text" name="search" placeholder="검색어 입력" value="<?php echo htmlspecialchars($search); ?>" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
	<button type="submit" class="btn" style="padding: 6px 15px;">검색</button>
    </form>
	<table> <!-- 표 생성 (th제목,tr행,td데이터필요)-->
        <thead>
            <tr>
                <th style="width: 5%;">번호</th>
                <th style="width: 40%;">제목</th>
                <th style="width: 15%;">작성자 ID</th>
                <th style="width: 20%;">작성일</th>
		<th style="width: 20%;">수정일</th>
            </tr>
        </thead>
        <tbody> <!--php 영역-->
            <?php
	    $display_num = $result->num_rows;
            while ($row = $result->fetch_assoc()) { //result에 들어가있는 데이터 표에서 한 행씩 가져옴
                echo "<tr>"; //이 행에
                echo "<td>" . $display_num-- . "</td>"; //id
                echo "<td style='text-align: left;'><a href='postview.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></td>";
                // 왼쪽 정렬로 누르면 주소로 넘어가게끔 htmlspecialchars = 특수문자를 특수문자로 읽게 포맷스트링 처리
		echo "<td>" . htmlspecialchars($row['username']) . "</td>"; // 작가 이름
                echo "<td>" . $row['created_at'] . "</td>"; // 만든 시간
		if($row['created_at'] !== $row['updated_at'] && $row['updated_at' !== NULL]){
                	echo "<td>" . $row['updated_at'] . "</td>"; //수정 시간
		}
		else{
			echo "<td>수정없음</td>";
		}
		echo "</tr>";
            }

            if ($result->num_rows === 0) { //데이터 표에 행이 없을 때
                echo "<tr><td colspan='5'>등록된 게시글이 없습니다.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>


