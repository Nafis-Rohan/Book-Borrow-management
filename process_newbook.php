<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST["book_title"]), ENT_QUOTES, 'UTF-8');
    $author = htmlspecialchars(trim($_POST["book_author"]), ENT_QUOTES, 'UTF-8');
    $isbn = filter_var($_POST["book_isbn"], FILTER_SANITIZE_NUMBER_INT);
    $publish_date = $_POST["publish_date"];
    $price = filter_var($_POST["book_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Database connection
    $conn = mysqli_connect('localhost', 'root', '', 'borrow_book');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $isbn_check_query = "SELECT * FROM books WHERE isbn = '$isbn'";
    $isbn_check_result = mysqli_query($conn, $isbn_check_query);

    if (mysqli_num_rows($isbn_check_result) > 0) {
        echo "Error: A book with this ISBN already exists.";
        header("refresh: 3; url = index.php");
    } else {

        $sql = "INSERT INTO books (title, author, isbn, publish_date, price) 
                VALUES ('$title', '$author', '$isbn', '$publish_date', '$price')";

        if (mysqli_query($conn, $sql)) {
            echo "New book added successfully!";
            header("refresh: 3; url = index.php");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
} else {
    echo "Invalid request method.";
    header("refresh: 2; url = index.php");
}
