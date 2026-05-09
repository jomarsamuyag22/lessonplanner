<?php
// Optional login protection if auth.php exists
if (file_exists("auth.php")) {
    require_once "auth.php";
    require_login();
} else {
    include "db.php";
}

if (!isset($conn)) {
    include "db.php";
}

$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

$month = str_pad(intval($month), 2, '0', STR_PAD_LEFT);
$year = intval($year);

$first = "$year-$month-01";
$days = date('t', strtotime($first));
$start = date('w', strtotime($first));
$name = date('F Y', strtotime($first));

$prevMonth = date('m', strtotime("$first -1 month"));
$prevYear = date('Y', strtotime("$first -1 month"));
$nextMonth = date('m', strtotime("$first +1 month"));
$nextYear = date('Y', strtotime("$first +1 month"));

$stmt = $conn->prepare("
    SELECT * FROM lesson_plans 
    WHERE lesson_date IS NOT NULL 
    AND MONTH(lesson_date)=? 
    AND YEAR(lesson_date)=? 
    ORDER BY lesson_date, subject, topic
");

$mi = intval($month);
$yi = intval($year);

$stmt->bind_param('ii', $mi, $yi);
$stmt->execute();

$res = $stmt->get_result();

$by = [];
$subjects = [];
$weekDaySummary = [];

while ($r = $res->fetch_assoc()) {
    $by[$r['lesson_date']][] = $r;

    $subject = trim($r['subject'] ?? 'Subject');
    $subjects[$subject] = $r['subject_color'] ?: '#dbeafe';

    $weekDay = trim($r['week_day'] ?? 'Unspecified Week/Day');

    if (!isset($weekDaySummary[$weekDay])) {
        $weekDaySummary[$weekDay] = [];
    }

    $weekDaySummary[$weekDay][] = $r;
}

function h($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function short_text($text, $limit = 120) {
    $text = trim(strip_tags($text ?? ''));

    if ($text === '') {
        return 'Not specified';
    }

    if (strlen($text) <= $limit) {
        return $text;
    }

    return substr($text, 0, $limit) . '...';
}

function get_week_of_month($day, $start) {
    return ceil(($day + $start) / 7);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lesson Calendar</title>
<link rel="stylesheet" href="style.css">

<style>
body {
    background: #eef2ff;
}

.calendar-page {
    max-width: 1400px;
}

.calendar-header {
    background: linear-gradient(135deg, #1d4ed8, #2563eb, #38bdf8);
    color: white;
    padding: 22px;
    border-radius: 16px;
    margin-bottom: 18px;
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.25);
}

.calendar-header h1 {
    color: white;
    margin: 0;
    text-align: left;
    font-size: 30px;
}

.calendar-header p {
    margin: 6px 0 0;
    opacity: 0.95;
}

.calendar-actions {
    margin-top: 14px;
}

.calendar-actions a,
.calendar-actions button,
.calendar-actions input[type=submit] {
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.45);
    color: white;
}

.calendar-actions a:hover,
.calendar-actions button:hover,
.calendar-actions input[type=submit]:hover {
    background: rgba(255,255,255,0.28);
}

.filter-panel {
    background: white;
    padding: 15px;
    border-radius: 14px;
    border: 1px solid #dbeafe;
    margin-bottom: 18px;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 12px;
    align-items: end;
}

.legend {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}

.legend-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 999px;
    padding: 5px 10px;
    font-size: 12px;
}

.legend-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 1px solid #64748b;
}

.summary-panel {
    background: #ffffff;
    border: 1px solid #dbeafe;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 18px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 12px;
}

.summary-card {
    border: 1px solid #cbd5e1;
    border-left: 6px solid #2563eb;
    border-radius: 12px;
    padding: 12px;
    background: #f8fafc;
}

.summary-card h4 {
    margin: 0 0 8px;
    color: #1e3a8a;
}

.summary-item {
    margin-bottom: 8px;
    padding-bottom: 8px;
    border-bottom: 1px dashed #cbd5e1;
    font-size: 12px;
}

.summary-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.calendar-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 8px;
    margin-top: 0;
}

.calendar-table th {
    background: #1e3a8a;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px;
    text-align: center;
}

.calendar-table td {
    background: white;
    border: 1px solid #dbeafe;
    border-radius: 14px;
    padding: 8px;
    height: 190px;
    vertical-align: top;
    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.08);
    overflow: hidden;
}

.calendar-table td.empty {
    background: #f1f5f9;
    box-shadow: none;
}

.day-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.day-number {
    font-weight: bold;
    color: #1e3a8a;
    background: #dbeafe;
    border-radius: 999px;
    padding: 4px 8px;
    font-size: 13px;
}

.week-badge {
    font-size: 10px;
    color: #475569;
    background: #f1f5f9;
    border-radius: 999px;
    padding: 3px 7px;
}

.lesson-card {
    border-radius: 10px;
    margin: 6px 0;
    padding: 7px;
    font-size: 11px;
    color: #111827;
    border-left: 6px solid #1e3a8a;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.12);
    overflow-wrap: break-word;
}

.lesson-card a {
    background: none;
    color: #0f172a;
    padding: 0;
    margin: 0;
    font-weight: bold;
    text-decoration: none;
}

.lesson-card a:hover {
    text-decoration: underline;
}

.lesson-meta {
    font-size: 10px;
    color: #334155;
    margin-top: 3px;
}

.lesson-section {
    margin-top: 5px;
    background: rgba(255,255,255,0.65);
    border-radius: 6px;
    padding: 5px;
}

.lesson-section strong {
    color: #1e3a8a;
}

.no-lesson {
    color: #94a3b8;
    font-size: 11px;
    margin-top: 35px;
    text-align: center;
}

