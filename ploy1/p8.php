<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แสดงข้อมูลนักศึกษา</title>

  <!-- ฟอนต์จาก Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Sarabun', sans-serif;
      background-color: #f0f8ff; /* เปลี่ยนเป็นสีฟ้าอ่อน */
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
      background-color: #1E90FF; /* เปลี่ยนเป็นสีฟ้าเข้ม */
      color: white;
    }

    tr:nth-child(even) {
      background-color: #e0f7fa; /* สีฟ้าอ่อน */
    }

    tr:hover {
      background-color: #b0e0e6; /* สีฟ้าพาสเทลเมื่อเมาส์ชี้ */
    }

    td {
      border-bottom: 1px solid #ddd;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #1E90FF; /* ปุ่มเป็นสีฟ้าเข้ม */
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
      font-weight: 600;
    }

    a:hover {
      background-color: #4682B4; /* เปลี่ยนสีปุ่มเป็นฟ้าเข้มขึ้นเมื่อเมาส์ชี้ */
    }
  </style>
</head>
<body>

<h2>รายชื่อนักศึกษา</h2>

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
  echo "<tr><th>ชื่อนำหน้า</th><th>ชื่อ-นามสกุล</th><th>ชั้นปี</th><th>ภูมิลำเนา</th><th>รายวิชาที่ลงทะเบียน</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row["StudentTitle"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["StudentFullName"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["Year"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["Province"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["Subject"]) . "</td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "ไม่มีข้อมูลในฐานข้อมูล";
}

// ปิดการเชื่อมต่อ
$conn->close();
?>

<!-- ลิงก์ไปยังหน้างานอดิเรก -->
<a href="p9.php">ไปที่หน้ารายการงานอดิเรก</a>

</body>
</html>
