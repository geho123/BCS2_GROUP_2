<?php
// Include file to connect to the database
include 'database/db_connect.php';

// Declare variables and initialize with empty values
$message = "";
$toastClass = "";
$currentPassword = "";
$newPassword = "";
$confirmNewPassword = "";

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "You need to log in to view this page.";
    exit;
}

// Retrieve the user ID from the session
$userID = $_SESSION['email'];

// Processing form data after submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate current password
    if (empty(trim($_POST["currentPassword"]))) {
        $message = "Please enter your current password.";
        $toastClass = "#c30010"; // Warning color
        header("Location: setting.php");
    } else {
        $currentPassword = trim($_POST["currentPassword"]);
    }

    // Validate new password
    if (empty(trim($_POST["newPassword"]))) {
        $message = "Please enter a new password.";
        $toastClass = "#c30010"; // Warning color
        header("Location: setting.php");
    } elseif (strlen(trim($_POST["newPassword"])) < 8) {
        $message = "Password must have at least 8 characters.";
        $toastClass = "#c30010"; // Warning color
        header("Location: setting.php");
    } else {
        $newPassword = trim($_POST["newPassword"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirmNewPassword"]))) {
        $message = "Please confirm the new password.";
        $toastClass = "#c30010"; // Warning color
        header("Location: setting.php");
    } else {
        $confirmNewPassword = trim($_POST["confirmNewPassword"]);
        if ($newPassword != $confirmNewPassword) {
            $message = "Passwords do not match.";
            $toastClass = "#c30010"; // Error color
            header("Location: setting.php");
        }
    }

    // Check input errors before updating the password
    if (empty($message)) {
        // Retrieve the current password from the database
        $sql = "SELECT password FROM user WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $email);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if the user exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $storedPassword);

                    // Fetch the result
                    mysqli_stmt_fetch($stmt);

                    // Verify the current password entered by the user
                    if (password_verify($currentPassword, $storedPassword)) {
                        // Password match, now update the password
                        $updateSql = "UPDATE user SET password = ? WHERE email = ?";
                        if ($updateStmt = mysqli_prepare($conn, $updateSql)) {
                            // Bind variables
                            mysqli_stmt_bind_param($updateStmt, "si", $param_newPassword, $email);

                            // Set the new password after hashing it
                            $param_newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                            // Attempt to execute the update query
                            if (mysqli_stmt_execute($updateStmt)) {
                                $message = "Password updated successfully.";
                                $toastClass = "#4caf50"; // Success color
                                header("Location: setting.php");
                            } else {
                                $message = "Oops! Something went wrong. Please try again later.";
                                $toastClass = "#c30010"; // Error color
                                header("Location: setting.php");
    
                            }

                            // Close statement
                            mysqli_stmt_close($updateStmt);
                        }
                    } else {
                        // Current password does not match
                        $message = "Current password is incorrect.";
                        $toastClass = "#c30010"; // Error color
                        header("Location: setting.php");
                    }
                }
            } else {
                $message = "Oops! Something went wrong. Please try again later.";
                $toastClass = "#c30010"; // Error color
                header("Location: setting.php");
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>