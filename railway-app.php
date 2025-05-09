<?php

// Ensure proper permissions
if (file_exists('storage') && !is_writable('storage')) {
    chmod('storage', 0755);
}

if (file_exists('database') && !is_writable('database')) {
    chmod('database', 0755);
}

if (file_exists('bootstrap/cache') && !is_writable('bootstrap/cache')) {
    chmod('bootstrap/cache', 0755);
}

// Create SQLite database if it doesn't exist
if (!file_exists('database/database.sqlite')) {
    touch('database/database.sqlite');
}

// Execute artisan serve command
require __DIR__.'/artisan'; 