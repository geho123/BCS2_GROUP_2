<?php 

// Include config file
include 'database/db_connect.php';

// Initialize the variable
$message = "";
$toastClass = "";
// Start session
session_start();
// Retrieve the user ID from the session
$userID = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

if (is_null($userID)) {
    echo "User Id is not set in the session.";
    exit;
}



//UPDATING USER INFO
// Handle form submission to update user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated data from the form
    $newUserName = $_POST['username'];
    $newEmail = $_POST['email'];
    $newBio = $_POST['bio'];
    $newGender = $_POST['gender'];
    $newRelationship = $_POST['relationship'];

    // Update the user's information in the database
    include 'database/db_connect.php';  // Reconnect to the database

    $updateSql = "UPDATE user SET username = ?, email = ?, bio = ?, gender = ?, relationship = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, "sssssi", $newUserName, $newEmail, $newBio, $newGender, $newRelationship, $userID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Profile updated successfully.";
        $_SESSION['toastClass'] = "#00ab41"; 
        header("location: setting.php");
       
    } else {
        $_SESSION['message'] = "Error updating profile". mysqli_error($conn);
        $_SESSION['toastClass'] = "#c30010"; 
        header("location: setting.php");
    }

    // Close the connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>