<?php
$databasePath = '/var/www/phi-book-app.sqlite'; 
try {
    $conn = new PDO('sqlite:' . $databasePath);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    die();
}

// Check if book ID is provided
if (!isset($_GET['book_id'])) {
    echo "Book ID is missing.";
    die();
}

$book_id = $_GET['book_id'];

// Delete book from the database
try {
    $stmt = $conn->prepare("DELETE FROM books WHERE id = :id");
    $stmt->bindValue(':id', $book_id);
    $stmt->execute();
    // Redirect back to view.php after deletion
    header("Location: view.php");
    exit;
} catch (PDOException $e) {
    $errorMessage = "Failed to delete book: " . $e->getMessage();
}
?>
