<!-- php part -->
<?php 
    // go to dashboard if session is alrady set
    session_start();
    if(isset($_SESSION['login'])) {
        header('location: dashboard.php');
        exit();
    }
    // connexion to database
    require 'connexion.php';
    // data verification 
    if(isset($_POST['login'])) {
        $username = $_POST['user'];
        $password = $_POST['password'];
        $errors = array();
        if (empty($username)) {
            $errors['user'] = "Username required" ;
        }
        if (empty($password)) {
            $errors['pass'] = "Password required";
        }
        if (!empty($username)&&!empty($password)) {
            $query = $db->prepare("select * from user where user_name=?");
            $query->execute(array($username));
            $count = $query->rowCount();
            if($count == 0) {
                $errors['user1'] = "Invalid username";
            }
            else{
                $row = $query->fetch();
                if($password == $row['password']){
                    $_SESSION['login'] = true;
                    $_SESSION['welcome'] = "Welcome admin";
                    header('location:dashboard.php');
                    exit();
                }
                else{
                    $errors['pass1'] = "Invalid password";
                } 
            }  
        }
    }
?>

<!-- html part -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Login</title>
</head>
<body>
    <!-- welcome part -->
    <div class="left-container">
        <h1>Welcome</h1>
        <p>This application is developed for private purposes<br> Please log in with your personal information</p>
    </div>
    <!-- login part -->
    <div class="right-container">
        <form action="" method="post">
            <img src="../images/alomrane-groupe-logo-6C1BA5DE7C-seeklogo.com.png">
            <div class="form">
                <div class="content">
                    <i class='bx bxs-user' style="position:fixed; top:291px;"></i>
                    <input type="text" name="user" placeholder="Username" style="margin-bottom:35px">
                </div>
                <!-- alert messages -->
                <div class="danger" style="margin-top:-65px;">
                    <p><?php if(isset($errors['user'])) { echo $errors['user']; }?></p>
                    <p><?php if(isset($errors['user1'])) { echo $errors['user1']; }?></p>
                </div>
                <div class="space"></div>
                <div class="content">
                    <i class='bx bxs-lock-alt' style="position:absolute; bottom:15px;"></i>
                    <input type="password" name="password" placeholder="Password">
                </div>
                <!-- alert messages -->
                <div class="danger" style="margin-top:-30px; margin-bottom:20px;">
                        <p><?php if(isset($errors['pass'])) { echo $errors['pass']; }?></p>
                        <p><?php if(isset($errors['pass1'])) { echo $errors['pass1']; }?></p>
                </div>
                <div class="forgot">
                    <a href="forgot.php">Forgot Password ?</a>
                </div>
                <input type="submit" name="login" class="btn" value="Login">
            </div>
        </form>
    </div>
</body>
</html>


