<?php
session_start();

include "db.php";
if(isset($_GET['id'])){
	$post_id = intval($_GET['id']);
}
else{
	echo "<script>alert('잘 못된 접근입니다.');location.href = 'postlist.php'</script>";
	exit;
}
$sql_query = "SELECT posts.*,users.username FROM posts LEFT JOIN users ON posts.author_id = users.id WHERE posts.id = $post_id";
$res = $db->query($sql_query);
$row = $res->fetch_assoc();

if(!$row){
	echo "<script>alert(지워졌거나 없어진 글 입니다.); location.href = 'postlist.php'</script>";
	exit;
}
?>

<!DOCTYPE html>
<html lang = "ko">
<html>
<head>
	<meta charset = "UTP-8">
	<title><?php echo htmlspecialchars($row['title']); ?></title>
	<style>
		body { font-family: sans-serif; margin: 40px; line-height: 1.6; }
        	.post-container { max-width: 800px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        	.post-title { font-size: 24px; font-weight: bold; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 10px; }
        	.post-meta { font-size: 14px; color: #666; margin-bottom: 20px; text-align: right; }
        	.post-content { min-height: 200px; background: #f9f9f9; padding: 15px; border-radius: 4px; white-space: pre-wrap; }
        	.btn-group { margin-top: 20px; text-align: center; }
        	.btn { display: inline-block; padding: 8px 16px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px; margin: 0 5px; }
        	.btn-secondary { background-color: #6c757d; }
		.comment-divider { border: 0; border-top: 1px solid #ccc; margin: 40px 0 20px; }
        	.comment-section { max-width: 800px; margin: 0 auto; padding-bottom: 50px; }
        	.comment-title { color: #333; margin-bottom: 20px; }
        	.comment-item { border-bottom: 1px solid #eee; padding: 15px 0; }
        	.comment-author { color: #007BFF; font-weight: bold; }
        	.comment-date { color: gray; font-size: 12px; margin-left: 10px; }
        	.comment-text { margin-top: 5px; line-height: 1.5; color: #444; }
        	.comment-empty { color: #888; font-size: 14px; }
        	.comment-form { margin-top: 30px; display: flex; gap: 10px; }
        	.comment-input { flex-grow: 1; padding: 12px; border-radius: 6px; border: 1px solid #ccc; font-size: 15px; }
        	.comment-btn { padding: 12px 25px; }
	</style>
</head>
<body> <!--div = 내용물 담는 상자 한번에 옮기거나 할 수 있음-->
	<div class = "post-container">
		<div class = "post-title"><?php echo htmlspecialchars($row['title']);?></div>
		<div class = "post-meta">작성일: <?php echo htmlspecialchars($row['created_at']);?>
		<?php if($row['created_at'] !== $row['updated_at']):?> <!--updated_at은 변경된 사항이 없으면 작성일자와 같음-->
		<!--!==는 php에서 데이터 타입과 내용을 비교 !=로 비교할 시 내용만 비교-->
		| 최근 수정일: <?php echo htmlspecialchars($row['updated_at']);?>
		<?php endif; ?></div>
		<div class = "post-content"><?php echo htmlspecialchars($row['content']);?></div>
	</div>
	<div class = 'btn-group'>
		<a href = "postlist.php" class = "btn btn-secondary">목록으로</a>
		<?php if(isset($_SESSION['user_id'])&&$_SESSION['user_id'] = $row['author_id']): ?>
			<a href = "edit.php?id=<?php echo $row['id'];?>" class = "btn btn-secondary">수정</a>
			<a href="delete_action.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
		<?php endif; ?>
</div>
<hr class="comment-divider">
    <div class="comment-section">
        <h3 class="comment-title">댓글</h3>

        <?php
        $comments_sql = "SELECT comments.*, users.username FROM comments LEFT JOIN users ON comments.author_id = users.id WHERE post_id = $post_id ORDER BY comments.id ASC";
        $comments_res = $db->query($comments_sql);

        if ($comments_res->num_rows > 0) {
            while($comments_row = $comments_res->fetch_assoc()) {
                echo "<div class='comment-item'>";
                echo "<span class='comment-author'>" . htmlspecialchars($comments_row['username']) . "</span>";
                echo "<span class='comment-date'>" . $comments_row['created_at'] . "</span>";
		if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comments_row['author_id']) {
			echo " <a href='comments_edit.php?id=" . $comments_row['id'] . "' style='font-size:12px; color:#007BFF; text-decoration:none; margin-left:10px;'>[수정]</a>";
			echo " <a href='comments_delete.php?id=" . $comments_row['id'] . "&post_id=" . $post_id . "' style='font-size:12px; color:#dc3545; text-decoration:none; margin-left:5px;' onclick='return confirm(\"정말 삭제하시겠습니까?\");'>[삭제]</a>";
		}
                echo "<div class='comment-text'>" . nl2br(htmlspecialchars($comments_row['content'])) . "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='comment-empty'>아직 작성된 댓글이 없습니다.</p>";
        }
        ?>

        <?php if(isset($_SESSION['user_id'])): ?>
        <form action="comments_action.php" method="POST" class="comment-form">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <input type="text" name="content" placeholder="댓글을 남겨보세요!" required class="comment-input">
            <button type="submit" class="btn comment-btn">등록</button>
        </form>
        <?php endif; ?>

	<?php if(!empty($row['file_name'])): ?>
		<div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 5px;">
			<strong style="color: #333;">첨부파일:</strong> 
			<a href="uploads/<?php echo htmlspecialchars($row['file_name']); ?>" download style="color: #007BFF; text-decoration: none; margin-left: 10px;">
                		<?php echo htmlspecialchars(substr($row['file_name'], strpos($row['file_name'], '_') + 1)); ?>
			</a>
		</div>
	<?php endif; ?>
    </div>
</body>
</html>
