to run this PHP auth app, use command:

> php -S localhost:7000

SQLite notes for Student CRUD:

- A `students.db` file is created automatically in the project root on first visit to `dashboard.php`.
- The table `students` has columns: `id, name, email, course, age`.
- Go to `http://localhost:7000/login.php`, log in, then `dashboard.php` lets you add, edit, and delete students.
- If you need to inspect the DB, you can use the SQLite CLI:
  - `sqlite3 students.db`
  - `.tables` to list tables, `SELECT * FROM students;` to view rows.
