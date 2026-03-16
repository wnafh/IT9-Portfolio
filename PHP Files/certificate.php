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

$certificates = $conn->query("SELECT * FROM Certificate_Tbl ORDER BY Certificate_Date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates</title>
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

    <div class="certificates-page">
        <h1 class="page-title">Certificates</h1>
        
        <div class="certificates-grid">
            <?php while($row = $certificates->fetch_assoc()): ?>
            <div class="certificate-card">
                <div class="certificate-image">
                    <?php if($row['Certificate_Image']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Certificate_Image']); ?>" 
                             alt="<?php echo $row['Certificate_Title']; ?>" style="max-width: 100%; height: auto; border-radius: 5px;">
                    <?php else: ?>
                        <i class="fas fa-certificate" style="font-size: 64px; color: #5a686e;"></i>
                    <?php endif; ?>
                </div>
                <div class="certificate-info">
                    <h2><?php echo $row['Certificate_Title']; ?></h2>
                    <p class="certificate-date">
                        <?php echo date('F Y', strtotime($row['Certificate_Date'])); ?>
                    </p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>