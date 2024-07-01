<?php
session_start();
include 'dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the current user's information from the database
$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT * FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Debug: Print fetched user data
//echo "<pre>"; print_r($user); echo "</pre>";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Debug: Print old password and hashed password
    //echo "Old password (entered): $old_password<br>";
    //echo "Stored hashed password: {$user['password']}<br>";

    // Check if the old password is correct
    if ($old_password === $user['password']) {
        // Update the user's information in the database
        $stmt = $con->prepare("UPDATE user SET user_name = ?, user_email = ?, password = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $username, $email, $new_password, $user_id);
        $stmt->execute();

        // Update the session variable with the new username
        $_SESSION['user_name'] = $username;

        // Redirect to the home page or display a success message
        header('Location: home.php');
        exit();
    } else {
        $error_message = "Incorrect old password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    />
    <link rel="stylesheet" href="editProfileStyles.css">
    <link rel="stylesheet" href="navbarStyles.css">
    
</head>
<body>
<header>
<a href="home.php" class="logo"><img src="assets/NovelNestLogo.png" alt="Novel Next Logo"></a>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search novels...">
        <button onclick="searchNovels()">Search</button>
    </div>
    <div class="profile-dropdown">
        <div onclick="toggle()" class="profile-dropdown-btn">
          <div class="profile-img">
            <i class="fa-solid fa-circle"></i>
          </div>

          <span>
          <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?> <!-- Display the username here -->
            <i class="fa-solid fa-angle-down"></i>
          </span>
        </div>

        <ul class="profile-dropdown-list">
          <li class="profile-dropdown-list-item">
            <a href="editProfile.php">
              <i class="fa-regular fa-user"></i>
              Edit Profile
            </a>
          </li>

          <li class="profile-dropdown-list-item">
            <a href="#">
              <i class="fa-solid fa-book"></i>
              Bookmarks
            </a>
          </li>

          <li class="profile-dropdown-list-item">
            <a href="login.php">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
              Log out
            </a>
          </li>
        </ul>
      </div>
</header>

<nav class="navbar">
    <ul>
        <li><a href="novel_list.php">Novel List</a></li>
        <li><a href="advanced_filter.php">Advanced Filter</a></li>
    </ul>
</nav>
    <div class="container">
        <h1>Edit Profile</h1>
        <?php if (isset($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo $user['user_name']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $user['user_email']; ?>" required>

            <label for="old_password">Old Password:</label>
            <input type="password" name="old_password" id="old_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password">

            <input type="submit" value="Update Profile">
        </form>
    </div>
    <script src="scriptHome.js"></script><br>
</body>
</html>