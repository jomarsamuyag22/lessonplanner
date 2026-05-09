<?php
header('Content-Type: application/json; charset=utf-8');
include "api_config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!defined('OPENAI_API_KEY') || OPENAI_API_KEY === 'PASTE_YOUR_OPENAI_API_KEY_HERE' || trim(OPENAI_API_KEY) === '') {
    http_response_code(400);
    echo json_encode(['error' => 'OpenAI API key is not set. Edit api_config.php first.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON request']);
    exit;
}

function v($arr, $key) { return trim($arr[$key] ?? ''); }

$prompt = "You are ChatGPT acting as a humanized Philippine DepEd lesson planning assistant.\n\n" .
"Generate concise, teacher-ready, humanized, contextualized, inclusive content for the DepEd 2026 Appendix A Lesson Plan Template. Base everything on the lesson topic, content standards, performance standards, learning competency, objectives, grade level, learning area, and references.\n\n" .
"Return ONLY valid JSON with these exact keys:\n" .
"learner_context, pre_lesson, lesson_flow, materials, integration, class_profile, language_instruction, assessment, remarks, extended_learning, reflection.\n\n" .
"Requirements:\n" .
"- Learner Context: strengths, interests, recent performance, barriers to learning, and support needed.\n" .
"- Pre-Lesson: how learners get ready for the lesson.\n" .
"- Flow: activities, interactions, collaboration, inclusion strategies, checking for understanding, and reflection prompts.\n" .
"- Learning Resources: practical, available, inclusive resources and alternatives for limited materials/emergencies.\n" .
"- Opportunities for Integration: other learning areas, special topics, technology, values, literacy, numeracy, real-life application, or N/A if none.\n" .
"- Class Profile / Notes: practical class profile, learners needing support, learners needing enrichment.\n" .
"- Language of Instruction: one of English, Filipino, Mother Tongue, or Bilingual.\n" .
"- Formative Assessment: task/activity/questions, feedback, learner support options, accommodations.\n" .
"- Remarks: teacher-ready remarks.\n" .
"- Extended Learning Opportunities: beyond classroom learning.\n" .
"- Reflections: teacher reflection prompts for next-session planning.\n\n" .
"Lesson Details:\n" .
"Teacher: " . v($input,'teacher') . "\n" .
"School: " . v($input,'school') . "\n" .
"Grade Level and Section: " . v($input,'grade_level') . "\n" .
"Learning Area/s: " . v($input,'subject') . "\n" .
"Curriculum: " . v($input,'curriculum') . "\n" .
"Term: " . v($input,'term') . "\n" .
"Week/Day: " . v($input,'week_day') . "\n" .
"Lesson Date: " . v($input,'lesson_date') . "\n" .
"Duration: " . v($input,'duration') . "\n" .
"No. of Sessions: " . v($input,'no_sessions') . "\n" .
"Name of Lesson / Topic: " . v($input,'topic') . "\n" .
"Content Standards: " . v($input,'content_standards') . "\n" .
"Performance Standards: " . v($input,'performance_standards') . "\n" .
"Learning Competency: " . v($input,'competency') . "\n" .
"Learning Objectives: " . v($input,'objectives') . "\n" .
"References: " . v($input,'references_used') . "\n";

$payload = [
    'model' => defined('OPENAI_MODEL') ? OPENAI_MODEL : 'gpt-4o-mini',
    'response_format' => ['type' => 'json_object'],
    'messages' => [
        ['role' => 'system', 'content' => 'You output only valid JSON. No markdown. No extra commentary.'],
        ['role' => 'user', 'content' => $prompt]
    ],
    'temperature' => 0.7
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . OPENAI_API_KEY
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL error: ' . $curlError]);
    exit;
}

$data = json_decode($response, true);
if ($status < 200 || $status >= 300) {
    http_response_code($status);
    echo json_encode(['error' => $data['error']['message'] ?? 'OpenAI API request failed', 'raw' => $data]);
    exit;
}

$content = $data['choices'][0]['message']['content'] ?? '';
$generated = json_decode($content, true);

if (!$generated) {
    http_response_code(500);
    echo json_encode(['error' => 'AI response was not valid JSON', 'raw' => $content]);
    exit;
}

echo json_encode(['success' => true, 'data' => $generated]);
?>
