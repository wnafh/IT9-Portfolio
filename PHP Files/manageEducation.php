<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "portfolio_db";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

$conn->query("CREATE TABLE IF NOT EXISTS Education_Tbl (
    Education_ID INT AUTO_INCREMENT PRIMARY KEY,
    Education_Degree VARCHAR(100) NOT NULL,
    Education_Institution VARCHAR(150) NOT NULL,
    Education_YearStart YEAR NOT NULL,
    Education_YearEnd YEAR,
    Education_Description TEXT,
    Education_Image LONGBLOB NOT NULL
)");

if (isset($_POST['save'])) {
    $id = $_POST['Education_ID'] ?? '';
    $degree = $_POST['Education_Degree'];
    $institution = $_POST['Education_Institution'];
    $year_start = $_POST['Education_YearStart'];
    $year_end = $_POST['Education_YearEnd'];
    $description = $_POST['Education_Description'];
    $image = addslashes(file_get_contents($_FILES['Education_Image']['tmp_name']));

    if ($id == "") {
        $conn->query("INSERT INTO Education_Tbl 
                     (Education_Degree, Education_Institution, Education_YearStart, Education_YearEnd, Education_Description, Education_Image)
                      VALUES ('$degree', '$institution', '$year_start', '$year_end', '$description', '$image')");
        echo "<p style='color:green; text-align:center;'>Education added successfully!</p>";
    } else {
        $conn->query("UPDATE Education_Tbl 
                      SET Education_Degree='$degree',
                          Education_Institution='$institution',
                          Education_YearStart='$year_start',
                          Education_YearEnd='$year_end',
                          Education_Description='$description',
                          Education_Image='$image'
                      WHERE Education_ID=$id");
        echo "<p style='color:green; text-align:center;'>Education updated successfully!</p>";
    }
}

if (isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $conn->query("DELETE FROM Education_Tbl WHERE Education_ID=$id");
    echo "<p style='color:green; text-align:center;'>Education deleted successfully!</p>";
}

$edit_id = "";
$edit_degree = "";
$edit_institution = "";
$edit_year_start = "";
$edit_year_end = "";
$edit_description = "";

if (isset($_POST['edit_id'])) {
    $edit_id = (int) $_POST['edit_id'];
    $result = $conn->query("SELECT * FROM Education_Tbl WHERE Education_ID = $edit_id");
    $row = $result->fetch_assoc();
    $edit_degree = $row['Education_Degree'];
    $edit_institution = $row['Education_Institution'];
    $edit_year_start = $row['Education_YearStart'];
    $edit_year_end = $row['Education_YearEnd'];
    $edit_description = $row['Education_Description'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Education</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <a href="dashboard.php">< Back to Dashboard</a>
    <hr>

    <h2 class="page-title"><?php echo $edit_id ? "Edit Education" : "Add Education"; ?></h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="Education_ID" value="<?php echo $edit_id; ?>">

        <div class="form-group">
            <label>Degree/Year Level:</label>
            <input type="text" name="Education_Degree" value="<?php echo $edit_degree; ?>" placeholder="Bachelor of Science in Information Technology" required>
        </div>

        <div class="form-group">
            <label>Institution:</label>
            <input type="text" name="Education_Institution" value="<?php echo $edit_institution; ?>" placeholder="University of Mindanao" required>
        </div>

        <div class="form-group">
            <label>Start Year:</label>
            <input type="text" name="Education_YearStart" value="<?php echo $edit_year_start; ?>" placeholder="2022" required>
        </div>

        <div class="form-group">
            <label>End Year:</label>
            <input type="text" name="Education_YearEnd" value="<?php echo $edit_year_end; ?>" placeholder="2026 or leave empty if present">
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="Education_Description" placeholder="Description" rows="4"><?php echo $edit_description; ?></textarea>
        </div>

        <div class="form-group">
            <label>Institution Image:</label>
            <input type="file" name="Education_Image" required>
        </div>

        <button type="submit" name="save" class="btn btn-primary">
            <?php echo $edit_id ? "Update Education" : "Save Education"; ?>
        </button>

        <?php if($edit_id): ?>
            <a href="education.php" class="btn" style="background-color: #6c757d;">Cancel</a>
        <?php endif; ?>
    </form>

    <hr>

    <h2 class="page-title">Education List</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Degree</th>
            <th>Institution</th>
            <th>Years</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM Education_Tbl ORDER BY Education_YearStart DESC");

        while ($row = $result->fetch_assoc()) {
            $years = $row['Education_YearStart'] . " - " . ($row['Education_YearEnd'] ?: 'Present');
            echo "<tr>";
            echo "<td>" . $row['Education_ID'] . "</td>";
            echo "<td>";
            if ($row['Education_Image']) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['Education_Image']) . '" width="100">';
            }
            echo "</td>";
            echo "<td>" . $row['Education_Degree'] . "</td>";
            echo "<td>" . $row['Education_Institution'] . "</td>";
            echo "<td>" . $years . "</td>";
            echo "<td>
                    <form method='POST' style='display:inline'>
                        <input type='hidden' name='edit_id' value='" . $row['Education_ID'] . "'>
                        <button type='submit' class='btn-edit'>Edit</button>
                    </form>
                    
                    <form method='POST' style='display:inline' 
                          onsubmit='return confirm(\"Are you sure you want to delete this education?\");'>
                        <input type='hidden' name='delete_id' value='" . $row['Education_ID'] . "'>
                        <button type='submit' class='btn-delete'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>
<?php $conn->close(); ?>