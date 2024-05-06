
<?php
$databasePath = '/var/www/phi-book-store.sqlite';  // Directly using the path from your env information
$db = new SQLite3($databasePath);

if ($db) {
    echo "Connected to the database successfully!";
} else {
    echo "Failed to connect to the database.";
}