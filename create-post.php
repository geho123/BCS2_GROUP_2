<?php
// Include database connection
include 'database/db_connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$message = "";
$toastClass = "";

// Handle the post submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_post'])) {
    $userID = $_SESSION['UserID']; // Get user ID from session
    $postContent = trim($_POST['post_content']); // Post content

    // Validate the post content
    if (empty($postContent)) {
        $_SESSION['message'] = "Please enter some text for your post.";
        $_SESSION['toastClass'] = "#c30010"; 
        header("Location: feed.php");
        exit;
    } else {
        // Handle image upload (optional)
        $postImage = null;
        if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // Max size 5MB

            if (!in_array($_FILES['post_image']['type'], $allowed_types)) {
                $_SESSION['message'] = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
                $_SESSION['toastClass'] = "#c30010"; 
                header("Location: feed.php");
                exit;
            } elseif ($_FILES['post_image']['size'] > $max_size) {
                $_SESSION['message'] = "Image size exceeds the limit of 5MB.";
                $_SESSION['toastClass'] = "#c30010"; 
                header("Location: feed.php");
                exit;
            } else {
                // Move the uploaded image to the server
                $upload_dir = "uploads/posts/";
                $image_name = uniqid('post_', true) . '.' . pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION);
                $image_path = $upload_dir . $image_name;

                if (move_uploaded_file($_FILES['post_image']['tmp_name'], $image_path)) {
                    $postImage = $image_name; // Store the image file name in the database
                } else {
                    $_SESSION['message'] = "Failed to upload image.";
                    $_SESSION['toastClass'] = "#c30010"; 
                    header("Location: feed.php");
                    exit;
                }
            }
        }

        // If no errors, insert the post into the database
        if (empty($message)) {
            $sql = "INSERT INTO posts (UserID, PostContent, PostImage) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iss", $userID, $postContent, $postImage);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Post created successfully!";
                $_SESSION['toastClass'] = "#00ab41"; 
                header("Location: feed.php");
                exit;
            } else {
                $_SESSION['message'] = "Failed to create post.";
                $_SESSION['toastClass'] = "#c30010"; 
                header("Location: feed.php");
            }

            mysqli_stmt_close($stmt);
        }
    }
}

// Close the connection
mysqli_close($conn);
?>
