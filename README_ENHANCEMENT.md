# NID Registration Kits / Personnel Enhancement

## What was changed
- Added a shared CoolAdmin-inspired responsive UI shell used by Personnel Information and Registration Kits.
- Added SweetAlert2 confirmations/notifications.
- Added quarterly personnel workflow: Q1/Q2/Q3/Q4, new personnel, renewal, update, history, remarks, archive/inactivate.
- Duplicate Personnel + Year + Quarter is blocked by the existing UNIQUE key.
- Status is derived from the latest quarterly record and contract dates: New, Active, Renewed, Inactive, Expired.
- Added year/quarter/status/NID/name filters and dashboard counters.
- Registration Kits now uses the same UI, searchable/filtered inventory, validation status, and secure CRUD.
- New APIs use PDO prepared statements, authentication, CSRF protection, server-side validation, and JSON responses.
- Existing tables/data are preserved. Personnel deletion is implemented as archive/inactive so history is not destroyed.

## Database
Run `database/quarterly_personnel_migration.sql` against the existing `nid` database. It is safe for the existing quarterly/remarks tables because it uses CREATE TABLE IF NOT EXISTS.

## Important
Back up the database before deployment. The existing RegistrationDetails password field remains stored as-is because changing its storage format could break current operational integrations; the UI masks it by default.
