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

// Fetch student data with the modified query
$sql = "
    SELECT 
        S.ID,
        S.Title,
        S.EnglishName AS StudentEnglishName, 
        F.Faculty AS Faculty, 
        P.Program AS Program, 
        Prov.ProvinceName AS Province, 
        C.ClassYear AS ClassYear,
        H.HobbyName AS HobbyName
    FROM 
        Students S
    JOIN 
        Faculty F ON S.IDFaculty = F.IDFaculty
    JOIN 
        program P ON S.IDprogram = P.IDprogram
    JOIN 
        Provinces Prov ON S.ProvinceID = Prov.ProvinceID
    JOIN 
        ClassYear C ON S.IDClassYear = C.IDClassYear
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
    <title>Student Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            font-family: 'Open Sans', sans-serif, Helvetica, Arial;
            background-color: #f4f4f4;
            line-height: 1.5;
        }
        .table-container {
            margin: 20px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 90%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background-color: #87ceeb;
            color: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn {
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-edit {
            background-color: #f0ad4e;
            color: white;
        }
        .btn-edit:hover {
            background-color: #ec971f;
        }
        .btn-delete {
            background-color: #d9534f;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<div class="table-container">
    <h2>รายการนักศึกษา</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>รหัส</th>
                <th>คำนำหน้า</th>
                <th>ชื่อ</th>
                <th>คณะ</th>
                <th>สาขา</th>
                <th>จังหวัด</th>
                <th>งานอดิเรก</th>
                <th>ชั้นปี</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Title']); ?></td> <!-- Ensure Title is in the SQL query -->
                        <td><?php echo htmlspecialchars($row['StudentEnglishName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Faculty']); ?></td>
                        <td><?php echo htmlspecialchars($row['Program']); ?></td>
                        <td><?php echo htmlspecialchars($row['Province']); ?></td>
                        <td><?php echo htmlspecialchars($row['HobbyName']); ?></td>
                        <td><?php echo htmlspecialchars($row['ClassYear']); ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $row['ID']; ?>" class="btn btn-edit" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?php echo $row['ID']; ?>" class="btn btn-delete" title="ลบ" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">ไม่พบข้อมูลนักศึกษา</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="text-center">
        <a href="form_insert.php" class="btn btn-primary" title="เพิ่มนักศึกษาใหม่">
            <i class="fas fa-plus"></i> เพิ่มนักศึกษาใหม่
        </a>
    </div>
</div>

</body>  
</html>

<?php
// Close connection
$conn->close();
?>
