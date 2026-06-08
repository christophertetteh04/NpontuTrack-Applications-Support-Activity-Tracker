# NpontuTrack — Applications Support Activity Tracker

Laravel app for recording daily Applications Support activity updates and producing shift handover–friendly reporting.

## What it tracks

- A defined list of activities (grouped by category)
- Per-day updates for each activity (status + optional metrics/remark)
- A complete timeline of updates (who changed what, and when)
- Shift handover “seals” with a generated PDF summary

## Architecture (high level)

- **Laravel 11** application
- **Controllers** handle requests and authorization, e.g.:
  - `ActivityLogController` stores activity updates and provides history responses/views
  - Shift handover sealing handled by `ShiftSealController`
- **Models** represent domain entities (Activity, ActivityLog, ShiftSeal, User)
- **Views** are Blade templates (dashboard, activities, reports, and handover pages)
- **Authorization** uses Laravel **policy-based** checks (role-based access)
- **Audit model**: each activity update is stored as a new row in `activity_logs`

## Key design decisions

### 1) Append-only activity logs

Updates do not overwrite previous records. Each update creates a new `activity_logs` entry, which ensures:

- Full history is always available for handovers
- Reporting is based on an immutable audit trail

### 2) Change tracking on the timeline

When saving a new update, the app captures the previous status for that activity on the selected date, so the UI can show what changed.

### 3) Rate limiting on updates

To prevent accidental double-submits or abuse, `ActivityLogController@store()` rate-limits updates per user (10 updates/minute). Responses degrade cleanly for JSON vs form/redirect requests.

### 4) Soft deletes for activity definitions

Activities can be deactivated/removed without losing historical logs.

- Deleted activities are excluded by default
- Admins can restore them

### 5) Performance-friendly querying

The database is indexed for common access patterns (especially by date and activity/personnel filtering) to keep the dashboard and reporting responsive.

## Setup

### Local (recommended)

1. Install dependencies:
   ```bash
   composer install
   ```
2. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Use SQLite for a quick start:
   ```bash
   touch database/database.sqlite
   php artisan migrate
   php artisan db:seed
   ```
4. Run:
   ```bash
   php artisan serve
   ```

### Docker Compose (production-like)

1. Build and start containers:
   ```bash
   docker-compose up -d --build
   ```
2. Run migrations inside the app container:
   ```bash
   docker-compose exec app php artisan migrate
   ```

## Main pages / routes

- **Daily Dashboard**: activity overview by date, with per-activity update timeline
- **Activities**: manage the activity definitions (categories, ordering, activation)
- **Daily Summary**: a handover-friendly daily view
- **Reports**: filterable reporting with CSV export
- **Shift Handover Seal**: generates a signed PDF summary for a selected shift
- **Users**: admin-only user management

## Security notes

- Authentication is handled by Laravel’s built-in session auth.
- Authorization is policy-based (controllers call `$this->authorize(...)`).
- CSRF protection is enabled for form submissions.
- Activity update writes include validation and basic audit fields (timestamp, updater, originating IP).

## Hosting Recommendations

This application is optimized for containerized environments. Recommended hosting paths:

1. **VPS (DigitalOcean, Hetzner, Linode):** Ideal for running the provided `docker-compose.yml` directly on a Linux server.
2. **Managed Container PaaS (Render, Railway):** Best for automated deployments from GitHub.
   - **Render Free Tier:** Use a "Web Service" connected to your GitHub repo.
   - **Database:** Since Render's free tier is for PostgreSQL, use [Aiven.io](https://aiven.io/) for a **Free MySQL** instance to stay within a $0 budget.
3. **Serverless Containers (Google Cloud Run):** A cost-effective option for low-traffic internal tools, as it scales based on request volume.

---

## 🚀 Quick Deployment Guide (Free Tier)

1. **Push to GitHub:** Ensure your latest code is in a public or private GitHub repository.
2. **Database:** Create a free MySQL instance on **Aiven.io**.
3. **Render Deployment:**
   - Log in to **Render.com** and create a **New Web Service**.
   - Connect your GitHub repository.
   - **Region:** Choose the one closest to you.
   - **Environment Variables:** Add `APP_KEY`, `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE`, and set `APP_DEBUG=false`.
   - **Build Command:** Render will use your `Dockerfile`.

_Note: Ensure `APP_DEBUG` is set to `false` and a strong `APP_KEY` is generated for any public-facing deployment._

## Development & CI

- CI runs PHPUnit tests and database migrations on push/PR against MySQL.
- Container setup uses `Dockerfile` + `docker-compose.yml` with nginx (web) and php-fpm (app).
