<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ดำเนินการกับข้อมูลที่รับมา
    $prefix = $_POST['prefix'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $device = isset($_POST['device']) ? implode(", ", $_POST['device']) : 'None';
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // แสดงผลข้อมูลที่รับมา
    echo "<h1>ข้อมูลที่ได้รับ</h1>";
    echo "<p>คำนำหน้าชื่อ: $prefix</p>";
    echo "<p>ชื่อ-นามสกุล: $name</p>";
    echo "<p>เพศ: " . ($gender == 'male' ? 'ชาย' : 'หญิง') . "</p>";
    echo "<p>อุปกรณ์ที่ใช้: $device</p>";
    echo "<p>E-mail: $email</p>";
    echo "<p>Username: $username</p>";

    // ตรวจสอบความถูกต้องของรหัสผ่าน
    if ($password === $confirm_password) {
        echo "<p>Password: $password</p>";
    } else {
        echo "<p style='color: red;'>Password และ Confirm Password ไม่ตรงกัน</p>";
    }
} else {
    echo "ไม่มีการร้องขอแบบ POST ที่ถูกต้อง";
}
?>
