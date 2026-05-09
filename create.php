<?php include "helpers.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Lesson Plan</title>
    <link rel="stylesheet" href="style.css">

    <style>
        :root {
            --blue: #2563eb;
            --blue-dark: #1e3a8a;
            --blue-soft: #eff6ff;
            --green: #16a34a;
            --green-dark: #15803d;
            --yellow-soft: #fefce8;
            --yellow: #ca8a04;
            --gray-bg: #f1f5f9;
            --gray-border: #cbd5e1;
            --text: #0f172a;
            --muted: #64748b;
            --white: #ffffff;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.18), transparent 32%),
                linear-gradient(135deg, #eef2ff 0%, #f8fafc 45%, #e0f2fe 100%);
            color: var(--text);
        }

        .container {
            max-width: 1180px;
            border-radius: 18px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
            border: 1px solid rgba(148, 163, 184, 0.35);
        }

        .page-header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb, #38bdf8);
            color: white;
            padding: 28px 32px;
            position: relative;
        }

        .page-header::after {
            content: "";
            position: absolute;
            right: -60px;
            top: -60px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.13);
            border-radius: 50%;
        }

        .page-header h1 {
            color: white;
            text-align: left;
            margin: 0;
            font-size: 30px;
            letter-spacing: -0.5px;
        }

        .page-header p {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.92);
            font-size: 15px;
        }

        .form-wrapper {
            padding: 28px 32px 34px;
            background: rgba(255, 255, 255, 0.96);
        }

        .section-card {
            background: white;
            border: 1px solid #dbeafe;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 16px;
            color: var(--blue-dark);
            font-size: 20px;
            border-bottom: 1px solid #dbeafe;
            padding-bottom: 10px;
        }

        .section-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--blue-soft);
            color: var(--blue);
            font-weight: bold;
        }

        label {
            color: #1e293b;
            font-size: 14px;
            margin-top: 12px;
        }

        input,
        textarea,
        select {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 11px 12px;
            transition: all 0.2s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        textarea {
            min-height: 105px;
            resize: vertical;
        }

        input[type="color"] {
            height: 44px;
            padding: 5px;
            cursor: pointer;
        }

        .helper-text {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }

        .ai-panel {
            margin-top: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #eff6ff, #fefce8);
            border-radius: 18px;
            border: 1px solid #bfdbfe;
            border-left: 7px solid var(--blue);
            box-shadow: 0 8px 22px rgba(37, 99, 235, 0.10);
        }

        .ai-panel h3 {
            margin: 0 0 8px;
            color: var(--blue-dark);
            font-size: 22px;
        }

        .ai-panel p {
            color: #334155;
            margin-top: 0;
            line-height: 1.5;
        }

        .ai-prompt-box {
            min-height: 300px;
            background: #0f172a;
            color: #e2e8f0;
            font-family: Consolas, "Courier New", monospace;
            font-size: 13px;
            border: 1px solid #334155;
        }

        .ai-prompt-box:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.18);
        }

        .button-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            margin-top: 12px;
        }

        button,
        input[type="submit"],
        a {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        button:hover,
        input[type="submit"]:hover,
        a:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.20);
        }

        .secondary {
            background: var(--green);
        }

        .secondary:hover {
            background: var(--green-dark);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding: 18px 0 0;
            border-top: 1px solid #e2e8f0;
            margin-top: 16px;
        }

        .form-actions input[type="submit"] {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            padding: 12px 18px;
        }

        .back-link {
            background: #64748b;
        }

        .back-link:hover {
            background: #475569;
        }

        .badge-note {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1e3a8a;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .required-mark {
            color: #dc2626;
        }

        @media(max-width: 800px) {
            .page-header {
                padding: 22px;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .form-wrapper {
                padding: 18px;
            }

            .section-card {
                padding: 16px;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            .page-header {
                background: white !important;
                color: black !important;
                padding: 0;
            }

            .page-header h1,
            .page-header p {
                color: black !important;
            }

            .ai-panel,
            .no-print,
            .form-actions {
                display: none !important;
            }

            .container {
                box-shadow: none;
                border: none;
            }

            .form-wrapper {
                padding: 0;
            }

            .section-card {
                box-shadow: none;
                border: 1px solid #000;
                border-radius: 0;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="page-header">
        <span class="badge-note">DepEd 2026 Appendix A</span>
        <h1>Create Lesson Plan</h1>
        <p>Prepare a structured, inclusive, and AI-assisted lesson plan aligned with the DepEd 2026 template.</p>
    </div>

    <div class="form-wrapper">

        <form method="POST" action="save.php">

            <!-- BASIC INFORMATION -->
            <div class="section-card">
                <h2 class="section-title">
                    <span class="section-icon">1</span>
                    Basic Lesson Information
                </h2>

                <div class="grid-2">
                    <div>
                        <label>Teacher Name <span class="required-mark">*</span></label>
                        <input name="teacher" placeholder="Example: Jomar Samuyag" required>
                    </div>

                    <div>
                        <label>School <span class="required-mark">*</span></label>
                        <input name="school" placeholder="Example: Your School Name" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div>
                        <label>Grade Level and Section <span class="required-mark">*</span></label>
                        <input name="grade_level" placeholder="Example: Grade 6 - Rizal" required>
                    </div>

                    <div>
                        <label>Learning Area/s <span class="required-mark">*</span></label>
                        <input name="subject" placeholder="Example: English, Science, Mathematics" required>
                    </div>
                </div>

                <label>Subject Color</label>
                <input type="color" name="subject_color" value="#dbeafe">
                <div class="helper-text">This color will be used in the calendar and subject cards.</div>

                <input type="hidden" name="plan_type" value="DepEd 2026 Appendix A Lesson Plan">
            </div>

            <!-- SCHEDULE -->
            <div class="section-card">
                <h2 class="section-title">
                    <span class="section-icon">2</span>
                    Schedule and Curriculum
                </h2>

                <label>Curriculum <span class="required-mark">*</span></label>
                <select name="curriculum" required>
                    <option value="">Select Curriculum</option>
                    <option value="K–10 MATATAG Curriculum">K–10 MATATAG Curriculum</option>
                    <option value="Strengthened Senior High School Curriculum">Strengthened Senior High School Curriculum</option>
                    <option value="Existing Senior High School Curriculum">Existing Senior High School Curriculum</option>
                </select>

                <div class="grid-2">
                    <div>
                        <label>Term <span class="required-mark">*</span></label>
                        <select name="term" required>
                            <option value="">Select Term</option>
                            <option value="Term 1">Term 1</option>
                            <option value="Term 2">Term 2</option>
                            <option value="Term 3">Term 3</option>
                        </select>
                    </div>

                    <div>
                        <label>Week / Day <span class="required-mark">*</span></label>
                        <input name="week_day" placeholder="Example: Week 1 - Day 1" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div>
                        <label>Lesson Date <span class="required-mark">*</span></label>
                        <input type="date" name="lesson_date" required>
                    </div>

                    <div>
                        <label>Duration <span class="required-mark">*</span></label>
                        <input name="duration" placeholder="Example: 60 minutes" required>
                    </div>
                </div>

                <label>No. of Sessions <span class="required-mark">*</span></label>
                <input name="no_sessions" placeholder="Example: 1 session / 2 sessions" required>
            </div>

            <!-- REFERENCES AND TOPIC -->
            <div class="section-card">
                <h2 class="section-title">
                    <span class="section-icon">3</span>
                    Lesson Details and References
                </h2>

                <label>References</label>
                <textarea name="references_used" placeholder="Books, websites, toolkits, DepEd CGs, modules, etc."></textarea>

                <label>Declaration of AI Use</label>
                <textarea name="declaration_ai_use">AI was used to assist in generating learner context, pre-lesson activities, lesson flow, learning resources, integration opportunities, formative assessment, extended learning opportunities, and reflection prompts. The teacher reviewed, contextualized, validated, and finalized all content before classroom use.</textarea>

                <label>Name of Lesson <span class="required-mark">*</span></label>
                <input name="topic" placeholder="Example: Identifying Main Idea and Supporting Details" required>
            </div>

            <!-- INTENTIONS -->
            <div class="section-card">
                <h2 class="section-title">
                    <span class="section-icon">4</span>
                    Intentions
                </h2>

                <label>Content Standards</label>
                <textarea name="content_standards" placeholder="Write the applicable content standard."></textarea>

                <label>Performance Standards</label>
                <textarea name="performance_standards" placeholder="Write the applicable performance standard."></textarea>

                <label>Learning Competency <span class="required-mark">*</span></label>
                <textarea name="competency" placeholder="Write the learning competency from the curriculum." required></textarea>

                <label>Learning Objectives <span class="required-mark">*</span></label>
                <textarea name="objectives" placeholder="Write the learning objectives." required></textarea>
            </div>

            <!-- AI GENERATOR -->
            <div class="ai-panel no-print">
                <h3>ChatGPT AI Generator</h3>
                <p>
                    Click this button after filling the basic lesson details.
                    Copy the prompt to ChatGPT, then paste the generated answers in the fields below.
                </p>

                <div class="button-row">
                    <button type="button" class="secondary" onclick="generateAIPrompt()">Generate ChatGPT AI Prompt</button>
                    <button type="button" onclick="copyAIPrompt()">Copy AI Prompt</button>
                </div>

                <label>ChatGPT AI Prompt</label>
                <textarea id="ai_generated_prompt" class="ai-prompt-box"></textarea>
            </div>

            <!-- AI GENERATED FIELDS -->
            <div class="section-card">
                <h2 class="section-title">
                    <span class="section-icon">5</span>
                    Learner Context and Learning Experience
                </h2>

                <label>Learner Context - AI Generated</label>
                <textarea name="learner_context" placeholder="Paste ChatGPT-generated learner context here."></textarea>

                <label>Pre-Lesson - AI Generated</label>
                <textarea name="pre_lesson" placeholder="Paste ChatGPT-generated pre-lesson here."></textarea>

                <label>Flow - AI Generated</label>
                <textarea name="lesson_flow" placeholder="Paste ChatGPT-generated lesson flow here."></textarea>

                <label>Learning Resources - AI Generated <span class="required-mark">*</span></label>
                <textarea name="materials" required placeholder="Paste ChatGPT-generated learning resources here."></textarea>

                <label>Opportunities for Integration - AI Generated</label>
                <textarea name="integration" placeholder="Paste ChatGPT-generated integration here."></textarea>
            </div>

            <!-- PROFILE AND ASSESSMENT -->
            <div class="section-card">
                <h2 class="section-title">
                    <span class="section-icon">6</span>
                    Assessment and Ways Forward
                </h2>

                <label>Class Profile / Notes - AI Generated <span class="required-mark">*</span></label>
                <textarea name="class_profile" required placeholder="Paste ChatGPT-generated class profile here."></textarea>

                <label>Language of Instruction - AI Suggested <span class="required-mark">*</span></label>
                <select name="language_instruction" required>
                    <option value="">Select Language</option>
                    <option value="English">English</option>
                    <option value="Filipino">Filipino</option>
                    <option value="Mother Tongue">Mother Tongue</option>
                    <option value="Bilingual">Bilingual</option>
                </select>

                <label>Formative Assessment - AI Generated</label>
                <textarea name="assessment" placeholder="Paste ChatGPT-generated formative assessment here."></textarea>

                <label>Remarks - AI Generated</label>
                <textarea name="remarks" placeholder="Paste ChatGPT-generated remarks here."></textarea>

                <label>Extended Learning Opportunities - AI Generated</label>
                <textarea name="extended_learning" placeholder="Paste ChatGPT-generated extended learning opportunities here."></textarea>

                <label>Reflections - AI Generated</label>
                <textarea name="reflection" placeholder="Paste ChatGPT-generated reflections here."></textarea>
            </div>

            <div class="form-actions">
                <input type="submit" value="Generate and Save">
                <a href="index.php" class="back-link">Back</a>
            </div>

        </form>

    </div>
</div>

<script>
function getValue(name) {
    let field = document.querySelector(`[name="${name}"]`);
    return field ? field.value : "";
}

function generateAIPrompt() {
    let prompt = `You are ChatGPT acting as an AI lesson planning assistant for a Philippine teacher.

Generate and fill in the following DepEd 2026 Appendix A Lesson Plan Template fields:

1. Learner Context
- Include learner strengths, interests, recent performance, barriers to learning, and support needed.

2. Pre-Lesson
- Describe how learners will get ready for the lesson.

3. Flow
- Describe lesson activities, interactions, collaboration, inclusion strategies, checking for understanding, and reflection prompts.

4. Learning Resources
- List appropriate learning resources that are available, inclusive, and practical.
- Include alternatives in case of limited materials or emergencies.

5. Opportunities for Integration
- Include possible integration of other learning areas, special topics, technology, values, literacy, numeracy, and real-life application.
- Write N/A if not applicable.

6. Class Profile / Notes
- Create a practical class profile based on the lesson context.
- Include learners needing support and learners needing enrichment.

7. Language of Instruction
- Recommend the most appropriate language of instruction.

8. Formative Assessment
- Create a task, activity, or questions to evaluate learning.
- Include feedback and learner support options.
- Include accommodations for varied learners.

9. Remarks
- Provide teacher-ready remarks section.

10. Extended Learning Opportunities
- Suggest learning experiences outside classroom hours to reinforce learning or support learners.

11. Reflections
- Provide teacher reflection prompts for next-session planning.

Use formal, teacher-ready language. Make the output contextualized, inclusive, age-appropriate, and aligned with DepEd lesson planning. Do not invent official policy.

Lesson Details:
Teacher: ${getValue("teacher")}
School: ${getValue("school")}
Grade Level and Section: ${getValue("grade_level")}
Learning Area/s: ${getValue("subject")}
Curriculum: ${getValue("curriculum")}
Term: ${getValue("term")}
Week / Day: ${getValue("week_day")}
Lesson Date: ${getValue("lesson_date")}
Duration: ${getValue("duration")}
No. of Sessions: ${getValue("no_sessions")}
Name of Lesson: ${getValue("topic")}
Content Standards: ${getValue("content_standards")}
Performance Standards: ${getValue("performance_standards")}
Learning Competency: ${getValue("competency")}
Learning Objectives: ${getValue("objectives")}
References: ${getValue("references_used")}
Declaration of AI Use: ${getValue("declaration_ai_use")}

Return the answer using this exact format:

Learner Context:
[answer]

Pre-Lesson:
[answer]

Flow:
[answer]

Learning Resources:
[answer]

Opportunities for Integration:
[answer]

Class Profile / Notes:
[answer]

Language of Instruction:
[answer]

Formative Assessment:
[answer]

Remarks:
[answer]

Extended Learning Opportunities:
[answer]

Reflections:
[answer]`;

    document.getElementById("ai_generated_prompt").value = prompt;
}

function copyAIPrompt() {
    let box = document.getElementById("ai_generated_prompt");

    if (!box.value.trim()) {
        alert("Please generate the AI prompt first.");
        return;
    }

    box.select();
    box.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(box.value);

    alert("ChatGPT AI prompt copied!");
}
</script>

</body>
</html>