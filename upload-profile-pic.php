<?php
// Start session
session_start();

// Include database connection
include 'database/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$message = "";
$upload_dir = "uploads/"; // Directory where images will be uploaded
$allowed_types = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types
$max_size = 5 * 1024 * 1024; // Max file size (5MB)

// Check if form is submitted (file uploaded)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];

    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = "Error uploading file.";
        $_SESSION['toastClass'] = "#c30010"; 
        header("Location: setting.php");
        exit;
    } elseif (!in_array($file['type'], $allowed_types)) {
        $_SESSION['message'] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        $_SESSION['toastClass'] = "#c30010"; 
        header("Location: setting.php");
        exit;
    } elseif ($file['size'] > $max_size) {
        $_SESSION['message'] = "File size exceeds the limit of 5MB.";
        $_SESSION['toastClass'] = "#c30010"; 
        header("Location: setting.php");
        exit;
    } else {
        // Generate a unique name for the uploaded file
        $file_name = uniqid('profile_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_path = $upload_dir . $file_name;

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Get user ID from session
            $userID = $_SESSION['UserID'];

            // Prepare SQL query to update the profile picture in the database
            $sql = "UPDATE user SET photo = ? WHERE UserID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, "si", $file_path, $userID);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = "Profile picture uploaded and saved to database successfully!";
                    $_SESSION['toastClass'] = "#00ab41"; // Success message
                    header("Location: setting.php");
                } else {
                    $_SESSION['message'] = "Error updating database.";
                    $_SESSION['toastClass'] = "#c30010"; // Error message
                    header("Location: setting.php");
                }

                // Close statement
                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['message'] = "Error preparing the SQL statement.";
                $_SESSION['toastClass'] = "#c30010"; // Error message
                header("Location: setting.php");
            }
        } else {
            $_SESSION['message'] = "Failed to move the uploaded file.";
            $_SESSION['toastClass'] = "#c30010"; // Error message
            header("Location: setting.php");
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>
