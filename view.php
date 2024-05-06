<?php
$databasePath = '/var/www/phi-book-app.sqlite'; 
try {
    $conn = new PDO('sqlite:' . $databasePath);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    die();
}

// Retrieve all books from the database
try {
    if(isset($_GET['search'])) {
        $search = $_GET['search'];
        $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE :search");
        $stmt->bindValue(':search', "%$search%");
    } else {
        $stmt = $conn->query("SELECT * FROM books");
    }
    
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Failed to retrieve books: " . $e->getMessage();
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class='container'>
        <form method="GET" action="view.php">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search by title" name="search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
        </form>
        <h1>Book List</h1>
        <table class="table table-light">
            <thead>
                <tr>
                    <th>Id<th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Publication Year</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr class='trow'>
                        <td><?php echo htmlspecialchars($book['id']); ?></td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['genre']); ?></td>
                        <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                        <td><a href="edit.php?book_id=<?php echo $book['id']; ?>" class="btn btn-primary">Edit</a></td>
                        <td><a href="delete.php?book_id=<?php echo $book['id']; ?>" class="btn btn-danger">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href='index.php' class='primary'>Go back: Home</a>
    <div>
</html>