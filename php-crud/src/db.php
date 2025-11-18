<?php
// Simple SQLite connection using SQLite3 class and table auto-create
function get_db(): SQLite3
{
    static $db = null;
    if ($db !== null) {
        return $db;
    }
    $dbPath = __DIR__ . '/../students.db';
    $db = new SQLite3($dbPath);
    $db->enableExceptions(true);

    // Create students table if it does not exist
    $db->exec('CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        course TEXT NOT NULL,
        age INTEGER NOT NULL
    )');

    // Create users table if it does not exist
    $db->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    )');

    return $db;
}
