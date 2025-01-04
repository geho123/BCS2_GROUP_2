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
    $newfacebook = $_POST['facebook'];
    $newIstagram = $_POST['instagram'];
    $newTwitter = $_POST['twitter'];
    $newYoutube = $_POST['youtube'];
    $newGithub = $_POST['github'];

    // Update the user's information in the database
    include 'database/db_connect.php';  // Reconnect to the database

    $updateSql = "UPDATE user SET facebook = ?, instagram = ?, twitter = ?, youtube = ?, github = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, "sssssi", $newfacebook, $newIstagram, $newTwitter, $newYoutube, $newGithub, $userID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Social Media Link updated successfully.";
        $_SESSION['toastClass'] = "#00ab41"; 
        header("location: setting.php");
       
    } else {
        $_SESSION['message'] = "Error updating social media link". mysqli_error($conn);
        $_SESSION['toastClass'] = "#c30010"; 
        header("location: setting.php");
    
    }

    // Close the connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>