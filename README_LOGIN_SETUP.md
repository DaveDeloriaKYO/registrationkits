# Login System — What Was Added

## New files
- `auth.php` — session guard. Every existing page and AJAX endpoint now
  starts with `require_once __DIR__ . '/auth.php';`, so nothing loads
  without an active login. AJAX calls get a 401 JSON response instead
  of an HTML redirect (so your existing `$.ajax` success/error handlers
  keep working); normal page loads redirect to `login.php`.
- `login.php` — the login form + handler (single shared admin account,
  checked against a `users` table).
- `logout.php` — destroys the session.
- `setup_admin.php` — **run this once** to create the `users` table and
  your admin account. It refuses to run again once an account exists.
- `change_password.php` — lets the logged-in admin change the password.
- `database/users.sql` — the `users` table schema, for reference
  (`setup_admin.php` creates it automatically, so you don't need to run
  this by hand unless you prefer to).

## Deploy steps
1. Upload all files (including the new ones) to your server, keeping
   the same folder structure. `vendor/` and `composer.json/.lock` are
   unchanged — nothing here touches your existing PhpSpreadsheet/PDF
   dependencies.
2. Visit `setup_admin.php` in your browser once, and create your admin
   username/password there (minimum 8 characters). This is the only
   step that creates the account — I couldn't pre-generate a password
   hash for you, since that has to be done by your server's own PHP
   install for it to verify correctly later.
3. **Delete `setup_admin.php` from the server** right after — it's
   intentionally left unprotected so it can create the first account,
   which means it must not stay reachable afterward.
4. Go to `login.php` and log in with the account you just created.

## What's protected
All 63 existing `.php` pages and AJAX endpoints (everything except
`db.php`, `bootstrap.php`, `navbar.php`, `sidebar.php`,
`profile_dropdown.php`, and `script.php`, which are just included
snippets, not directly-loaded pages) now require a valid session.

## Visible logout link
Added a small "Logged in as ... | Logout" bar to `index.php` (the
dashboard) and `registration_kits.php`. The rest of the pages don't
share a common header/nav bar yet (that was already true before this
change — each page is a standalone HTML file), so I didn't script
that part in automatically to avoid breaking any page's layout. Happy
to add a consistent top bar across all pages as a follow-up — say the
word and I'll wire it in everywhere.

## Notes / things worth doing next
- Session timeout is set to 30 minutes of inactivity (edit
  `NID_SESSION_TIMEOUT` in `auth.php` to change it).
- Passwords are hashed with PHP's `password_hash()` (bcrypt) — never
  stored in plain text.
- If you later want multiple accounts or roles (Admin vs Viewer), the
  `users` table already has room to grow — just add a `role` column
  and check it in `auth.php`.
- The unused `html/` demo folder (leftover template samples like
  `authentication-login.html`) was left untouched since nothing
  references it, but it's safe to delete if you want to tidy the repo.

## Personnel Information — new additions
- Dashboard (`index.php`) no longer has the old "Add NID Field Personnel"
  tile or the "Filter NID Field Personnel" tile/modal — both are removed,
  including their JS. The "NID Field Personnel" tile now links straight
  to `personnel_information.php`.
- `personnel_information.php` now has its own **Add Personnel** button
  (same fields as the old dashboard modal, posts to the existing
  `insert.php` — unchanged).
- Each row has two new actions:
  - **View Details** (id-card icon) — opens all of that person's info
    plus a **Remarks** log (add a note any time; each is timestamped
    and tagged with the logged-in username). New table:
    `personnel_remarks`. New endpoints: `get_personnel_details.php`,
    `add_personnel_remark.php`.
  - **Add Quarterly Record** (calendar-plus icon) / **History**
    (clock icon) — lets you add a new quarter's assignment (position,
    municipality, reg kit, contract dates, status) without overwriting
    the personnel's master record, and view the full quarter-by-quarter
    history. New table: `personnel_quarterly_history` (one row per
    personnel per quarter — a duplicate quarter is rejected with a
    message telling you to edit the existing one instead). New
    endpoints: `add_personnel_quarter.php`, `get_personnel_quarters.php`.
- All new tables are created automatically the first time their
  endpoint is called — no manual SQL import needed, though
  `database/personnel_quarterly_history.sql` and
  `database/personnel_remarks.sql` are there for reference.

### Ideas for later
- Contract-expiry alert: flag personnel whose latest quarterly
  `contract_end_date` is within, say, 30 days, right on the stat cards.
- Export the personnel table (or one person's quarter history) to
  Excel/PDF — `vendor/` already has the libraries this app uses
  elsewhere (`upload_excel.php`, PDF borrower slips).
- Turn "Municipality" on `personnel_info` into a link to that
  municipality's assigned registration kit/team, since `team_display.php`
  already tracks that.
