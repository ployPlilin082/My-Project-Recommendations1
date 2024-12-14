<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>งานอดิเรก</title>

  <!-- ฟอนต์จาก Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Sarabun', sans-serif;
      background-color: #f0f8ff; /* สีพื้นหลังฟ้าอ่อน */
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #333;
      font-weight: 600;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin: 20px 0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    th, td {
      text-align: left;
      padding: 12px;
      font-size: 16px;
    }

    th {
      background-color: #1E90FF; /* สีหัวข้อตารางฟ้าเข้ม */
      color: white;
    }

    tr:nth-child(even) {
      background-color: #e0f7fa; /* สีแถวที่สอง */
    }

    tr:hover {
      background-color: #b0e0e6; /* สีแถวเมื่อเมาส์ชี้ */
    }

    td {
      border-bottom: 1px solid #ddd;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #1E90FF; /* สีฟ้าปุ่ม */
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
      font-weight: 600;
    }

    a:hover {
      background-color: #4682B4; /* สีฟ้าปุ่มเมื่อเมาส์ชี้ */
    }
  </style>
</head>
<body>

<h2>รายการงานอดิเรก</h2>

<?php
// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "u299560388_651138";
$password = "TM6534El";
$dbname = "u299560388_651138";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
  die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตั้งค่าภาษาให้รองรับ UTF-8
$conn->set_charset("utf8");

// คำสั่ง SQL เพื่อดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM StudentDetails";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // แสดงข้อมูลในตาราง
  echo "<table>";
  echo "<tr><th>ชื่อ-นามสกุล</th><th>งานอดิเรก</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row["StudentFullName"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["Hobby"]) . "</td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "ไม่มีข้อมูลในฐานข้อมูล";
}

// ปิดการเชื่อมต่อ
$conn->close();
?>

<!-- ลิงก์ไปยังหน้ารายชื่อนักศึกษา -->
<a href="p8.php">ไปที่หน้ารายชื่อนักศึกษา</a>

</body>
</html>
