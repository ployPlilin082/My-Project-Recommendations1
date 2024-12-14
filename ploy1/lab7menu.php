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
    @font-face {
    font-family: 'Roboto';
    src: url('fonts/Roboto-Regular.eot');
    src: url('fonts/Roboto-Regular.eot?#iefix') format('embedded-opentype'),
         url('fonts/Roboto-Regular.woff2') format('woff2'),
         url('fonts/Roboto-Regular.woff') format('woff'),
         url('fonts/Roboto-Regular.ttf') format('truetype');
}

:root {
    --green-1: #a0c66c;
    --green-2: #83a259;
    --green-3: #5e8246;
    --yellow-1: #f2eb7a;
    --blue-1: #c9e2f0;
    --pink-1: #f4d6d8;
}

body {
    font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--green-2); /* ใช้โทนสีเขียวอ่อน */
    color: #333;
    line-height: 1.6;
}

.table-container {
    margin: 30px auto;
    padding: 40px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    max-width: 90%;
}

h2 {
    font-family: 'Roboto', serif;
    text-align: center;
    margin-bottom: 30px;
    color: var(--green-3); /* ใช้โทนสีเขียวเข้ม */
    background-color: var(--blue-1); /* สีฟ้าอ่อนภายใน */
    border-radius: 20px; /* ขอบมน */
    padding: 10px 20px; /* ระยะห่างภายใน */
    font-size: 24px; /* ขนาดตัวอักษรที่ใหญ่ขึ้น */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* เงาเพิ่มความลึก */
}


table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background-color: var(--blue-1); /* ใช้โทนสีฟ้า */
    color: white;
}

th, td {
    padding: 15px;
    text-align: center;
    border: 1px solid #eee;
}

tr:nth-child(even) {
    background-color: var(--yellow-1); /* ใช้โทนสีเหลือง */
}

tr:hover {
    background-color: var(--pink-1); /* ใช้โทนสีชมพูอ่อน */
}

.actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    cursor: pointer;
    color: white;
}

.btn-edit {
    background-color: var(--green-2); /* ใช้โทนสีเขียวกลาง */
}

.btn-edit:hover {
    background-color: var(--green-3); /* ใช้โทนสีเขียวเข้ม */
}

.btn-delete {
    background-color: #e76f51; /* สีเดิมของปุ่มลบ */
}

.btn-delete:hover {
    background-color: #cf6679; /* สีเดิมของปุ่มลบ */
}

.moustache {
    position: relative;
    width: 100px;
    height: 20px;
    background-color: var(--green-3); /* ใช้โทนสีเขียวเข้ม */
    border-radius: 50%;
    transform: rotate(45deg);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(45deg);
}

.moustache::before {
    content: "";
    position: absolute;
    width: 100px;
    height: 20px;
    background-color: var(--green-3); /* ใช้โทนสีเขียวเข้ม */
    border-radius: 50%;
    transform: rotate(45deg);
    top: 50%;
    left: -50%;
    transform: translate(0, -50%) rotate(45deg);
}

.moustache::after {
    content: "";
    position: absolute;
    width: 100px;
    height: 20px;
    background-color: var(--green-3); /* ใช้โทนสีเขียวเข้ม */
    border-radius: 50%;
    transform: rotate(45deg);
    top: 50%;
    left: 50%;
    transform: translate(100%, -50%) rotate(45deg);
}

@media (max-width: 768px) {
    .table-container {
        padding: 20px;
    }

    th, td {
        padding: 10px;
    }

    .btn {
        font-size: 14px;
    }
}


    
    </style>
</head>
<body>

<div class="table-container">
<h2 class="header-blue">รายการนักศึกษา</h2>
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
    <a href="students_hobbies.php" class="btn btn-secondary" title="ดูรายชื่อนักศึกษาและงานอดิเรก">
        <i class="fas fa-book"></i> ดูรายชื่อนักศึกษาและงานอดิเรก
    </a>
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
