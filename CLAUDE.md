# Project: Noon Training Exams (منصة الاختبارات المحوسبة - معهد نون للتدريب)

@docs/progress.md

Online exam platform for Noon Training Institute: admins/staff manage question banks (by category), exams and students; students log in and take timed, randomly-generated exams.

## Before you start (every session)
1. `docs/progress.md` is loaded above — treat it as the latest state, decisions, and cross-module dependencies.
2. Run `git log --oneline -15` to see recent changes.
3. If the task touches a module listed under "Dependencies map", check the related files before editing.

## When a task or feature is done
When a task is finished, or when I say "update progress" or run /checkpoint, update `docs/progress.md`:
- Edit the relevant module section (files, how it works, dependencies, decisions, pending) — don't just append.
- Add one entry to the session log; keep only the last 10 entries.
- Update "Current status".
Then suggest a commit message.

## Stack
- Framework: Laravel 12 / PHP ^8.2, modular via `nwidart/laravel-modules` v12 (`Modules/`, enabled list in `modules_statuses.json`)
- Database: MySQL (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
- Frontend: Blade + Bootstrap RTL admin theme ("Robust", static files in `public/admin-assets/`) + jQuery. Tailwind 4 / Vite are installed (`resources/css/app.css`) but not used by module views (?)
- Auth: custom session guards in `config/auth.php` — `admin` (admins table), `user` (users table), `student` (students table). No Breeze/Jetstream.
- Permissions: custom roles/permissions tables in PermissionModule + `hasRole()/hasPermission()` on Admin/User models. `spatie/laravel-permission` is installed but not used (?)
- Key packages: `prettus/l5-repository` (repositories), `maatwebsite/excel` (Excel imports), `laravel/sanctum` (installed, no API in use (?))

## Commands
- Dev server: `php artisan serve` (or `composer dev` — serve + queue + pail + vite)
- Assets: `npm run dev` / `npm run build`
- Migrate: `php artisan migrate` (module migrations live in `Modules/*/database/migrations`)
- Seed permissions: `php artisan module:seed PermissionModule` (?)
- Tests: `php artisan test` (only Laravel example tests exist)
- Clear caches: `php artisan optimize:clear`

## Project structure
- `Modules/<Name>Module/` — each feature module:
  - `App/Http/Controllers`, `App/Http/Models`, `App/Http/Requests`, `App/Imports`, `App/Providers`
  - `Repository/` (l5-repository `BaseRepository`), `Services/` (business logic)
  - `resources/views/` (namespace `<name>module::`), `routes/web.php`, `database/migrations`, `database/seeders`
- `Modules/LayoutModule/resources/views/layouts/` — shared layouts: `main` (admin/user panel), `home_layout` (public/student), `flash`, `header`, `footer`, `custom_pagination`; navs in `admin/`, `user/`, `student/`
- `app/Http/Middleware/CheckPermission.php` — permission middleware (registered only in legacy `app/Http/Kernel.php`, see progress)
- `app/Helpers/` — `ApiResponseHelper`, `FcmHelper`, `UploaderHelper` (leftovers from another project (?))
- `public/admin-assets/` — theme CSS/JS; `public/imports/` — Excel templates for students and questions
- `public/assets__TO_DEL/`, `public/hoe_cats.php`, `public/mkg_request*.php` — unrelated leftovers, candidates for deletion (?)
- `routes/web.php` — empty; all routes come from module route files

## Conventions
- Route names: `{guard}.{resource}.{action}` (e.g. `admin.students.index`, `user.exam.edit`, `student.exam.start`). Inconsistent singular/plural in QuestionModule (`questions.index` vs `question.create`) and UserModule (`admin.users.list` vs `admin.user.create`).
- Admin and staff share the same CRUD routes: `foreach (['admin','user'] as $guard)` with `auth:$guard`. Controllers redirect via `route(Auth::getDefaultDriver() . '.students.index')` — keep this pattern for shared screens.
- Always use named routes — never hardcoded URLs
- Flow: Controller → Service → Repository (`prettus` BaseRepository) → Model. Controllers should stay thin; some still query models directly (e.g. `Category::all()`).
- Validation: mostly inline `$request->validate()` / `Validator::make()` with Arabic messages. One Form Request exists (`StudentRequest`, unused (?)). (proposed) new code: Form Requests in `App/Http/Requests`.
- Update/delete style: update posts `id` in the body (`POST students/update`), delete often via GET `{id}/delete` (proposed: POST/DELETE for new routes).
- Models namespace: `Modules\<X>Module\App\Http\Models`. Imports often use lowercase `app\` — works on macOS only; use `App\` in new code (proposed).
- Views: `@extends('layoutmodule::layouts.main')` + `@include('layoutmodule::layouts.flash')`; flash keys `success` / `error`.
- Language / direction: Arabic RTL UI (all user-facing text and validation messages in Arabic); code/comments in English.
- Naming: DB columns snake_case; controller methods camelCase; views snake_case/camelCase mixed (`showExam.blade.php`, `change_password.blade.php`).
- Code style: opening brace on same line for classes/methods in newer modules (Student/Question/Exam); Laravel style in older ones. Laravel Pint is available but no config (?).

## Dependencies map (what affects what)
- **LayoutModule** ↔ every module's views (layouts, navs, flash, pagination). Root `/` (`home`) route lives here.
- **AdminModule** ↔ `admin` guard, PermissionModule (roles/permissions via `model_has_*`), dashboard counts from ExamModule (`ExamService::count*`).
- **UserModule** ↔ `user` guard (staff), managed by admins (`admin.users.*`), PermissionModule; shares CRUD routes of Exam/Question/Student modules.
- **PermissionModule** ↔ Admin & User models (`roles()`, `permissions()`), `CheckPermission` middleware, seeders (`create_exam`, `create_questions`, `create_student`).
- **QuestionModule** (categories, questions, answers) ↔ ExamModule (`exams.category_id`), StudentModule (`QuestionService::getRandomQuestionsByType` builds attempts; `answers.correct_answer` used for grading). Question import via `QuestionImport` + `public/imports/questions_*_template.xlsx`.
- **ExamModule** ↔ QuestionModule (category + mcq/true_false counts), StudentModule (`student_exams` pivot), AdminModule dashboard.
- **StudentModule** ↔ `student` guard, ExamModule (assign/unassign), QuestionModule (random questions, grading), `StudentImport` + `public/imports/students_template.xlsx`.
- **Reports / results export** (planned (?)) ↔ StudentModule `student_exams` scores, ExamModule `success_grade`.

## Rules
- Before renaming anything shared (routes, components, config keys), search the whole project for references and update them all.
- Never edit migrations that already ran — create a new migration.
- Ask before deleting files or changing shared layouts.
- Keep changes minimal and scoped to the task.
