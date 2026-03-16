<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
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

   <?php
    $fname = "WENAFE";
    $lname = "MAGAYAWA";
    $abm = "ABOUT ME";
    $pabm = "I am currently a second year student of Bachelor of Science in Information Technology at the University of Mindanao.
            My interest in technology is rooted in a desire to create practical, user-friendly solutions that simplify everyday life. I am drawn to the problem-solving aspect of IT, finding satisfaction in building systems that provide efficiency and ease.
            Beyond the screen, I value balance and quietude. I recharge best in calm, less crowded environments, often with a book in hand. While I am sociable and can engage comfortably in team settings, I find that my best thinking happens in moments of stillness. I bring this same thoughtful, intentional approach to my work in technology."
    ?>



    <div class="split-layout">
        <div class="left-side">
           <div class="cent">
                <?php 
                    echo "<h1> $fname</h1>";
                    echo "<h2> $lname </h2>";
                ?>
           </div>
            
        </div>
         <div class="right-side">
    
         <?php
            echo "<h3> $abm </h3>";
            echo "<p> $pabm </p>"
         ?>
            
    
            
           
        </div>
    </div>
    
</body>
</html>

<?php $conn->close(); ?>