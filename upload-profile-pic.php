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
$user_id = $_SESSION['UserID'];
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profile/';
        $fileName = uniqid() . '-' . basename($_FILES['profile_picture']['name']);
        $uploadFile = $uploadDir . $fileName;

        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            // Save file path to the database
            $filePath = $conn->real_escape_string($uploadFile);
            $sql = "UPDATE user SET photo = '$filePath' WHERE UserID = '$user_id'";

            if ($conn->query($sql) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = 'Profile picture uploaded successfully!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Database error: ' . $conn->error;
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to move uploaded file.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No file uploaded or an error occurred.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>
