<?php
// Optional login protection if auth.php exists
if (file_exists("auth.php")) {
    require_once "auth.php";
    require_login();
}

include "db.php";
include "helpers.php";

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM lesson_plans WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();

$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    die('Lesson plan not found.');
}

$filename = 'lesson_plan_' . $row['id'] . '_DepEd2026_A4.xls';

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF";

/*
|--------------------------------------------------------------------------
| Output helpers
|--------------------------------------------------------------------------
| wrap_text() adds soft break opportunities to long URLs and long words.
| This prevents Excel HTML tables from stretching beyond A4 portrait width.
|--------------------------------------------------------------------------
*/

function cell_out($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function wrap_text($value) {
    $text = htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');

    // Add zero-width spaces after URL/path separators and common long-text symbols.
    $text = preg_replace('/([\/\\\\\?\&\=\#\.\-\_\:\%\+])/', '$1&#8203;', $text);

    // Add soft break every 35 continuous non-space characters.
    $text = preg_replace('/([^\s]{35})/', '$1&#8203;', $text);

    return nl2br($text);
}

function cell($value) {
    return wrap_text($value ?? '');
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>DepEd 2026 Lesson Plan</title>

<style>
@page {
    size: 8.27in 11.69in; /* A4 Portrait */
    margin: 0.35in 0.35in 0.35in 0.35in;
    mso-page-orientation: portrait;
    mso-horizontal-page-align: center;
    mso-vertical-page-align: top;
}

html,
body {
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    font-size: 8.7pt;
    line-height: 1.05;
    color: #000;
}

table {
    width: 100%;
    max-width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin: 0;
    padding: 0;
}

td,
th {
    border: 1px solid #000;
    padding: 3px;
    vertical-align: top;
    word-wrap: break-word;
    overflow-wrap: break-word;
    mso-number-format: "\@";
    mso-line-height-rule: exactly;
    mso-padding-alt: 3px 3px 3px 3px;
}

p {
    margin: 0;
    padding: 0;
}

br {
    line-height: 1.05;
}

.no-border td,
.no-border th {
    border: none;
}

.center {
    text-align: center;
}

.bold {
    font-weight: bold;
}

.subtitle {
    font-size: 8.7pt;
    text-align: center;
    line-height: 1.05;
}

.section-header {
    background: #d9ead3;
    font-weight: bold;
    width: 22%;
}

.section-desc {
    font-style: italic;
    font-size: 7pt;
    line-height: 1.0;
}

.label {
    width: 22%;
    font-weight: bold;
    background: #f8fafc;
    font-size: 8.3pt;
}

.value-cell {
    width: 78%;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.references-cell {
    font-size: 7.6pt;
    line-height: 1.0;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.ai-cell {
    font-size: 7.8pt;
    line-height: 1.0;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.signature-table td {
    height: 50px;
    text-align: center;
    vertical-align: bottom;
    font-size: 8pt;
}

.small-note {
    font-size: 7pt;
    font-style: italic;
    line-height: 1.0;
}

.keep-together {
    page-break-inside: avoid;
}

.header-logo {
    width: 13%;
    font-size: 7.5pt;
}

.header-center {
    width: 74%;
}

.appendix-left {
    width: 23%;
}

.appendix-mid {
    width: 54%;
}

.appendix-right {
    width: 23%;
}
</style>
</head>

<body>

<!-- DepEd Header -->
<table class="no-border keep-together">
    <colgroup>
        <col style="width:13%;">
        <col style="width:74%;">
        <col style="width:13%;">
    </colgroup>

    <tr>
        <td class="center header-logo">
            DepEd<br>Logo
        </td>

        <td class="center header-center">
            Republic of the Philippines<br>
            <strong>Department of Education</strong><br>
            Region: ________________________________<br>
            Schools Division Office: ________________________________<br>
            <strong><?php echo cell_out($row['school']); ?></strong>
        </td>

        <td class="center header-logo">
            School<br>Logo
        </td>
    </tr>
</table>

<table class="keep-together">
    <colgroup>
        <col class="appendix-left">
        <col class="appendix-mid">
        <col class="appendix-right">
    </colgroup>

    <tr>
        <th>Appendix A</th>
        <th>Lesson Plan Template</th>
        <th></th>
    </tr>
</table>

<!-- Basic Lesson Information -->
<table>
    <colgroup>
        <col style="width:22%;">
        <col style="width:78%;">
    </colgroup>

    <tr>
        <td class="label"><i>Name of Lesson</i></td>
        <td class="value-cell"><?php echo cell_out($row['topic']); ?></td>
    </tr>

    <tr>
        <td class="label"><i>Learning Area/s</i></td>
        <td class="value-cell"><?php echo cell_out($row['subject']); ?></td>
    </tr>

    <tr>
        <td class="label"><i>Designed by Teacher/s</i></td>
        <td class="value-cell"><?php echo cell_out($row['teacher']); ?></td>
    </tr>

    <tr>
        <td class="label"><i>Designed for which Grade Level and Section</i></td>
        <td class="value-cell"><?php echo cell_out($row['grade_level']); ?></td>
    </tr>

    <tr>
        <td class="label"><i>No. of Sessions</i></td>
        <td class="value-cell"><?php echo cell_out($row['no_sessions']); ?></td>
    </tr>

    <tr>
        <td class="label">
            <i>References</i><br>
            <span class="section-desc">(books, websites, toolkits, etc.)</span>
        </td>
        <td class="references-cell"><?php echo cell($row['references_used']); ?></td>
    </tr>

    <tr>
        <td class="label">
            <i>Declaration of AI Use</i><br>
            <span class="section-desc">
                Cite how AI was used in the formulation of the lesson plan.
            </span>
        </td>
        <td class="ai-cell"><?php echo cell($row['declaration_ai_use']); ?></td>
    </tr>
</table>

<!-- Intentions -->
<table>
    <colgroup>
        <col style="width:22%;">
        <col style="width:78%;">
    </colgroup>

    <tr>
        <th class="section-header">Intentions.</th>
        <th>
            Meaningful learning experiences are anchored in how we frame them.
            Start by deciding what learners should master by the end of the lesson.
            Understanding learners' context helps ensure lessons are relevant.
        </th>
    </tr>

    <tr>
        <td class="label">
            <i>Learning Competency:</i><br>
            <span class="section-desc">
                Include targeted competency and applicable content/performance standards.
            </span>
        </td>

        <td class="value-cell">
            <strong>Content Standards:</strong><br>
            <?php echo cell($row['content_standards']); ?>

            <br>

            <strong>Performance Standards:</strong><br>
            <?php echo cell($row['performance_standards']); ?>

            <br>

            <strong>Learning Competency:</strong><br>
            <?php echo cell($row['competency']); ?>
        </td>
    </tr>

    <tr>
        <td class="label">
            <i>Learning Objectives:</i><br>
            <span class="section-desc">
                Smaller knowledge, skills, or tasks learners should show by the end.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['objectives']); ?></td>
    </tr>

    <tr>
        <td class="label">
            <i>Learner Context:</i><br>
            <span class="section-desc">
                Include strengths, interests, recent performance, and possible barriers.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['learner_context']); ?></td>
    </tr>
</table>

<!-- Learning Experience -->
<table>
    <colgroup>
        <col style="width:22%;">
        <col style="width:78%;">
    </colgroup>

    <tr>
        <th class="section-header">Learning Experience.</th>
        <th>
            A learning experience is a thoughtfully designed journey.
            Each activity and interaction builds toward meaningful understanding and growth.
        </th>
    </tr>

    <tr>
        <td class="label">
            <i>Pre-Lesson:</i><br>
            <span class="section-desc">
                Describe how learners will get ready for the lesson.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['pre_lesson']); ?></td>
    </tr>

    <tr>
        <td class="label">
            <i>Flow:</i><br>
            <span class="section-desc">
                Activities, guidance, checks, collaboration, reflection, and inclusion.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['lesson_flow']); ?></td>
    </tr>

    <tr>
        <td class="label">
            <i>Learning Resources:</i><br>
            <span class="section-desc">
                Available resources and alternatives.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['materials']); ?></td>
    </tr>

    <tr>
        <td class="label">
            <i>Opportunities for Integration:</i><br>
            <span class="section-desc">
                Learning areas, values, tech, literacy, numeracy, or N/A.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['integration']); ?></td>
    </tr>
