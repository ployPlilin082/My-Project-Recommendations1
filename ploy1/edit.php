<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "u299560388_651138";
$password = "TM6534El";
$dbname = "u299560388_651138";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตารางที่ต้องการ
$sqlFaculty = "SELECT IDFaculty, Faculty FROM Faculty ORDER BY Faculty"; // เพิ่มการเรียงลำดับ
$resultFaculty = $conn->query($sqlFaculty);
if ($resultFaculty === false) {
    die("Error in Faculty query: " . $conn->error);
}

$sqlProgram = "SELECT IDProgram, Program FROM program ORDER BY Program"; // แก้ไขคำสั่ง SQL
$resultProgram = $conn->query($sqlProgram);
if ($resultProgram === false) {
    die("Error in Program query: " . $conn->error);
}

$sqlProvince = "SELECT ProvinceID, ProvinceName FROM Provinces ORDER BY ProvinceName"; // เพิ่มการเรียงลำดับ
$resultProvince = $conn->query($sqlProvince);
if ($resultProvince === false) {
    die("Error in Province query: " . $conn->error);
}

$sqlHobby = "SELECT HobbyID, HobbyName FROM Hobbies ORDER BY HobbyName"; // เพิ่มการเรียงลำดับ
$resultHobby = $conn->query($sqlHobby);
if ($resultHobby === false) {
    die("Error in Hobby query: " . $conn->error);
}

$sqlClassYear = "SELECT IDClassYear, ClassYear FROM ClassYear ORDER BY ClassYear"; // เพิ่มการเรียงลำดับ
$resultClassYear = $conn->query($sqlClassYear);
if ($resultClassYear === false) {
    die("Error in ClassYear query: " . $conn->error);
}

// ตรวจสอบว่ามีการส่ง ID ของนักศึกษามาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // ดึงข้อมูลนักศึกษาตาม ID
    $sql = "SELECT Title, EnglishName, IDFaculty, IDProgram, ProvinceID, HobbyID, IDClassYear FROM Students WHERE ID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("การเตรียมคำสั่ง SQL ผิดพลาด: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($title, $englishName, $idFaculty, $idProgram, $provinceID, $hobbyID, $classYear);
    
    if ($stmt->fetch()) {
        $student = [
            'Title' => $title,
            'EnglishName' => $englishName,
            'IDFaculty' => $idFaculty,
            'IDProgram' => $idProgram,
            'ProvinceID' => $provinceID,
            'HobbyID' => $hobbyID,
            'IDClassYear' => $classYear
        ];
    } else {
        echo "ไม่พบนักศึกษา";
        exit;
    }

    // Close the statement to free the result set
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // รับค่าจากฟอร์ม
        $title = $_POST['Title'];
        $englishName = $_POST['EnglishName'];
        $idFaculty = $_POST['IDFaculty'];
        $idProgram = $_POST['IDProgram'];
        $provinceID = $_POST['ProvinceID'];
        $hobbyID = $_POST['HobbyID'];
        $classYear = $_POST['IDClassYear'];
        
        // อัปเดตข้อมูลนักศึกษา
        $updateSql = "UPDATE Students SET Title = ?, EnglishName = ?, IDFaculty = ?, IDProgram = ?, ProvinceID = ?, HobbyID = ?, IDClassYear = ? WHERE ID = ?";
        $updateStmt = $conn->prepare($updateSql);
        
        if ($updateStmt === false) {
            die("การเตรียมคำสั่ง SQL อัปเดตผิดพลาด: " . $conn->error);
        }

        $updateStmt->bind_param("ssiiiiii", $title, $englishName, $idFaculty, $idProgram, $provinceID, $hobbyID, $classYear, $id);
        
        if ($updateStmt->execute()) {
            // อัปเดตสำเร็จแล้วให้รีไดเรกไปที่ lab7menu.php
            header("Location: lab7menu.php");
            exit;
        } else {
            echo "การอัปเดตผิดพลาด: " . $updateStmt->error;
        }
        
        // Close the update statement
        $updateStmt->close();
    }
} else {
    echo "ไม่มี ID ของนักศึกษา";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลนักศึกษา</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
    body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            color: #343a40;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header-decor {
            width: 50px;
            height: 5px;
            background-color: #28a745;
            margin: 10px auto;
            border-radius: 5px;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #28a745; /* สีเขียว */
            border-color: #28a745; /* สีเขียว */
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit student information</h2>
        <form method="post">
            <div class="form-group">
                <label>คำนำหน้า</label>
                <input type="text" name="Title" class="form-control" value="<?php echo htmlspecialchars($student['Title']); ?>">
            </div>
            <div class="form-group">
                <label>ชื่อภาษาอังกฤษ</label>
                <input type="text" name="EnglishName" class="form-control" value="<?php echo htmlspecialchars($student['EnglishName']); ?>">
            </div>
            <div class="form-group">
                <label>คณะ</label>
                <select name="IDFaculty" class="form-control">
                    <?php while($row = $resultFaculty->fetch_assoc()): ?>
                        <option value="<?php echo $row['IDFaculty']; ?>" <?php if($student['IDFaculty'] == $row['IDFaculty']) echo 'selected'; ?>>
                            <?php echo $row['Faculty']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>สาขา</label>
                <select name="IDProgram" class="form-control">
                    <?php while($row = $resultProgram->fetch_assoc()): ?>
                        <option value="<?php echo $row['IDProgram']; ?>" <?php if($student['IDProgram'] == $row['IDProgram']) echo 'selected'; ?>>
                            <?php echo $row['Program']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>จังหวัด</label>
                <select name="ProvinceID" class="form-control">
                    <?php while($row = $resultProvince->fetch_assoc()): ?>
                        <option value="<?php echo $row['ProvinceID']; ?>" <?php if($student['ProvinceID'] == $row['ProvinceID']) echo 'selected'; ?>>
                            <?php echo $row['ProvinceName']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>งานอดิเรก</label>
                <select name="HobbyID" class="form-control">
                    <?php while($row = $resultHobby->fetch_assoc()): ?>
                        <option value="<?php echo $row['HobbyID']; ?>" <?php if($student['HobbyID'] == $row['HobbyID']) echo 'selected'; ?>>
                            <?php echo $row['HobbyName']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>ชั้นปี</label>
                <select name="IDClassYear" class="form-control">
                    <?php while($row = $resultClassYear->fetch_assoc()): ?>
                        <option value="<?php echo $row['IDClassYear']; ?>" <?php if($student['IDClassYear'] == $row['IDClassYear']) echo 'selected'; ?>>
                            <?php echo $row['ClassYear']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        </form>
    </div>
</body>
</html>
