<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Borrow Book</title>
    <link rel="stylesheet" href="main.css">
    <?php

    $used_tokens_file = 'usedtokens.json';
    $used_tokens_data = [];

    if (file_exists($used_tokens_file)) {
        $used_tokens_data = json_decode(file_get_contents($used_tokens_file), true);
    }

    $tokens_file = 'tokens.json';
    $tokens_data = [];
    $valid_tokens = [];

    if (file_exists($tokens_file)) {
        $tokens_data = json_decode(file_get_contents($tokens_file), true);
        $valid_tokens = $tokens_data['tokens'];
    }
    ?>
</head>

<body>
    <div>
        <img src="id.png" alt="id-picture">
    </div>

    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="#">Borrow Book</a>
            </div>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#hero">Books</a></li>
                <li><a href="#validation">Borrow</a></li>
                <!-- <li><a href="#">About</a></li> -->
                <li><a href="#addbook">Add Book</a></li>
            </ul>
        </div>


        <div class="main-content">

            <!-- LEFT SIDEBAR: show used tokens here -->
            <div class="left-sidebar">
                <h3>Used Tokens</h3>
                <?php
                if (!empty($used_tokens_data)) {
                    echo "<ul>";
                    foreach ($used_tokens_data as $usedToken) {
                        echo "<li>Token: " . htmlspecialchars($usedToken) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No tokens used yet.</p>";
                }
                ?>
            </div>
            <!-- End of left sidebar -->

            <div class="content-area">
                <div class="hero" id="hero">
                    <h1>Welcome to Our Library</h1>
                    <p>Search for books and check their availability:</p>
                    <form action="" method="GET" class="search-form">
                        <input
                            type="text"
                            name="query"
                            placeholder="Enter book title or author..."
                            required>
                        <button type="submit">Search</button>
                    </form>
                    <div class="available-books">
                        <h2>Available Books</h2>
                        <div class="books-list">
                            <?php

                            $conn = mysqli_connect('localhost', 'root', '', 'borrow_book');
                            if (!$conn) {
                                echo "<p>Failed to connect to the database.</p>";
                            } else {
                                $query = isset($_GET['query']) ? htmlspecialchars(trim($_GET['query'])) : '';


                                $sql = "SELECT title, author FROM books";
                                if (!empty($query)) {
                                    $sql .= " WHERE title LIKE '%$query%' OR author LIKE '%$query%'";
                                }
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<ul>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<li>" . htmlspecialchars($row['title']) . " by " . htmlspecialchars($row['author']) . "</li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "<p>No books found.</p>";
                                }
                                mysqli_close($conn);
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="content2" id="addbook">
                    <h2>Add a New Book</h2>
                    <form action="process_newbook.php" method="POST">
                        <!-- Book Title -->
                        <label for="book_title">Book Title</label>
                        <input type="text" id="book_title" name="book_title" placeholder="Enter the book title" required />
                        <!-- Author -->
                        <label for="book_author">Author</label>
                        <input
                            type="text"
                            id="book_author"
                            name="book_author"
                            placeholder="Enter the author's name"
                            required />
                        <!-- Publish Date -->
                        <label for="publish_date">Publish Date</label>
                        <input
                            type="date"
                            id="publish_date"
                            name="publish_date"
                            required />

                        <!-- Book Price or Fee -->
                        <label for="book_price">Price</label>
                        <input
                            type="number"
                            id="book_price"
                            name="book_price"
                            placeholder="Enter the price"
                            required />

                        <!-- Additional Info -->
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Short description of the book..."></textarea>
                        <label for="book_isbn">ISBN (Numbers Only)</label>
                        <input
                            type="text" id="book_isbn" name="book_isbn" placeholder="Enter the ISBN (numbers only)" pattern="[0-9]+" required />
                        <!-- Submission Date/Time -->
                        <input type="submit" value="Submit" />
                    </form>
                </div>

                <div class="last-container">
                    <!-- <div class="small-contents">
                        <div class="small-content">Small Content 1</div>
                        <div class="small-content">Small Content 2</div>
                        <div class="small-content">Small Content 3</div>
                    </div> -->

                    <div class="small-contents" id="smallContents"></div>





                    <div class="footer">
                        <!-- Footer 1: Validation Form -->
                        <div class="validation" id="validation">
                            <form action="process.php" method="post">
                                <label for="student_name">Student name</label>
                                <input type="text" id="sname" name="student_name" placeholder="Your Name" required>

                                <label for="student_id">Student ID</label>
                                <input type="text" id="sid" name="student_id" placeholder="**-*****-**" required>

                                <label for="email">Email</label>
                                <input type="text" name="email_id" id="email_id" placeholder="Your Email" required>

                                <label for="books">Choose a book</label>
                                <select id="books" name="books">
                                    <?php
                                    // Database connection
                                    $conn = mysqli_connect('localhost', 'root', '', 'borrow_book');

                                    if (!$conn) {
                                        die("Connection failed: " . mysqli_connect_error());
                                    }
                                    $query = "SELECT title, author FROM books";
                                    $result = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        // Generate an option for each book
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $title = htmlspecialchars($row['title']);
                                            $author = htmlspecialchars($row['author']);
                                            echo "<option value='$title'>$title</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No books available</option>";
                                    }

                                    mysqli_close($conn);
                                    ?>
                                </select>


                                <label for="borrowDate">Borrow Date</label>
                                <input type="date" id="borrowDate" name="borrowDate" required>

                                <label for="returnDate">Return Date</label>
                                <input type="date" id="returnDate" name="returnDate" required>

                                <label for="fees">Fees</label>
                                <input type="text" id="fees" name="fees" placeholder="Enter Fees" required>

                                <!-- Token field -->
                                <label for="token">Token (for extended borrow period)</label>
                                <input type="text" id="token" name="token" placeholder="Click on a token">

                                <small id="token-info" style="color: green;"></small><br><br><br>

                                <input type="submit" value="Submit">
                            </form>
                        </div>
                        <!-- End of validation form -->

                        <!-- Footer 2: Token Display -->
                        <div id="token-display">
                            <h3>Available Tokens</h3>
                            <ul>
                                <?php
                                // Display available tokens
                                if (!empty($valid_tokens)) {
                                    foreach ($valid_tokens as $token) {
                                        // Only show tokens that are not used
                                        // We *assume* removing them from tokens.json after usage
                                        echo "<li class='token-item' onclick='document.getElementById(\"token\").value = $token;'>Token: $token</li>";
                                    }
                                } else {
                                    echo "<li>No tokens available.</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <!-- End of footer -->
                </div>
            </div>

            <div class="right-sidebar">
                <h3>Upcoming Books</h3>
                <ul>
                    <?php
                    // Load the JSON file
                    $jsonFile = 'upcoming_books.json';
                    if (file_exists($jsonFile)) {
                        $books = json_decode(file_get_contents($jsonFile), true);

                        // Calculate days remaining for each book
                        foreach ($books as $book) {
                            $title = htmlspecialchars($book['title']);
                            $releaseDate = new DateTime($book['releaseDate']);
                            $currentDate = new DateTime();
                            $daysRemaining = $currentDate->diff($releaseDate)->days;

                            // Display book and days remaining
                            if ($currentDate < $releaseDate) {
                                echo "<li>$title - $daysRemaining days to go</li>";
                            } else {
                                echo "<li>$title - Released!</li>";
                            }
                        }
                    } else {
                        echo "<li>Upcoming books data not available.</li>";
                    }
                    ?>
                </ul>
            </div>

        </div>
    </div>

    <script>
        // borrow date and return date
        document.getElementById('borrowDate').addEventListener('change', checkDates);
        document.getElementById('returnDate').addEventListener('change', checkDates);

        function checkDates() {
            const borrowDate = new Date(document.getElementById("borrowDate").value);
            const returnDate = new Date(document.getElementById("returnDate").value);
            const tokenInfo = document.getElementById("token-info");

            if (borrowDate && returnDate) {
                const dayDifference = (returnDate - borrowDate) / (1000 * 60 * 60 * 24);

                if (dayDifference > 10) {
                    tokenInfo.textContent = "Return date exceeds 10 days. A valid token is required.";
                    tokenInfo.style.color = "red";
                } else if (dayDifference > 0) {
                    tokenInfo.textContent = "Token not required for less than 10 days.";
                    tokenInfo.style.color = "green";
                    document.getElementById("token").value = "";
                } else {
                    tokenInfo.textContent = "Invalid return date.";
                    tokenInfo.style.color = "red";
                }
            }
        }

        async function loadBooks() {
            try {
                const response = await fetch('books.json');
                const books = await response.json();

                const container = document.getElementById('smallContents');
                let currentIndex = 0;

                function displayBooks() {
                    container.innerHTML = '';

                    // Get the next 3 books
                    for (let i = 0; i < 3; i++) {
                        const bookIndex = (currentIndex + i) % books.length;
                        const book = books[bookIndex];
                        const bookHTML = `
                        <div class="small-content">
                            <img src="${book.image}" alt="${book.title} Cover">
                            <h4>${book.title}</h4>
                            <p>Author: ${book.author}</p>
                            <p>${book.description}</p>
                        </div>
                    `;
                        container.innerHTML += bookHTML;
                    }


                    currentIndex = (currentIndex + 3) % books.length;
                }

                displayBooks();

                setInterval(displayBooks, 5000);
            } catch (error) {
                console.error('Error fetching or parsing books.json:', error);
            }
        }

        // Load the books when the page is ready
        document.addEventListener('DOMContentLoaded', loadBooks);
    </script>
</body>

</html>