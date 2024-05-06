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

// Retrieve book details from the database
try {
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->bindValue(':id', $book_id);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Failed to retrieve book: " . $e->getMessage();
    die();
}

// Update book details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $publication_year = intval($_POST['publication_year']); // Ensure it's an integer

    // Prepare the SQL statement to update the book
    $stmt = $conn->prepare("UPDATE books SET title = :title, author = :author, genre = :genre, publication_year = :publication_year WHERE id = :id");
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':author', $author);
    $stmt->bindValue(':genre', $genre);
    $stmt->bindValue(':publication_year', $publication_year);
    $stmt->bindValue(':id', $book_id);

    // Execute and check if successful
    try {
        $stmt->execute();
        $successMessage = "Book details updated successfully!";
    } catch (PDOException $e) {
        $errorMessage = "Failed to update book: " . $e->getMessage();
    }

    header('Location: view.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit Book</h1>
        <?php if(isset($successMessage)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        <?php if(isset($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="edit.php">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $book['title']; ?>">
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo $book['author']; ?>">
            </div>
            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre" value="<?php echo $book['genre']; ?>">
            </div>
            <div class="form-group">
                <label for="publication_year">Publication Year</label>
                <input type="number" class="form-control" id="publication_year" name="publication_year" value="<?php echo $book['publication_year']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
