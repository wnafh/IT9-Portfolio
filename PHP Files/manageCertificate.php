<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username ="root";
$password = "";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

$conn->query("CREATE TABLE IF NOT EXISTS Certificate_Tbl (
    Certificate_ID INT AUTO_INCREMENT PRIMARY KEY,
    Certificate_Title VARCHAR(150) NOT NULL, 
    Certificate_Date VARCHAR(50) NOT NULL,
    Certificate_Image LONGBLOB NOT NULL 
)");

if (isset($_POST['save'])){
    $id = $_POST['Certificate_ID']?? ' ';
    $title = $_POST['Certificate_Title'];
    $date = $_POST['Certificate_Date'];
    $image = addslashes(file_get_contents($_FILES['Certificate_Image']['tmp_name']));

    if ($id == ""){
        $conn->query("INSERT INTO Certificate_Tbl (Certificate_Title, Certificate_Date, Certificate_Image)
                        VALUES ('$title', '$date', '$image')");
        echo "<p style= 'color:green; text-align:center;'> Certificate added successfully!</p>";
    }else{
        $conn->query("UPDATE Certificate_Tbl 
                        SET Certificate_Title ='$title',
                            Certificate_Date ='$date',
                            Certificate_Image ='$image'
                        wHERE Certificate_ID = $id ");
        echo "<p style= 'color:green; text-align:center;'>Certificate updated successfully!</p>";
    }
}

if (isset($_POST['delete_id'])){
    $id = (int) $_POST['delete_id'];
    $conn->query("DELETE FROM Certificate_Tbl WHERE Certificate_ID=$id");
    echo "<p style color:green; text-align:center;> Certificate deleted successfully!</p>";
}

$edit_id = "";
$edit_title = "";
$edit_date = "";

if (isset($_POST['edit_id'])){
    $edit_id = (int) $_POST['edit_id'];
    $result = $conn->query("SELECT * FROM Certificate_Tbl WHERE Certificate_ID = $edit_id");
    $row = $result->fetch_assoc();
    $edit_title = $row['Certificate_Title'];
    $edit_date = $row['Certificate_Date'];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Certificate</title>
    <link rel="stylesheet" href="style.css">


</head>
<body>

    <a href="dashboard.php">< Back to Dashboard</a>
    <hr>

    <h2 class="page-title"><?php echo $edit_id ?  "Edit Certificate" : "Add Certificate"; ?> </h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="Certificate_ID" value="<?php echo $edit_id; ?>">

        <div class="form-group">
            <label>Certificate Title:</label>
            <input type="text" name="Certificate_Title" value="<?php echo $edit_title; ?>" placeholder="Certificate Title" required>

        </div>
        <div class="form-group">
            <label>Date:</label>
            <input type="text" name="Certificate_Date" value="<?php echo $edit_date; ?>" required>

        </div>
        <div class="form-group">
            <label>Certificate Image:</label>
            <input type="file" name="Certificate_Image" required>

        </div>
        <button type="submit" name="save" class="btn btn-primary">
                <?php echo $edit_id ? "Update Certificate" : "Save Certificate"; ?>
            </button>
            
            <?php if($edit_id): ?>
                <a href="certificates.php" class="btn" style="background-color: #6c757d;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
    
    <hr>
    
    <h2 class="page-title">Certificates List</h2>
    
    <div style="max-width: 1200px; margin: 0 auto; padding: 20px; overflow-x: auto;">
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            
            <?php
            $result = $conn->query("SELECT * FROM Certificate_Tbl ORDER BY Certificate_Date DESC");
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Certificate_ID'] . "</td>";
                echo "<td>";
                if ($row['Certificate_Image']) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['Certificate_Image']) . '" width="100">';
                }
                echo "</td>";
                echo "<td>" . $row['Certificate_Title'] . "</td>";
                echo "<td>" . date('F d, Y', strtotime($row['Certificate_Date'])) . "</td>";
                echo "<td>
                        <form method='POST' style='display:inline'>
                            <input type='hidden' name='edit_id' value='" . $row['Certificate_ID'] . "'>
                            <button type='submit' class='btn-edit'>Edit</button>
                        </form>
                        
                        <form method='POST' style='display:inline' 
                              onsubmit='return confirm(\"Are you sure you want to delete this certificate?\");'>
                            <input type='hidden' name='delete_id' value='" . $row['Certificate_ID'] . "'>
                            <button type='submit' class='btn-delete'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