@media print {
    body {
        background: white;
        padding: 0;
    }

    .container {
        box-shadow: none;
        max-width: 100%;
        padding: 0;
    }

    .calendar-header {
        background: white !important;
        color: black !important;
        box-shadow: none;
        border: 1px solid #000;
    }

    .calendar-header h1 {
        color: black !important;
    }

    .filter-panel,
    .calendar-actions,
    .summary-panel {
        display: none !important;
    }

    .calendar-table {
        border-spacing: 2px;
        font-size: 9px;
    }

    .calendar-table td {
        height: 145px;
        box-shadow: none;
        border: 1px solid #000;
        border-radius: 0;
        padding: 3px;
    }

    .calendar-table th {
        background: #ddd !important;
        color: #000 !important;
        border: 1px solid #000;
        border-radius: 0;
    }

    .lesson-card {
        box-shadow: none;
        border: 1px solid #000;
        border-left: 4px solid #000;
        padding: 3px;
        font-size: 8px;
    }

    .lesson-section {
        padding: 2px;
    }
}

@media(max-width: 900px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }

    .calendar-table {
        border-spacing: 4px;
        font-size: 11px;
    }

    .calendar-table td {
        height: 170px;
        padding: 5px;
    }

    .lesson-card {
        font-size: 10px;
    }
}
</style>
</head>

<body>
<div class="container calendar-page">

<?php
if (function_exists("security_nav")) {
    echo security_nav();
}
?>

<div class="calendar-header">
    <h1>Lesson Calendar - <?php echo h($name); ?></h1>
    <p>Monthly view of topics, learning competencies, and formative assessments by week and day.</p>

    <div class="calendar-actions no-print">
        <a href="index.php">Back</a>
        <a href="create.php">Create Lesson Plan</a>
        <a href="calendar.php?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>">Previous Month</a>
        <a href="calendar.php?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>">Next Month</a>
        <button onclick="window.print()">Print Calendar</button>
    </div>
</div>

<div class="filter-panel no-print">
    <form method="GET">
        <div class="filter-grid">
            <div>
                <label>Month</label>
                <input type="number" min="1" max="12" name="month" value="<?php echo intval($month); ?>">
            </div>

            <div>
                <label>Year</label>
                <input type="number" name="year" value="<?php echo h($year); ?>">
            </div>

            <div>
                <input type="submit" value="Go">
            </div>
        </div>
    </form>

    <?php if (!empty($subjects)): ?>
        <div class="legend">
            <?php foreach ($subjects as $subject => $color): ?>
                <span class="legend-item">
                    <span class="legend-dot" style="background:<?php echo h($color); ?>"></span>
                    <?php echo h($subject); ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($weekDaySummary)): ?>
<div class="summary-panel no-print">
    <h2>Auto-Generated Learning Competencies by Week / Day</h2>
    <div class="summary-grid">
        <?php foreach ($weekDaySummary as $weekDay => $items): ?>
            <div class="summary-card">
                <h4><?php echo h($weekDay); ?></h4>

                <?php foreach ($items as $item): ?>
                    <div class="summary-item">
                        <strong><?php echo h($item['lesson_date']); ?></strong><br>
                        <span style="color:<?php echo h($item['subject_color'] ?: '#1e3a8a'); ?>; font-weight:bold;">
                            <?php echo h($item['subject']); ?>
                        </span>
                        —
                        <?php echo h($item['topic']); ?>
                        <br>
                        <strong>Competency:</strong>
                        <?php echo h(short_text($item['competency'], 150)); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<table class="calendar-table">
    <tr>
        <th>Sun</th>
        <th>Mon</th>
        <th>Tue</th>
        <th>Wed</th>
        <th>Thu</th>
        <th>Fri</th>
        <th>Sat</th>
    </tr>

    <tr>
        <?php
        for ($b = 0; $b < $start; $b++) {
            echo "<td class='empty'></td>";
        }

        $dow = $start;

        for ($d = 1; $d <= $days; $d++) {
            $date = "$year-$month-" . str_pad($d, 2, '0', STR_PAD_LEFT);
            $weekNumber = get_week_of_month($d, $start);

            echo "<td>";
            echo "<div class='day-header'>";
            echo "<span class='day-number'>$d</span>";
            echo "<span class='week-badge'>Week $weekNumber</span>";
            echo "</div>";

            if (isset($by[$date])) {
                foreach ($by[$date] as $l) {
                    $color = h($l['subject_color'] ?: '#dbeafe');
                    $borderColor = h($l['subject_color'] ?: '#1e3a8a');

                    echo "<div class='lesson-card' style='background:$color; border-left-color:$borderColor;'>";

                    echo "<a href='view.php?id=" . intval($l['id']) . "'>";
                    echo h($l['topic']);
                    echo "</a>";

                    echo "<div class='lesson-meta'>";
                    echo h($l['subject']);
                    echo " | ";
                    echo h($l['week_day']);
                    echo "</div>";

                    echo "<div class='lesson-section'>";
                    echo "<strong>Competency:</strong><br>";
                    echo h(short_text($l['competency'], 110));
                    echo "</div>";

                    echo "<div class='lesson-section'>";
                    echo "<strong>Assessment:</strong><br>";
                    echo h(short_text($l['assessment'], 100));
                    echo "</div>";

                    echo "</div>";
                }
            } else {
                echo "<div class='no-lesson'>No lesson plan</div>";
            }

            echo "</td>";

            $dow++;

            if ($dow == 7 && $d != $days) {
                echo "</tr><tr>";
                $dow = 0;
            }
        }

        while ($dow > 0 && $dow < 7) {
            echo "<td class='empty'></td>";
            $dow++;
        }
        ?>
    </tr>
</table>

</div>
</body>
</html>