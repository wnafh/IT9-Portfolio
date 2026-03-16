<?php
session_start();

if (isset($_SESSION['username'])){
    header("Location: dashboard.php");
    exit();
}

$uname = "admin";
$pword = "admin123";

if (isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $uname && $password === $pword) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    }else{
        $error = "Invalid username or password";
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
            
            <div class="nav-right">
                
                
                <div class="social-links">
                    <a href="https://www.instagram.com/_ial.wena/" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://github.com/wnafh" target="_blank">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="mailto:w.magayawa.554012@umindanao.edu.ph">
                        <i class="far fa-envelope"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="login-container">
        <h2>Admin Login</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" name="login" class="btn btn-primary">Login</button>
            
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>