<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "u299560388_651138";
$password = "TM6534El";
$dbname = "u299560388_651138"; 

// Create connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // First, delete the corresponding records in Hobbies if any
    $deleteHobbies = $conn->prepare("DELETE FROM Hobbies WHERE HobbyID IN (SELECT HobbyID FROM Students WHERE ID = ?)");
    $deleteHobbies->bind_param("i", $id);
    $deleteHobbies->execute();

    // Now, delete the corresponding records in Subjects if any
    $deleteSubjects = $conn->prepare("DELETE FROM Subjects WHERE SubjectID = (SELECT SubjectID FROM Students WHERE ID = ?)");
    $deleteSubjects->bind_param("i", $id);
    $deleteSubjects->execute();

    // Prepare and bind the delete statement for Students
    $stmt = $conn->prepare("DELETE FROM Students WHERE ID = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the main page or display a success message
        $_SESSION['message'] = "ลบข้อมูลนักศึกษาเรียบร้อยแล้ว";
        header("Location: lab7menu.php"); // เปลี่ยน 'index.php' เป็นชื่อไฟล์ที่คุณใช้แสดงรายชื่อนักศึกษา
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "ID not provided.";
}

// Close connection
$conn->close();
?>
