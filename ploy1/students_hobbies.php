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

// Fetch only student names and hobbies
$sql = "
    SELECT 
        S.EnglishName AS StudentEnglishName, 
        H.HobbyName AS HobbyName
    FROM 
        Students S
    LEFT JOIN 
        Hobbies H ON S.HobbyID = H.HobbyID
    ORDER BY 
        S.EnglishName ASC;
";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Names and Hobbies</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        :root {
            --green-1: #a0c66c;
            --green-2: #83a259;
            --green-3: #5e8246;
            --yellow-1: #f2eb7a;
            --blue-1: #c9e2f0;
            --pink-1: #f4d6d8;
        }
        
        /* Add some basic styling to the table */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        /* Apply the custom CSS variables */
        thead tr {
            background-color: var(--green-1);
            color: var(--blue-1);
     }
        
        tbody tr:nth-child(even) {
            background-color: var(--green-2);
        }
        
        tbody tr:nth-child(odd) {
            background-color: var(--green-3);
        }
        
        .container {
            background-color: var(--pink-1);
            padding: 20px;
        }
        .btn-secondary {
    background-color: var(--green-1);
    color: var(--blue-1);
}
    </style>
</head>
<body>


<div class="container">
    <h2 class="text-center mt-4">List of students and hobbies</h2>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ชื่อ</th>
                <th>งานอดิเรก</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['StudentEnglishName']); ?></td>
                        <td><?php echo htmlspecialchars($row['HobbyName'] ? $row['HobbyName'] : "ไม่มีงานอดิเรก"); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">ไม่พบข้อมูลนักศึกษา</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="text-center mt-4">
        <a href="lab7menu.php" class="btn btn-secondary">ย้อนกลับ</a>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
