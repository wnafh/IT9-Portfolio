<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

$conn->query("CREATE TABLE IF NOT EXISTS Projects_Tbl (
    Project_ID INT AUTO_INCREMENT PRIMARY KEY,
    Project_Title VARCHAR(100) NOT NULL,
    Project_Description TEXT NOT NULL,
    Project_Technologies VARCHAR(255) NOT NULL,
    Project_Image LONGBLOB,
    Project_Github VARCHAR(255)
)");

if (isset($_POST['save'])) {
    $id = $_POST['Project_ID'] ?? '';
    $title = $_POST['Project_Title'];
    $description = $_POST['Project_Description'];
    $technologies = $_POST['Project_Technologies'];
    $github = $_POST['Project_Github'];
    
   
    if ($_FILES['Project_Image']['size'] > 0) {
        $image = addslashes(file_get_contents($_FILES['Project_Image']['tmp_name']));
    } else {
        
        if ($id != "") {
            $result = $conn->query("SELECT Project_Image FROM Projects_Tbl WHERE Project_ID = $id");
            $row = $result->fetch_assoc();
            $image = $row['Project_Image'];
        } else {
            $image = '';
        }
    }

    if ($id == "") {
        $conn->query("INSERT INTO Projects_Tbl (Project_Title, Project_Description, Project_Technologies, Project_Image, Project_Github)
                      VALUES ('$title', '$description', '$technologies', '$image', '$github')");
        echo "<p style='color:green; text-align:center;'>Project added successfully!</p>";
    } else {
        if ($_FILES['Project_Image']['size'] > 0) {
            $conn->query("UPDATE Projects_Tbl 
                          SET Project_Title='$title',
                              Project_Description='$description',
                              Project_Technologies='$technologies',
                              Project_Image='$image',
                              Project_Github='$github'
                          WHERE Project_ID=$id");
        } else {
            $conn->query("UPDATE Projects_Tbl 
                          SET Project_Title='$title',
                              Project_Description='$description',
                              Project_Technologies='$technologies',
                              Project_Github='$github'
                          WHERE Project_ID=$id");
        }
        echo "<p style='color:green; text-align:center;'>Project updated successfully!</p>";
    }
}

if (isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $conn->query("DELETE FROM Projects_Tbl WHERE Project_ID=$id");
    echo "<p style='color:green; text-align:center;'>Project deleted successfully!</p>";
}

$edit_id = "";
$edit_title = "";
$edit_description = "";
$edit_technologies = "";
$edit_github = "";

if (isset($_POST['edit_id'])) {
    $edit_id = (int) $_POST['edit_id'];
    $result = $conn->query("SELECT * FROM Projects_Tbl WHERE Project_ID = $edit_id");
    $row = $result->fetch_assoc();
    $edit_title = $row['Project_Title'];
    $edit_description = $row['Project_Description'];
    $edit_technologies = $row['Project_Technologies'];
    $edit_github = $row['Project_Github'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <a href="dashboard.php">< Back to Dashboard</a>
    <hr>

    <h2 class="page-title"><?php echo $edit_id ? "Edit Project" : "Add New Project"; ?></h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="Project_ID" value="<?php echo $edit_id; ?>">

        <div class="form-group">
            <label>Project Title:</label>
            <input type="text" name="Project_Title" value="<?php echo $edit_title; ?>" placeholder="Enter project title" required>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="Project_Description" placeholder="Enter project description" rows="5" required><?php echo $edit_description; ?></textarea>
        </div>

        <div class="form-group">
            <label>Technologies (comma separated):</label>
            <input type="text" name="Project_Technologies" value="<?php echo $edit_technologies; ?>" placeholder="PHP, MySQL, JavaScript, etc." required>
        </div>

        <div class="form-group">
            <label>Project Image:</label>
            <input type="file" name="Project_Image" accept="image/*" <?php echo $edit_id ? '' : 'required'; ?>>
            <?php if($edit_id): ?>
                <small style="color: #666; display: block; margin-top: 5px;">Leave empty to keep existing image</small>
            <?php endif; ?>
        </div>

        <?php if($edit_id): ?>
            <?php
            $result = $conn->query("SELECT Project_Image FROM Projects_Tbl WHERE Project_ID = $edit_id");
            $row = $result->fetch_assoc();
            if($row['Project_Image']):
            ?>
            <div class="form-group">
                <label>Current Image:</label><br>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Project_Image']); ?>" 
                     width="200" style="border: 1px solid #ddd; border-radius: 5px; padding: 5px;">
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="form-group">
            <label>GitHub URL:</label>
            <input type="text" name="Project_Github" value="<?php echo $edit_github; ?>" placeholder="https://github.com/username/project">
        </div>

        <button type="submit" name="save" class="btn btn-primary">
            <?php echo $edit_id ? "Update Project" : "Save Project"; ?>
        </button>

        <?php if($edit_id): ?>
            <a href="projects.php" class="btn" style="background-color: #6c757d;">Cancel</a>
        <?php endif; ?>
    </form>

    <hr>

    <h2 class="page-title">Projects List</h2>

    
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Technologies</th>
                <th>GitHub</th>
                <th>Action</th>
            </tr>

            <?php
            $result = $conn->query("SELECT * FROM Projects_Tbl ORDER BY Project_ID DESC");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Project_ID'] . "</td>";
                echo "<td>";
                if ($row['Project_Image']) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['Project_Image']) . '" width="100" height="80" style="object-fit: cover; border-radius: 5px;">';
                } else {
                    echo '<i class="fas fa-code" style="font-size: 40px; color: #5a686e;"></i>';
                }
                echo "</td>";
                echo "<td>" . $row['Project_Title'] . "</td>";
                echo "<td>" . $row['Project_Technologies'] . "</td>";
                echo "<td>";
                if($row['Project_Github']) {
                    echo '<a href="' . $row['Project_Github'] . '" target="_blank">View</a>';
                } else {
                    echo 'N/A';
                }
                echo "</td>";
                echo "<td>
                        <form method='POST' style='display:inline'>
                            <input type='hidden' name='edit_id' value='" . $row['Project_ID'] . "'>
                            <button type='submit' class='btn-edit'>Edit</button>
                        </form>
                        
                        <form method='POST' style='display:inline' 
                              onsubmit='return confirm(\"Are you sure you want to delete this project?\");'>
                            <input type='hidden' name='delete_id' value='" . $row['Project_ID'] . "'>
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