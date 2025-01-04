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
$toastClass = "";

// Process the form data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userID = $_SESSION['UserID'];
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmNewPassword']);

    // Validate form input
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['toastClass'] = "#c30010"; 
        header("location: setting.php");
       
    } elseif (strlen($newPassword) < 8) {
        $_SESSION['message'] = "New password must be at least 8 characters.";
        $_SESSION['toastClass'] = "#c30010"; 
        header("location: setting.php");
     
    } elseif ($newPassword !== $confirmPassword) {
        $_SESSION['message'] = "New password and confirm password do not match.";
        $_SESSION['toastClass'] = "#c30010"; 
        header("location: setting.php");
    } else {
        // Fetch the current password from the database
        $sql = "SELECT password FROM user WHERE UserID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hashedPassword);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Verify the current password
        if (!password_verify($currentPassword, $hashedPassword)) {
            $_SESSION['message'] = "Current password is incorrect.";
            $_SESSION['toastClass'] = "#c30010"; 
            header("location: setting.php");
        } else {
            // Update the password
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET password = ? WHERE UserID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $newHashedPassword, $userID);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Password updated successfully.";
                $_SESSION['toastClass'] = "#00ab41"; 
                header("location: setting.php");
            } else {
                $_SESSION['message'] = "An error occurred. Please try again.";
                $_SESSION['toastClass'] = "#c30010"; 
                header("location: setting.php");
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);

    // Redirect with message
    header("Location: setting.php?message=" . urlencode($message) . "&toastClass=" . urlencode($toastClass));
    exit;
}
?>
