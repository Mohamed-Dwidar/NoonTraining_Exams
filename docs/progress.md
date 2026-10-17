# Project Progress

> Loaded into every session via CLAUDE.md — keep it short and current.
> Edit module sections in place. Session log: last 10 entries only (git has the rest).
> Cross-module connections: see "Dependencies map" in CLAUDE.md.

---

## Current status
- **Working on:** Excel imports polish (students + questions) and the public home page — last commits: "Skip rows with missing data", "home page", "import questions", "Students search". Branch `main`, in sync with origin; no uncommitted code changes (only file-mode changes on `artisan` and `public/assets__TO_DEL/js/vendor/*`).
- **Next up:** client review fixes (?) — "finished ... before client review" commit suggests a review round is in progress.
- **Known issues:**
  - `StudentExamController::startExam` — on time-out redirects to `student.exam.result` with `$exam->id` instead of `$studentExam->id`.
  - `StudentExamController::submitExam` / `storeQuestionAnswers` are dead code (no route; `user_answer` column doesn't exist).
  - `CheckPermission` is registered only in `app/Http/Kernel.php`, which Laravel 12 (`bootstrap/app.php`) does not load → `permission` middleware is not active, and it is used on no route.
  - `PermissionSeederTableSeeder` inserts permissions without `guard_name` (NOT NULL, no default) — may fail in strict MySQL (?).
  - Lowercase `app\` in many `use` statements and `config/auth.php` providers — will break on case-sensitive (Linux) servers unless the optimized classmap covers it (?).
  - `national_id` validation inconsistent: `digits:10` in controller, `digits:14` in `StudentRequest`, column is `string(14)`.
  - No real tests.

---

## Modules

### LayoutModule
- **Last updated:** 2026-10-17
- **Files:** `Modules/LayoutModule/resources/views/layouts/{main,home_layout,flash,header,footer,custom_pagination}.blade.php`, `admin/nav`, `user/nav`, `student/nav`, `admin/modals`, `routes/web.php`
- **How it works:** Shared Blade layouts. `main` = admin/staff panel (Robust RTL theme), `home_layout` = public intro + student pages. Root `/` → `layoutmodule::index` (route `home`).
- **Decisions:**
  - Theme assets served statically from `public/admin-assets` — no Vite build for module views.
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
- **Last updated:** 2026-10-17
- **Files:** `App/Http/Controllers/ExamModuleController.php`, `App/Http/Models/Exam.php`, `Services/ExamService.php`, views `admin/{index,create,edit}`
- **How it works:** Exam = category + date window (`start_date`/`end_date`) + `duration_minutes` + `mcq_count` / `true_false_count` (total_questions) + `success_grade` / `total_grade`. Validation (`$validator->after`) checks duration is set with dates and `mcq_count + true_false_count <= total_questions`. Pass/fail = `score >= success_grade` (student dashboard).
- **Pending:** no check that the category has enough questions of each type (?); no per-exam results report.

### StudentModule
- **Last updated:** 2026-10-17
- **Files:** controllers `StudentModuleController`, `Auth/StudentAuthController`, `Exam/StudentExamController`; models `Student`, `StudentExam`, `StudentExamAnswer`; `App/Imports/StudentImport.php`; services `StudentService`, `StudentExamService`; views `students/*`, `auth/*`, `exam/{start,result,showExam}`
- **How it works:**
  - Admin/staff: CRUD + search (`Student::scopeFilter`), Excel import (name, national_id, phone, email; password = national_id; skip existing national_id / incomplete rows), assign/unassign exams (`student_exams`).
  - Student: login by national_id + password at `/student`, dashboard lists assigned exams. Starting an attempt picks random MCQ + T/F questions from the exam category into `student_exam_answers`; answers saved one by one via AJAX (`exam.submitAnswer`); time limit checked on reload; `completeExam` grades (`score` = number of correct answers) and shows result.
- **Pending:** see Known issues (time-out redirect, dead code, national_id rules); no server-side check of exam date window on start (?); `is_active` not checked at login (?).

### Reports / results export
- **Status:** Not started (planned (?) — not in code; confirm).

---

## Session log

### 2026-10-17 — Initial project memory setup
- **Changed:** `CLAUDE.md` (stack, commands, structure, conventions, dependencies map), `docs/progress.md` (module sections, current status, known issues)
- **Why:** give every session an accurate snapshot of the codebase
- **Affects:** documentation only
- **Commit:** docs: add project memory (CLAUDE.md + docs/progress.md)
