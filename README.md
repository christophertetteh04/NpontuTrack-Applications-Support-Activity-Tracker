# NpontuTrack — Applications Support Activity Tracker

A Laravel 11 web application for tracking daily activities of an Applications Support team.

---

## Requirements Addressed

| # | Requirement | Implementation |
|---|-------------|---------------|
| 1 | Input daily activities (e.g. SMS count vs log count) | `activities` table + `expected_value` / `actual_value` / `variance` fields on every update |
| 2 | Update status (done/pending) + remark per activity | `ActivityLogController@store` — status enum + remark field |
| 3 | Capture bio details of updater + timestamp | `updated_by` FK → users, `updated_at_time` timestamp auto-set |
| 4 | Handover view — all updates per activity per day visible | Dashboard shows full timeline per activity; Daily Summary report |
| 5 | Reporting by custom date range | Reports page with date range, activity, category, personnel, status filters + CSV export |
| 6 | User authentication | Laravel Auth middleware, login/logout, role-based access |

---

## Tech Stack

- **Framework:** Laravel 11 (PHP 8.2+)
- **Database:** SQLite (dev) / MySQL (production)
- **Auth:** Laravel built-in session auth
- **UI:** Blade templates + Tailwind CSS CDN + IBM Plex Sans/Mono
- **No additional JS frameworks** — vanilla JS for modal interactions

---

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (optional, for Vite asset compilation if extending)

### Steps

```bash
# 1. Clone / extract the project
cd npontu-tracker

# 2. Install PHP dependencies
composer install

# 3. Copy environment file and generate key
cp .env.example .env
php artisan key:generate

# 4. Create SQLite database file
touch database/database.sqlite

# 5. Run migrations
php artisan migrate

# 6. Seed with sample data (10 activities, 4 users, 7 days of logs)
php artisan db:seed

# 7. Start the dev server
php artisan serve
```

Visit **http://localhost:8000**

### Default Credentials (after seeding)

   
---

## User Roles

| Role       | Permissions |
|------------|-------------|
| **Admin**  | Full access — manage users, activities, view all reports |
| **Team Lead** | Manage activity definitions, update statuses, view reports |
| **Staff**  | Update activity statuses, view dashboard and reports |

---

## Key Features

### Daily Dashboard (`/`)
- Date-navigable board showing all activities grouped by category
- Each activity card shows its full **update timeline** for the selected date
- Each update entry shows: time, personnel name + employee ID, status, remark, and metric values
- "Update" button opens a modal capturing status, remark, SMS/metric values, shift, and timestamp automatically

### Activity Management (`/activities`)
- Team Leads and Admins can create/edit/deactivate activity definitions
- Activities have categories for grouping and a sort order for display priority
- `datalist` suggestions for common categories (SMS Monitoring, System Health, etc.)

### Daily Summary Report (`/reports/daily`)
- Printable daily view showing every activity with its complete update history
- Latest update flagged with "← LATEST" indicator for easy handover reading

### History Reports (`/reports`)
- Filter by: date range, activity, category, status, personnel, shift
- Aggregate summary cards (total updates, done, pending, escalated, days covered, staff count)
- Paginated results table (50 per page)
- **CSV export** of filtered results

### User Management (`/users`) — Admin only
- Create/edit team members with employee ID, department, role, phone
- Activate/deactivate accounts without deletion

---

## Database Schema

```
users
  id, name, employee_id, email, phone, department, role, password, is_active

activities
  id, title, description, category, sort_order, is_active, created_by

activity_logs
  id, activity_id, updated_by, log_date, status, remark,
  expected_value, actual_value, variance, shift, updated_at_time
```

`activity_logs` is the core audit table — it is **append-only**. Every update creates a new row, preserving the full history for handover visibility and reporting.

---

## Non-Functional Requirements Considered

- **Security:** CSRF protection on all forms, authentication middleware on all routes, password hashing via `Hash::make`, role-based access control
- **Performance:** Database indexes on `(log_date, activity_id)` and `(log_date, updated_by)` for fast daily queries
- **Auditability:** Append-only log design — no updates are ever overwritten
- **Usability:** Inline update modal eliminates page navigation; date picker for historical browsing
- **Scalability:** Eager loading (`with()`) prevents N+1 queries; paginated report results
- **Maintainability:** Thin controllers, model scopes (`forDate`, `forDateRange`, `forUser`, `withStatus`) for reusable query logic
