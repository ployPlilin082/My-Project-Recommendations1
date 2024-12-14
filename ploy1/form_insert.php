<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Prepare SQL queries for dropdown options
$faculties = $conn->query("SELECT * FROM Faculty ORDER BY IDFaculty ASC");
$hobbies = $conn->query("SELECT * FROM Hobbies ORDER BY HobbyName ASC");
$programs = $conn->query("SELECT * FROM program ORDER BY Program ASC");
$classYears = $conn->query("SELECT * FROM ClassYear ORDER BY IDClassYear ASC");

// Check for errors in hobbies query
if (!$hobbies) {
    die("Hobbies Query failed: " . $conn->error);
}

// Handle form submission ONLY IF the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["InsertNewStudent"]) && $_POST["InsertNewStudent"] === "Yes") {
    // Get values from the form
    $Title = $_POST['initial'];
    $EnglishName = $_POST['name'];
    $IDFaculty = $_POST['faculty'];
    $ProgramID = $_POST['program'];
    $ProvinceID = $_POST['Province'];
    $HobbyID_ids = $_POST['Hobby']; // This is an array due to multiple selection
    $IDClassYear = $_POST['ClassYear'];

    // Check if IDClassYear is set and not empty
    if (empty($IDClassYear)) {
        echo '<script>alert("กรุณาเลือกปีการศึกษา");</script>';
    } else {
        // Join hobbies into a string separated by commas
        $HobbyID_str = implode(", ", $HobbyID_ids);

        // Check if IDFaculty exists
        $facultyCheck = $conn->prepare("SELECT COUNT(*) FROM Faculty WHERE IDFaculty = ?");
        $facultyCheck->bind_param("s", $IDFaculty);
        $facultyCheck->execute();
        $facultyCheck->bind_result($facultyCount);
        $facultyCheck->fetch();
        $facultyCheck->close();

        // Check if IDClassYear exists
        $classYearCheck = $conn->prepare("SELECT COUNT(*) FROM ClassYear WHERE IDClassYear = ?");
        $classYearCheck->bind_param("s", $IDClassYear);
        $classYearCheck->execute();
        $classYearCheck->bind_result($classYearCount);
        $classYearCheck->fetch();
        $classYearCheck->close();

        // Validate foreign keys
        if ($facultyCount == 0) {
            echo '<script>alert("คณะไม่ถูกต้อง");</script>';
        } elseif ($classYearCount == 0) {
            echo '<script>alert("ปีการศึกษาไม่ถูกต้อง");</script>';
        } else {
            // Prepare and bind the SQL statement
            $stmt = $conn->prepare("INSERT INTO Students (Title, EnglishName, IDFaculty, IDProgram, ProvinceID, HobbyID, IDClassYear) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssssss", $Title, $EnglishName, $IDFaculty, $ProgramID, $ProvinceID, $HobbyID_str, $IDClassYear);
                // Execute and check for success
                if ($stmt->execute()) {
                    echo '<script>
                            alert("เพิ่มนักศึกษาใหม่สำเร็จ");
                            window.location = "lab7menu.php"; // Redirect after success
                          </script>';
                } else {
                    echo '<script>alert("เกิดข้อผิดพลาด: ' . $stmt->error . '");</script>';
                }
            } else {
                echo "Prepare failed: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add New Student</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

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
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #28a745; /* สีเขียว */
            border-color: #28a745; /* สีเขียว */
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        h2 {
            color: #343a40;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }
        .header-decor {
            width: 50px;
            height: 5px;
            background-color: #28a745;
            margin: 10px auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container"> 
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">ADD New Student</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="initial">คำนำหน้า</label><br>
                    <input type="radio" name="initial" value="Mr." checked> Mr.
                    <input type="radio" name="initial" value="Ms."> Ms.
                </div>
                <div class="form-group">
                    <label for="name">ชื่อ</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="faculty">คณะ</label>
                    <select name="faculty" class="form-control" required>
                        <option value="">เลือกคณะ</option>
                        <?php while($row = $faculties->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['IDFaculty']); ?>"><?php echo htmlspecialchars($row['Faculty']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="program">สาขา</label>
                    <select name="program" class="form-control" required>
                        <option value="">เลือกสาขา</option>
                        <?php while($row = $programs->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['IDprogram']); ?>"><?php echo htmlspecialchars($row['Program']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Province">จังหวัด</label>
                    <select name="Province" class="form-control" required>
                        <option value="">เลือกจังหวัด</option>
                        <?php
                        // Assume $provincesByName is a query to get provinces; include this query as necessary
                        $provincesByName = $conn->query("SELECT * FROM Provinces ORDER BY ProvinceName ASC");
                        if ($provincesByName) {
                            while ($row = $provincesByName->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['ProvinceID']); ?>"><?php echo htmlspecialchars($row['ProvinceName']); ?></option>
                            <?php endwhile;
                        } else {
                            echo '<option value="">ไม่มีจังหวัด</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Hobby">งานอดิเรก</label>
                    <select name="Hobby[]" class="form-control select2" multiple required>
                        <option value="">เลือกงานอดิเรก</option>
                        <?php if ($hobbies->num_rows > 0): ?>
                            <?php while($row = $hobbies->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['HobbyID']); ?>"><?php echo htmlspecialchars($row['HobbyName']); ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">ไม่มีงานอดิเรก</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ClassYear">ปีการศึกษา</label>
                    <select name="ClassYear" class="form-control" required>
                        <option value="">เลือกปีการศึกษา</option>
                        <?php while($row = $classYears->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['IDClassYear']); ?>"><?php echo htmlspecialchars($row['ClassYear']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <input type="hidden" name="InsertNewStudent" value="Yes">
                <button type="submit" class="btn btn-primary">เพิ่มนักศึกษาใหม่</button>
                <a href="lab7menu.php" class="btn btn-secondary">ย้อนกลับ</a>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for Hobby
        $('select[name="Hobby[]"]').select2({
            placeholder: "เลือกงานอดิเรก",
            allowClear: true
        });
    });
</script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
