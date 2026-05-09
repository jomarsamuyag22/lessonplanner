<?php include "db.php"; $id=intval($_GET['id']??0); $stmt=$conn->prepare("DELETE FROM lesson_plans WHERE id=?"); $stmt->bind_param('i',$id); $stmt->execute(); header('Location: index.php'); exit; ?>
