<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name  = isset($_POST['student_name']) ? trim($_POST['student_name']) : '';
    $student_email = isset($_POST['email_id'])     ? trim($_POST['email_id'])     : '';
    $student_id    = isset($_POST['student_id'])   ? trim($_POST['student_id'])   : '';
    $book          = isset($_POST['books'])        ? trim($_POST['books'])        : '';
    $borrow_date   = isset($_POST['borrowDate'])   ? trim($_POST['borrowDate'])   : '';
    $return_date   = isset($_POST['returnDate'])   ? trim($_POST['returnDate'])   : '';
    $fees          = isset($_POST['fees'])         ? trim($_POST['fees'])         : '';
    $token         = isset($_POST['token'])        ? trim($_POST['token'])        : '';

    // Load token JSON file
    $tokens_file = 'tokens.json';
    if (!file_exists($tokens_file)) {
        echo "Token file not found.";
        header("refresh: 3; url=index.php");
        exit;
    }

    $tokens_data = json_decode(file_get_contents($tokens_file), true);
    $valid_tokens = $tokens_data['tokens'];

    // Load usedtokens.json file
    $usedtokens_file = 'usedtokens.json';
    $used_tokens_data = [];

    if (file_exists($usedtokens_file)) {
        $used_tokens_data = json_decode(file_get_contents($usedtokens_file), true);
    }

    // Basic validation
    if (
        empty($student_name) || empty($student_id) || empty($book) ||
        empty($borrow_date) || empty($fees)
    ) {
        echo "All fields are required.";
        header("refresh: 3; url=index.php");
        exit;
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $student_name)) {
        echo "Invalid student name. Only letters and spaces are allowed.";
        header("refresh: 3; url=index.php");
        exit;
    }

    if (!preg_match("/^\d{2}-\d{5}-\d{1}$/", $student_id)) {
        echo "Invalid student ID. It should follow the pattern **-*****-*";
        header("refresh: 3; url=index.php");
        exit;
    }

    if (!preg_match("/^[\w.%+-]+@student\.aiub\.edu$/", $student_email)) {
        echo "Invalid email. It must end with @student.aiub.edu.";
        header("refresh: 3; url=index.php");
        exit;
    }

    $borrow_timestamp = strtotime($borrow_date);
    $return_timestamp = strtotime($return_date);

    if (!$borrow_timestamp || !$return_timestamp) {
        echo "Invalid date format. Please use a valid date.";
        header("refresh: 3; url=index.php");
        exit;
    }

    $date_diff = ($return_timestamp - $borrow_timestamp) / (60 * 60 * 24);

    if ($date_diff == 0) {
        echo "Return date cannot be the same as the borrow date.";
        header("refresh: 3; url=index.php");
        exit;
    }

    if ($date_diff < 0) {
        echo "Return date cannot be earlier than borrow date.";
        header("refresh: 3; url=index.php");
        exit;
    }

    // token is required
    if ($date_diff > 10) {
        if (empty($token) || !in_array((int)$token, $valid_tokens)) {
            echo "Return date exceeds 10 days. A valid token is required.";
            header("refresh: 3; url=index.php");
            exit;
        } else {
            $token_index = array_search((int)$token, $valid_tokens);
            if ($token_index !== false) {
                unset($valid_tokens[$token_index]);
                $valid_tokens = array_values($valid_tokens);
                $tokens_data['tokens'] = $valid_tokens;
                file_put_contents($tokens_file, json_encode($tokens_data));

                $used_tokens_data[] = (int)$token;
                file_put_contents($usedtokens_file, json_encode($used_tokens_data));
            }
        }
    }

    // Validate fees
    if (!is_numeric($fees) || floatval($fees) <= 0) {
        echo "Invalid fees. Please enter a valid positive number.";
        header("refresh: 3; url=index.php");
        exit;
    }

    // Check if the book is already borrowed
    $cookie_name = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $book);
    if (isset($_COOKIE[$cookie_name])) {
        echo "The book '" . $book . "' is already borrowed by " . $_COOKIE[$cookie_name] . ". Please wait until it is available.";
        header("refresh: 3; url=index.php");
        exit;
    } else {
        setcookie($cookie_name, $student_name, time() + 10, "/");
        echo "Book '" . $book . "' successfully borrowed by " . $student_name . ".<br>";
    }

    // output
    echo "Student Name: " . htmlspecialchars($student_name) . "<br>";
    echo "Student ID: " . htmlspecialchars($student_id) . "<br>";
    echo "Student Email: " . htmlspecialchars($student_email) . "<br>";
    echo "Book: " . htmlspecialchars($book) . "<br>";
    echo "Borrow Date: " . htmlspecialchars($borrow_date) . "<br>";
    echo "Return Date: " . htmlspecialchars($return_date) . "<br>";
    echo "Fees: " . htmlspecialchars(number_format((float)$fees, 2)) . "<br>";
}
