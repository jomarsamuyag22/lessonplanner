# DepEd 2026 Lesson Planner with Real ChatGPT Auto-Fill

## What this version does
- Uses your uploaded DepEd 2026 Appendix A lesson plan format.
- Adds real OpenAI API integration through PHP.
- Includes a Generate with AI button in create.php.
- Auto-fills:
  - Learner Context
  - Pre-Lesson
  - Flow
  - Learning Resources
  - Opportunities for Integration
  - Class Profile / Notes
  - Language of Instruction
  - Formative Assessment
  - Remarks
  - Extended Learning Opportunities
  - Reflections

## Setup
1. Extract ZIP.
2. Rename folder to lessonplanner.
3. Copy to C:\xampp\htdocs\lessonplanner.
4. Open api_config.php and paste your OpenAI API key.
5. Start Apache and MySQL.
6. Import database.sql in phpMyAdmin.
7. Open http://localhost/lessonplanner/index.php.

## Updating old database
Run migration_deped2026_real_chatgpt.sql in phpMyAdmin.

## Security reminder
Do not upload api_config.php publicly with your real API key.
