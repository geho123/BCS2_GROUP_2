<?php
// Start session
session_start();

// Include database connection
include 'database/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to like posts.']);
    exit;
}

// Validate POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postID'])) {
    $userID = intval($_SESSION['UserID']);
    $postID = intval($_POST['postID']);

    // Check if the user has already liked the post
    $checkSql = "SELECT * FROM likes WHERE UserID = ? AND PostID = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $userID, $postID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already liked, remove the like
        $deleteSql = "DELETE FROM likes WHERE UserID = ? AND PostID = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("ii", $userID, $postID);
        $deleteStmt->execute();
        $action = "unliked";
    } else {
        // Add a new like
        $insertSql = "INSERT INTO likes (UserID, PostID) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ii", $userID, $postID);
        $insertStmt->execute();
        $action = "liked";
    }

    // Get the updated total likes
    $likeCountSql = "SELECT COUNT(*) AS totalLikes FROM likes WHERE PostID = ?";
    $countStmt = $conn->prepare($likeCountSql);
    $countStmt->bind_param("i", $postID);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $likeRow = $countResult->fetch_assoc();
    $totalLikes = $likeRow['totalLikes'];

    // Return success response
    echo json_encode(['success' => true, 'action' => $action, 'totalLikes' => $totalLikes]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

// Close the database connection

?>
