<?php
include "db.php"; include "helpers.php"; $id=intval($_POST['id']??0);
$d=collect_post(); $d['lesson_content']=buildLessonContent($d); $d['ai_prompt']=buildAiPrompt($d);
$fields=dbFields(); $set=implode('=?,',$fields).'=?'; $stmt=$conn->prepare("UPDATE lesson_plans SET $set WHERE id=?");
$vals=[]; foreach($fields as $f)$vals[]=$d[$f]; $vals[]=$id; $types=str_repeat('s',count($fields)).'i'; $stmt->bind_param($types,...$vals);
if($stmt->execute()){header("Location: view.php?id=$id");exit;} echo 'Error: '.$conn->error;
?>
