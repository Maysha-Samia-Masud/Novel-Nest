<?php
session_start();
include 'dbconn.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle delete request
if (isset($_GET['novel_id'])) {
    $novel_id = $_GET['novel_id'];

    // Delete chapters first
    $delete_chapters_query = "DELETE FROM novel_chapter WHERE novel_id = $novel_id";
    mysqli_query($con, $delete_chapters_query);

    // Delete novel
    $delete_novel_query = "DELETE FROM novel WHERE novel_id = $novel_id";
    mysqli_query($con, $delete_novel_query);

    header('Location: delete_novel.php');
    exit();
}

// Fetch all novels
$query = "SELECT * FROM novel";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Novels</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="adminallnovelsstyles.css">
    <link rel="stylesheet" href="navbarStyles.css">
</head>
<body>

<header>
    <a href="home.php" class="logo"><img src="assets/NovelNestLogo.png" alt="Novel Nest Logo"></a>
    <div class="profile-dropdown">
        <div onclick="toggle()" class="profile-dropdown-btn">
            <div class="profile-img">
                <i class="fa-solid fa-circle"></i>
            </div>
            <span>
                <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?>
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

<main>
    <div class="container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="novel-item">';
                echo '<div class="novel-info">';
                echo '<img src="' . $row['novel_img_link'] . '" alt="Novel Image">';
                echo '<div class="novel-details">';
                echo '<h2>' . $row['novel_name'] . '</h2>';
                echo '<p>' . $row['intro'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '<div class="delete-button">';
                echo '<a href="delete_novel.php?novel_id=' . $row['novel_id'] . '">DELETE</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "No novels found.";
        }
        ?>
    </div>
</main>

<script src="header.js"></script>

</body>
</html>