</table>

<!-- Assessment -->
<table>
    <colgroup>
        <col style="width:22%;">
        <col style="width:78%;">
    </colgroup>

    <tr>
        <th class="section-header">Assessment.</th>
        <th>
            Assessments reveal what learners gained and what they still need help with.
        </th>
    </tr>

    <tr>
        <td class="label">
            <i>Formative Assessment:</i><br>
            <span class="section-desc">
                Task, activity, or questions with feedback and support.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['assessment']); ?></td>
    </tr>
</table>

<!-- Ways Forward -->
<table>
    <colgroup>
        <col style="width:22%;">
        <col style="width:78%;">
    </colgroup>

    <tr>
        <th class="section-header">Ways Forward.</th>
        <th>
            Meaningful learning can happen beyond the classroom.
            Pause and reflect on what happened today.
        </th>
    </tr>

    <tr>
        <td class="label">
            <i>Extended Learning Opportunities:</i><br>
            <span class="section-desc">
                Learning experiences outside class hours.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['extended_learning']); ?></td>
    </tr>

    <tr>
        <td class="label">
            <i>Reflections:</i><br>
            <span class="section-desc">
                Reflect on changes needed for the next session.
            </span>
        </td>
        <td class="value-cell"><?php echo cell($row['reflection']); ?></td>
    </tr>
</table>

<!-- Signatures -->
<table class="signature-table keep-together">
    <colgroup>
        <col style="width:33%;">
        <col style="width:33%;">
        <col style="width:34%;">
    </colgroup>

    <tr>
        <td>
            <strong>Prepared by:</strong><br><br>
            ___________________________________<br>
            <strong><?php echo cell_out($row['teacher']); ?></strong><br>
            Teacher
        </td>

        <td>
            <strong>Checked by:</strong><br><br>
            ___________________________________<br>
            Master Teacher / Department Head<br>
            Date: __________________
        </td>

        <td>
            <strong>Approved by:</strong><br><br>
            ___________________________________<br>
            Principal / School Head<br>
            Date: __________________
        </td>
    </tr>
</table>

<p class="small-note">
AI Use Statement: This lesson planner was drafted with the assistance of an AI tool.
Official policy and curriculum bases should come from applicable DepEd issuances,
curriculum guides, and school/division directions. The teacher reviewed, contextualized,
and finalized the lesson plan before classroom use.
</p>

</body>
</html>