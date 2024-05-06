<?php
$databasePath = '/var/www/phi-book-app.sqlite'; 
try {
    $conn = new PDO('sqlite:' . $databasePath);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Checking if the 'id' column exists
    $stmt = $conn->query("PRAGMA table_info(books)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $idColumnExists = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'id') {
            $idColumnExists = true;
            break;
        }
    }

    // Add the 'id' column if it doesn't exist
    if (!$idColumnExists) {
        $conn->exec("ALTER TABLE books ADD COLUMN id INTEGER PRIMARY KEY AUTOINCREMENT;");
    }

    // Create the table if it doesn't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS books (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        author TEXT NOT NULL,
        genre TEXT NOT NULL,
        publication_year INTEGER
    )");
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    die();
}

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $publication_year = intval($_POST['publication_year']); // Ensure it's an integer

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO books (title, author, genre, publication_year) VALUES (:title, :author, :genre, :publication_year)");
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':author', $author);
    $stmt->bindValue(':genre', $genre);
    $stmt->bindValue(':publication_year', $publication_year);

    // Execute and check if successful
    try {
        $stmt->execute();
        $successMessage = "Book successfully added!";
    } catch (PDOException $e) {
        $errorMessage = "Failed to add book: " . $e->getMessage();
    }

    // Redirect to view.php
    header("Location: view.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Add Book</title>
</head>
<body>
    <div class="container">
        <h1>Add a New Book</h1>
        <?php
            if (isset($successMessage)) {
                echo "<p style='color: green;'>$successMessage</p>";
            }
            if (isset($errorMessage)) {
                echo "<p style='color: red;'>$errorMessage</p>";
            }
        ?>
        <form action="index.php" method="post">
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

            <button type="submit" name='create' class='btn btn-primary'>Add Book</button>
    </form>
    <p class="books-lists">List of books in store <a href="view.php" class="primary">Available books</a></p>
    </div>
</body>
</html>
