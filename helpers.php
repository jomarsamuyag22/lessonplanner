<?php
function clean($v){return htmlspecialchars(trim($v ?? ''),ENT_QUOTES,'UTF-8');}
function out($v){return htmlspecialchars($v ?? '',ENT_QUOTES,'UTF-8');}
function br($v){return nl2br(htmlspecialchars($v ?? '',ENT_QUOTES,'UTF-8'));}
function subjectColor($subject){$s=strtolower($subject??''); if(strpos($s,'english')!==false)return'#3b82f6'; if(strpos($s,'math')!==false)return'#ef4444'; if(strpos($s,'science')!==false)return'#22c55e'; if(strpos($s,'filipino')!==false)return'#f59e0b'; if(strpos($s,'araling')!==false||$s==='ap')return'#8b5cf6'; if(strpos($s,'mapeh')!==false)return'#ec4899'; if(strpos($s,'tle')!==false||strpos($s,'tvl')!==false)return'#14b8a6'; if(strpos($s,'esp')!==false||strpos($s,'gmrc')!==false)return'#6366f1'; return'#dbeafe';}
function formFields(){return ['teacher','school','grade_level','subject','subject_color','plan_type','curriculum','term','week_day','lesson_date','duration','no_sessions','references_used','declaration_ai_use','topic','content_standards','performance_standards','competency','objectives','learner_context','integration','materials','class_profile','pre_lesson','lesson_flow','language_instruction','assessment','remarks','extended_learning','reflection'];}
function dbFields(){return array_merge(formFields(),['lesson_content','ai_prompt']);}
function collect_post(){ $d=[]; foreach(formFields() as $f){$d[$f]=clean($_POST[$f]??'');} if($d['subject_color']=='')$d['subject_color']=subjectColor($d['subject']); if($d['plan_type']=='')$d['plan_type']='DepEd 2026 Appendix A Lesson Plan'; if($d['declaration_ai_use']=='')$d['declaration_ai_use']='AI was used to assist in generating learner context, pre-lesson activities, lesson flow, learning resources, integration opportunities, formative assessment, extended learning opportunities, and reflection prompts. The teacher reviewed, contextualized, validated, and finalized all content before classroom use.'; return $d; }
function fallback($value,$text){return trim($value??'')!==''?$value:$text;}
function buildLessonContent($d){
$assessment=fallback($d['assessment'],'Formative assessment will be generated or finalized by the teacher based on learner responses.');
$extended=fallback($d['extended_learning'],'Extended learning opportunities will be provided based on learner needs and interests.');
$reflection=fallback($d['reflection'],'Teacher will reflect on learner progress, engagement, support needed, and next instructional steps.');
return "
<h2>Appendix A: Lesson Plan Template</h2>
<table class='template-table' border='1' cellpadding='6' style='width:100%;border-collapse:collapse;'>
<tr><th colspan='2'>Lesson Plan Template</th></tr>
<tr><td><b>Name of Lesson</b></td><td>".out($d['topic'])."</td></tr>
<tr><td><b>Learning Area/s</b></td><td>".out($d['subject'])."</td></tr>
<tr><td><b>Designed by Teacher/s</b></td><td>".out($d['teacher'])."</td></tr>
<tr><td><b>Designed for which Grade Level and Section</b></td><td>".out($d['grade_level'])."</td></tr>
<tr><td><b>No. of Sessions</b></td><td>".out($d['no_sessions'])."</td></tr>
<tr><td><b>References</b><br><i>Books, websites, toolkits, etc.</i></td><td>".br($d['references_used'])."</td></tr>
<tr><td><b>Declaration of AI Use</b><br><i>Cite how AI was used in the formulation of the lesson plan.</i></td><td>".br($d['declaration_ai_use'])."</td></tr>
</table><br>
<table class='template-table' border='1' cellpadding='6' style='width:100%;border-collapse:collapse;'>
<tr><th style='width:28%;background:#d9ead3;'>Intentions</th><th>Meaningful learning experiences are anchored in how we frame them. Start by deciding what learners should master by the end of the lesson.</th></tr>
<tr><td><b>Learning Competency</b></td><td><b>Content Standards:</b><br>".br($d['content_standards'])."<br><br><b>Performance Standards:</b><br>".br($d['performance_standards'])."<br><br><b>Learning Competency:</b><br>".br($d['competency'])."</td></tr>
<tr><td><b>Learning Objectives</b></td><td>".br($d['objectives'])."</td></tr>
<tr><td><b>Learner Context</b></td><td>".br($d['learner_context'])."</td></tr>
</table><br>
<table class='template-table' border='1' cellpadding='6' style='width:100%;border-collapse:collapse;'>
<tr><th style='width:28%;background:#d9ead3;'>Learning Experience</th><th>A learning experience is a thoughtfully designed journey. Each activity and interaction builds toward meaningful understanding and growth.</th></tr>
<tr><td><b>Pre-Lesson</b></td><td>".br($d['pre_lesson'])."</td></tr>
<tr><td><b>Flow</b></td><td>".br($d['lesson_flow'])."</td></tr>
<tr><td><b>Learning Resources</b></td><td>".br($d['materials'])."</td></tr>
<tr><td><b>Opportunities for Integration</b></td><td>".br($d['integration'])."</td></tr>
</table><br>
<table class='template-table' border='1' cellpadding='6' style='width:100%;border-collapse:collapse;'>
<tr><th style='width:28%;background:#d9ead3;'>Assessment</th><th>Assessments reveal what learners have gained and what they still need help with.</th></tr>
<tr><td><b>Formative Assessment</b></td><td>".br($assessment)."</td></tr>
</table><br>
<table class='template-table' border='1' cellpadding='6' style='width:100%;border-collapse:collapse;'>
<tr><th style='width:28%;background:#d9ead3;'>Ways Forward</th><th>Meaningful learning can also happen beyond the classroom. Pause and reflect on what happened today.</th></tr>
<tr><td><b>Extended Learning Opportunities</b></td><td>".br($extended)."</td></tr>
<tr><td><b>Reflections</b></td><td>".br($reflection)."</td></tr>
</table><br><br>
<table border='1' cellpadding='6' style='width:100%;border-collapse:collapse;'><tr><td style='width:33%;text-align:center;height:70px;vertical-align:bottom;'><b>Prepared by:</b><br><br>___________________________________<br><b>".out($d['teacher'])."</b><br>Teacher</td><td style='width:33%;text-align:center;height:70px;vertical-align:bottom;'><b>Checked by:</b><br><br>___________________________________<br>Master Teacher / Department Head<br>Date: __________________</td><td style='width:34%;text-align:center;height:70px;vertical-align:bottom;'><b>Approved by:</b><br><br>___________________________________<br>Principal / School Head<br>Date: __________________</td></tr></table>
<p style='font-size:9pt;font-style:italic;'>AI Use Statement: This lesson planner was drafted with the assistance of an AI tool. Official policy and curriculum bases should come from applicable DepEd issuances, curriculum guides, and school/division directions. The teacher reviewed, contextualized, and finalized the lesson plan before classroom use.</p>";}
function buildAiPrompt($d){return "AI generated fields were requested using the OpenAI API based on topic, content standards, performance standards, competency, objectives, grade level, and learning area.";}
?>
