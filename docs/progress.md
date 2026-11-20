# Project Progress

> Loaded into every session via CLAUDE.md — keep it short and current.
> Edit module sections in place. Session log: last 10 entries only (git has the rest).
> Cross-module connections: see "Dependencies map" in CLAUDE.md.

---

## Current status
- **Working on:** Typing speed test (`TypingTestModule`) + first client feedback round on it and on the student exam flow — all done, verified (scratch feature tests, 7 scenarios / 155 assertions, run on in-memory SQLite) and **staged, not committed**. Migrations already ran on the local MySQL DB.
- **Next up:**
  - Add at least one text to the typing bank (students can't start the typing test while it's empty).
  - Client decisions pending: fix the missing `login` route redirect (see Known issues)? remove the duplicate "سؤال X من N" inside each question card on the questions page? is `national_id` 10 digits (controller) or 14 (`StudentRequest`/column)?
  - Clean up duplicate-case paths in git (`App/` vs `app/`) before deploying to Linux.
- **Known issues:**
  - `StudentExamController::submitExam` / `storeQuestionAnswers` are dead code (no route; `user_answer` column doesn't exist).
  - `CheckPermission` is registered only in `app/Http/Kernel.php`, which Laravel 12 (`bootstrap/app.php`) does not load → `permission` middleware is not active, and it is used on no route.
  - `PermissionSeederTableSeeder` inserts permissions without `guard_name` (NOT NULL, no default) — may fail in strict MySQL (?).
  - Lowercase `app\` in many `use` statements and `config/auth.php` providers — will break on case-sensitive (Linux) servers unless the optimized classmap covers it (?).
  - `national_id` is required on create/edit (since 2026-11-20) but rules are inconsistent: `digits:10` in controller, `digits:14` in unused `StudentRequest`, column is nullable `string(14)`.
  - No real tests.
  - No route named `login`: opening a protected URL without the right guard throws `RouteNotFoundException` (500) instead of redirecting (`bootstrap/app.php` has no `redirectGuestsTo`).
  - Git index tracks every module file twice (`Modules/*/App/...` and `Modules/*/app/...`) — on Linux checkouts these become two diverging copies. Needs a cleanup (`git rm --cached` the lowercase paths) (?).
  - Shared `layouts/flash` shows only `success` + validation errors; `with('error', ...)` used across controllers is never displayed.

---

## Modules

### LayoutModule
- **Last updated:** 2026-11-20
- **Files:** `Modules/LayoutModule/resources/views/layouts/{main,home_layout,flash,header,footer,custom_pagination}.blade.php`, `admin/nav`, `user/nav`, `student/nav`, `admin/modals`, `routes/web.php`
- **How it works:** Shared Blade layouts. `main` = admin/staff panel (Robust RTL theme), `home_layout` = public intro + student pages. Root `/` → `layoutmodule::index` (route `home`). Navs: admin/user have "أختبار سرعة الكتابة" (→ `{guard}.typing.results.index`); student nav has only "أختباراتي" (dashboard, active on `dashboard|exam|typing-test`).
- **Decisions:**
  - Theme assets served statically from `public/admin-assets` — no Vite build for module views.
  - Theme Bootstrap is **4.0.0-alpha.4**: BS4-stable utilities (`d-none`, `d-flex`, `justify-content-*`, `align-items-*`, `bg-secondary`, `text-start`, `text-center`, `fw-bold`, and `.badge` itself) don't exist. Hide/show with inline `style="display:none"` / jQuery `toggle()`; use local CSS for flex. Existing views still use some of these classes (no effect).
- **Pending:** `main.blade.php` declares `lang="en"` / `ltr` while loading RTL CSS (?). UserModule login views extend non-existent `layoutmodule::admin.login` / `users.login` (?).

### AdminModule
- **Last updated:** 2026-10-17
- **Files:** `App/Http/Controllers/{AdminModuleController,Auth/AdminAuthController}.php`, `App/Http/Models/Admin.php`, `Services/AdminService.php`, views `admin/*`, `login.blade.php`
- **How it works:** `admin` guard login at `/admin`, dashboard (exam counts: upcoming/ongoing/finished), change password. Admin manages staff users, exams, questions, categories, students.
- **Pending:** admin CRUD views exist (`admin/create|edit|index|show`) but no routes for managing admins (?).

### UserModule (staff)
- **Last updated:** 2026-10-17
- **Files:** `App/Http/Controllers/{Admin/UserAdminModuleController,Auth/UserAuthController}.php`, `App/Http/Models/User.php`, `Services/UserService.php`
- **How it works:** `user` guard login at `/user`. Admins CRUD staff at `/admin/users`. Staff get the same exam/question/student screens as admins (`user.*` routes).
- **Pending:** staff access isn't restricted by permissions yet (see PermissionModule).

### PermissionModule
- **Last updated:** 2026-10-17
- **Files:** `Entities/{Role,Permission,ModelHasRole,ModelHasPermission}.php` (+ duplicates in `App/Http/Models/`), migrations for roles/permissions/pivots, seeders `PermissionSeederTableSeeder`, `RoleSeederTableSeeder`
- **How it works:** Custom tables (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`) with `model_type` pivot filter; Admin/User expose `hasRole`, `hasPermission`, `getAllPermissions`. Seeded permissions: `create_exam`, `create_questions`, `create_student`; roles `Admin`, `Moderator`.
- **Decisions:**
  - Custom implementation instead of spatie (spatie installed but unused (?)).
- **Pending:** no UI, middleware not wired (see Known issues), duplicate model classes in `Entities/` vs `App/Http/Models/`.

### QuestionModule
- **Last updated:** 2026-10-17
- **Files:** `App/Http/Controllers/{QuestionModuleController,CategoryController}.php`, models `Question`, `Answer`, `Category`, `App/Imports/QuestionImport.php`, `Services/{QuestionService,CategoryService}.php`, views `questions/*`, `categories/*`
- **How it works:** Categories group questions. Question types `mcq` (4 options A–D, stored as JSON array) and `true_false`. Correct answer stored in `answers.correct_answer` as the option **text** for MCQ, `true`/`false` for T/F. Excel import per category + type (row 0 = header; MCQ: text, A, B, C, D, answer letter; T/F: text, `صح`/`خطأ`); incomplete rows are skipped silently. `getRandomQuestionsByType()` feeds exam generation.
- **Pending:** import gives no report of skipped rows; import action is named `importStudents` in the question controller (naming leftover).

### ExamModule
- **Last updated:** 2026-11-19
- **Files:** `App/Http/Controllers/ExamModuleController.php`, `App/Http/Models/Exam.php`, `Services/ExamService.php`, views `admin/{index,create,edit}`
- **How it works:** Exam = category + date window (`start_date`/`end_date`) + `duration_minutes` + `mcq_count` / `true_false_count` (total_questions) + `success_grade` / `total_grade`. Validation (`$validator->after`) checks duration is set with dates and `mcq_count + true_false_count <= total_questions`. Pass/fail = `score >= success_grade` (student dashboard). `has_typing_test` (boolean, checkbox "يبدأ الأختبار بأختبار سرعة الكتابة" in create/edit) makes the typing test the first part of the exam — see TypingTestModule.
- **Pending:** no check that the category has enough questions of each type (?); no per-exam results report.

### StudentModule
- **Last updated:** 2026-11-20
- **Files:** controllers `StudentModuleController`, `Auth/StudentAuthController`, `Exam/StudentExamController`; models `Student`, `StudentExam`, `StudentExamAnswer`; `App/Imports/StudentImport.php`; services `StudentService`, `StudentExamService`; views `students/*`, `auth/*`, `exam/{start,result,showExam}`
- **How it works:**
  - Admin/staff: CRUD + search (`Student::scopeFilter`), `national_id` **required** on create/edit, Excel import (name, national_id, phone, email; password = national_id; skip existing national_id / incomplete rows), assign/unassign exams (`student_exams`) on `students/show-exams` — typing test is the first option of the assign list and its attempts share the "الاختبارات المسجلة" table (sorted by `created_at` desc); unassign refused once the course (or its linked typing part) started.
  - Student: login by national_id + password at `/student`; dashboard "أختباراتي" lists courses + standalone typing tests together by assignment date; start modal adapts to the typing test (words / pass mark in words, no total grade). Starting an attempt picks random MCQ + T/F questions from the exam category into `student_exam_answers`; answers saved one by one via AJAX (`exam.submitAnswer`); remaining time computed on the server (`StudentExamService::remainingSeconds`, passed to `exam/start` — no localStorage); expired attempts closed by `completeIfExpired` on reload of the exam or the dashboard; dashboard shows "جاري الاختبار" + "إكمال الأختبار" for in-progress attempts (incl. a linked typing part already opened); questions page: "سؤال X من N" above a thin progress bar and an always-visible "إنهاء الاختبار" button; the confirm modal warns how many questions are unanswered (they count as wrong); `completeExam` grades (`score` = number of correct answers) and shows result (+ typing part for linked exams).
- **Pending:** see Known issues (dead code, national_id rules); no server-side check of exam date window on start (?); `is_active` not checked at login (?); each question card still has its own "سؤال X من N" line (duplicate of the top counter — awaiting client decision).

### TypingTestModule (أختبار سرعة الكتابة)
- **Last updated:** 2026-11-20
- **Files:** `Services/{TypingEvaluator,TypingTestService,TypingTestSettingService,TypingTextService}.php`, models `TypingTestSetting`, `TypingText`, `StudentTypingTest`, repositories for each, controllers `Admin/{TypingTestSettingsController,TypingTextController,TypingResultController}`, `Student/StudentTypingTestController`, views `admin/{settings,texts/*,results/*}`, `student/{start,result}`, `partials/{highlighted_text,styles}`; migrations `typing_test_settings` (single seeded row), `typing_texts`, `student_typing_tests`; ExamModule migration `add_has_typing_test_to_exams_table`.
- **How it works:**
  - One fixed test; title stored in `typing_test_settings` but not fillable (can't be renamed/deleted). Main admin edits description / duration (10) / word count (150) / pass mark (60 correct words) at `admin/typing-test/settings`. Admin **and staff** manage the text bank at `{guard}/typing-test/texts`. Settings are snapshotted on each attempt when it starts.
  - Each attempt draws a random active text, cut to the first `word_count` words.
  - Grading (`TypingEvaluator`, mirrored in `student/start.blade.php` JS): positional word compare; tashkeel + punctuation matter; أ/إ/آ/ٱ=ا, ة=ه, ى=ي; whitespace runs = one separator; skipped word shifts → rest wrong; unreached words and an unfinished last-word prefix aren't counted. Score = correct words; pass = correct ≥ success_grade.
  - Standalone: on the student exams page (`students/show-exams`) the typing test is the first option of the "تعيين اختبار" list (AJAX `{guard}.typing.assign`, JSON when `Accept: application/json`) → one attempt per assignment (assign again = new attempt; unassign only if not started). Its attempts are listed in the same "الاختبارات المسجلة" table as the courses, ordered by assignment date (`created_at` desc). Student sees them in the same "أختباراتي" table on the dashboard as the courses, ordered by assignment date (no separate page or menu link).
  - Linked (`exams.has_typing_test`): `startExam` creates one typing attempt per course attempt and redirects to it. Passed → questions. Failed → result page with "إكمال إلى الأسئلة" (`?proceed=1`) or "الخروج" (back to dashboard; course stays not_started, as before). Questions timer starts when questions open. Course `score` unchanged; typing result shown on `exam/result`.
  - Server timer: `started_at` + snapshot duration; autosave every 15s; submissions later than deadline + 30s grace are ignored (last autosave is graded). Paste/copy blocked in the page.
  - Results list/details for admin + staff at `{guard}/typing-test/results`.
- **Decisions:**
  - Settings are main-admin only; staff can manage the text bank, assign and view results (2026-11-20).
  - Typing result is pass/fail only — no weight in the course score.
- **Pending:** no Excel import for texts; nothing stops editing `has_typing_test` on an exam with attempts in progress (only affects attempts not yet started).

### Reports / results export
- **Status:** Not started (planned (?) — not in code; confirm).

---

## Session log

### 2026-11-19 → 11-20 — Typing speed test + client feedback round (checkpoint)
- **Changed:**
  - New `Modules/TypingTestModule` (settings single row, text bank, attempts, `TypingEvaluator` grading mirrored in JS, results; routes `admin.typing.settings.*` admin-only, `{guard}.typing.{texts,results,assign,unassign}.*`, `student.typing.{start,save,submit,result}`), enabled in `modules_statuses.json`; ExamModule migration + checkbox `has_typing_test`.
  - StudentModule: `startExam` typing gate (`?proceed=1` after failing), fixed time-out redirect id, completed attempts can't reopen, server-side remaining time (`StudentExamService::{remainingSeconds,completeIfExpired}`, no localStorage); dashboard merged list + "جاري الاختبار"/"إكمال الأختبار" + auto-grading of expired attempts; questions page counter/finish-anytime/unanswered warning; assign page merged list (fixed Arabic `؛` JS bug); `national_id` required.
  - LayoutModule navs: typing link for admin/user; student nav = "أختباراتي" only.
  - UI fixes for the Bootstrap 4 alpha theme (no `d-none`/`d-flex`/`.badge`/`text-center`): jQuery `toggle()`, local CSS (`tt-*`, `exam-bar`, `#examProgress`).
- **Why:** client request (typing test spec) + review feedback (all exams in one list, staff manages texts, layout issues, resume/finish exam)
- **Affects:** ExamModule, StudentModule (student + admin screens), LayoutModule navs, TypingTestModule
- **Commit:** feat: typing speed test + student exam flow improvements

### 2026-10-17 — Initial project memory setup
- **Changed:** `CLAUDE.md` (stack, commands, structure, conventions, dependencies map), `docs/progress.md` (module sections, current status, known issues)
- **Why:** give every session an accurate snapshot of the codebase
- **Affects:** documentation only
- **Commit:** docs: add project memory (CLAUDE.md + docs/progress.md)
