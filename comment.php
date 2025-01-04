<?php
// Include database connection
include 'database/db_connect.php';

// Start session
session_start();
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $UserID = $_SESSION['UserID'];
    $PostID = intval($_POST['PostID']);
    $CommentContent = trim($_POST['CommentContent']);

    // Validate input
    if (!empty($CommentContent)) {
        $sql = "INSERT INTO comments (PostID, UserID, CommentContent) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iis", $PostID, $UserID, $CommentContent);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the feed page or wherever posts are displayed
            header("Location: feed.php");
            exit;
        } else {
            echo "Error: Unable to post comment.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Comment cannot be empty.";
    }
}

// Close connection
mysqli_close($conn);
?>
