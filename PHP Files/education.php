<?php
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

$education = $conn->query("SELECT * FROM Education_Tbl ORDER BY Education_YearStart DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">Home</a>
            <a href="projects.php">Projects</a>
            <a href="education.php">Education</a>
            <a href="certificate.php">Certificates</a>
            
            <a href="https://www.instagram.com/_ial.wena/" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>
            
            <a href="https://github.com/wnafh" target="_blank">
                <i class="fab fa-github"></i>
            </a>
            
            <a href="mailto:w.magayawa.554012@umindanao.edu.ph">
                <i class="far fa-envelope"></i>
            </a>

            <a href="login.php">Login</a>
        </div>
    </div>

    <div class="education-page">
        <h1 class="page-title">Education</h1>
        
        <div class="timeline">
            <?php while($row = $education->fetch_assoc()): ?>
            <div class="timeline-item">
                <div class="timeline-year">
                    <?php echo $row['Education_YearStart']; ?> - 
                    <?php echo $row['Education_YearEnd'] ? $row['Education_YearEnd'] : 'Present'; ?>
                </div>
                <div class="timeline-content">
                    <?php if($row['Education_Image']): ?>
                        <div class="education-image">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Education_Image']); ?>" 
                                 alt="<?php echo $row['Education_Institution']; ?>">
                        </div>
                    <?php else: ?>
                        <div class="education-image">
                            <i class="fas fa-university" style="font-size: 50px; color: #5a686e;"></i>
                        </div>
                    <?php endif; ?>
                    <div class="education-details">
                        <h2><?php echo $row['Education_Degree']; ?></h2>
                        <h3><?php echo $row['Education_Institution']; ?></h3>
                        <p><?php echo $row['Education_Description']; ?></p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
