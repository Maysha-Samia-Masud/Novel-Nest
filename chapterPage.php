<?php
session_start();
include 'dbconn.php';

// Get parameters from URL
if (isset($_GET['novel_id'], $_GET['chapter_no'], $_GET['chapter_title'], $_GET['chapter_text'])) {
    $novel_id = $_GET['novel_id'];
    $chapter_no = $_GET['chapter_no'];
    $chapter_title = urldecode($_GET['chapter_title']);
    $chapter_text = urldecode($_GET['chapter_text']);

    // Increment read count for the novel
    $update_query = "UPDATE novel SET Read_Count = Read_Count + 1 WHERE novel_id = $novel_id";
    mysqli_query($con, $update_query);
} else {
    echo "Invalid request.";
    exit();
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novel Next</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="chapterPageStyle.css">
</head>
<body>

<header>
    <a href="home.php" class="logo"><img src="assets/NovelNestLogo.png" alt="Novel Next Logo" style="width: 120px; height: 88px;"></a>
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
                <a href="#">
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

<article id="chapter-article">
    <section class="page-in content-wrap">
        <div class="titles">
            <h1 itemprop="headline">
                <a class="booktitle">
                    <?php 
                    if (isset($_GET['novel_name'])) {
                        echo htmlspecialchars($_GET['novel_name']);
                    } else {
                        echo "Novel Name";
                    }
                    ?>
                </a>
                <br><br><span class="chapter-title">
                    <?php 
                    if (isset($_GET['chapter_no']) && isset($_GET['chapter_title'])) {
                        echo "Chapter " . htmlspecialchars($_GET['chapter_no']) . ": " . htmlspecialchars($_GET['chapter_title']);
                    } else {
                        echo "Chapter Title";
                    }
                    ?>
                </span>
            </h1>
        </div>
        <div id="chapter-container" class="chapter-content font_default" itemprop="description" style="font-size: 16px;">
            <?php 
            if (isset($_GET['chapter_text'])) {
                echo htmlspecialchars($_GET['chapter_text']);
            } else {
                echo "Chapter content goes here.";
            }
            ?>
        </div>
    </section>
</article>

<script src="chapterPageScript.js"></script>
<script src="mousetrap.js"></script>

</body>
</html>
