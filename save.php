<?php
include "db.php"; include "helpers.php";
$d=collect_post(); $d['lesson_content']=buildLessonContent($d); $d['ai_prompt']=buildAiPrompt($d);
$fields=dbFields(); $cols=implode(',',$fields); $marks=rtrim(str_repeat('?,',count($fields)),',');
$stmt=$conn->prepare("INSERT INTO lesson_plans ($cols) VALUES ($marks)");
$vals=[]; foreach($fields as $f)$vals[]=$d[$f]; $types=str_repeat('s',count($vals)); $stmt->bind_param($types,...$vals);
if($stmt->execute()){header('Location: view.php?id='.$stmt->insert_id);exit;} echo 'Error: '.$conn->error;
?>
