<?php
include 'dbconn.php'; // Include your database connection file
session_start();

function check_login($con) {
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $query = "SELECT * FROM user WHERE user_id = '$id' LIMIT 1";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    // Redirect to login
    header("Location: login.php");
    die;
}

function random_num($length) {
    $text = "";
    if ($length < 5) {
        $length = 5;
    }
    $len = rand(4, $length);
    for ($i = 0; $i < $len; $i++) {
        $text .= rand(0, 9);
    }
    return $text;
}

function validate_signup($name, $email, $password) {
    $nameREGEX = "/^[A-Za-z0-9_]+$/";
    $emailREGEX = "/^[a-z0-9_.+-]+@[a-z0-9-]+\.[a-z0-9-]+\.com$/i";

    $passREGEX = "/^\S+$/";

    if (empty($name) || empty($email) || empty($password)) {
        return "All fields are required!";
    }
    if (!preg_match($nameREGEX, $name)) {
        return "Name can only contain letters, numbers, and underscores.";
    }
    if (!preg_match($emailREGEX, $email)) {
        return "Email must be in a valid format and end with '.com'. ex: name@domain.com";
    }
    if (!preg_match($passREGEX, $password)) {
        return "Password cannot contain spaces.";
    }
    return "";
}

function validate_login($name, $password) {
    $nameREGEX = "/^[A-Za-z0-9_]+$/";
    $passREGEX = "/^\S+$/";

    if (empty($name) || empty($password)) {
        return "All fields are required!";
    }
    if (!preg_match($nameREGEX, $name)) {
        return "Wrong username or password!";
    }
    if (!preg_match($passREGEX, $password)) {
        return "Wrong username or password!";
    }
    return "";
}

// Sign Up Logic
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['signup'])) {
    $user_name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = validate_signup($user_name, $email, $password);
    if ($error === "") {
        $user_id = random_num(20);
        $query = "INSERT INTO user (user_id, user_name, user_email, password) VALUES ('$user_id', '$user_name', '$email', '$password')";
        mysqli_query($con, $query);
        header("Location: login.php");
        die();
    } else {
        
    }
}

// Login Logic
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])) {
    $user_name = $_POST['username'];
    $password = $_POST['password'];

    $error = validate_login($user_name, $password);
    if($user_name === 'admin' && $password === 'adminpass') {
        // Additional check for the email in the database
        $query = "select * from user where user_name= '$user_name' AND user_email='admin@gmail.com';";
        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0){
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['user_name'] = $user_data['user_name']; // Set the username session variable
            header("Location: ./adminHome.php");
            exit();
        }
    }
    else if ($error === "") {
        $query = "SELECT * FROM user WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if ($user_data['password'] === $password) {
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['user_name'] = $user_data['user_name'];
                header("Location: home.php");
                die();
            }
        }
        $error="Wrong username or password!";
    } else {
        echo $error;
    }
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="stylesLogin.css">
    <title>Login Page | Sign-up</title>
</head>
<body>
    <div class="img"><img src="assets/logo.png" width="150" height="80"></div>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form id="signupForm" action="login.php" method="POST">
                <h1>Create Account</h1>
                <input type="text" id="signupName" name="name" placeholder="Name">
                <input type="email" id="signupEmail" name="email" placeholder="Email">
                <input type="password" id="signupPassword" name="password" placeholder="Password">
                <button type="submit" name="signup" value="signup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form id="loginForm" action="login.php" method="POST">
                <h1>Sign In</h1>
                <input type="text" id="loginName" name="username" placeholder="Username">
                <input type="password" id="loginPassword" name="password" placeholder="Password">
                <button type="submit" name="login" value="login">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Don't have account?</h1>
                    <p>Register with your personal details to read novels</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
        
    </div>

    <?php if (isset($error)) { ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php } ?>
    <script src="scriptLogin.js"></script>
</body>
</html>
