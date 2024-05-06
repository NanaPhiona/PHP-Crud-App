<?php
$databasePath = '/var/www/phi-book-app.sqlite'; 
try {
    $conn = new PDO('sqlite:' . $databasePath);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    die();
}

// Check if the duplicate 'id' column exists
$stmt = $conn->query("PRAGMA table_info(books)");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
$duplicateIdFound = false;
foreach ($columns as $column) {
    if ($column['name'] === 'id' && $duplicateIdFound) {
        // Drop the duplicate 'id' column
        try {
            $conn->exec("ALTER TABLE books DROP COLUMN id");
            echo "Duplicate 'id' column dropped successfully!";
        } catch (PDOException $e) {
            echo "Failed to drop duplicate 'id' column: " . $e->getMessage();
            die();
        }
        break;
    } elseif ($column['name'] === 'id') {
        $duplicateIdFound = true;
    }
}
?>

