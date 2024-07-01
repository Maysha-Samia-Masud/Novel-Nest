<?php
session_start();
include 'dbconn.php';

// Get novel_id from the URL parameter
if (isset($_GET['novel_id'])) {
    $novel_id = $_GET['novel_id'];

    // Fetch novel data from the database
    $query = "SELECT * FROM novel WHERE novel_id = $novel_id";
    $result = mysqli_query($con, $query);

    // Check if the novel exists
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $novel_name = $row["novel_name"];
        $rating = $row["Rating"];
        $status = ($row["status"] == 0) ? "Completed" : "Ongoing";
        $author = "xyz";
        $synopsis = $row["intro"];

        // Fetch chapters of the novel
        $chapters_query = "SELECT * FROM novel_chapter WHERE novel_id = $novel_id ORDER BY cpt_no ASC";
        $chapters_result = mysqli_query($con, $chapters_query);
        $chapters = [];
        while ($chapter_row = mysqli_fetch_assoc($chapters_result)) {
            $chapters[] = $chapter_row;
        }
    } else {
        echo "Novel not found.";
    }
} else {
    echo "Invalid request.";
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $novel_name; ?> Details</title>
    <link rel="stylesheet" href="detailstyles.css">
    <link rel="stylesheet" href="navbarStyles.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    />
</head>
<body>
<header>
    <a href="home.php" class="logo"><img src="assets/NovelNestLogo.png" alt="Novel Next Logo"></a>
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

<nav class="navbar">
    <ul>
        <li><a href="novel_list.php">Novel List</a></li>
    </ul>
</nav>

<div class="container">
    <div class="novel-header">
        <img src="<?php echo $row['novel_img_link']; ?>" alt="Cover Image">
        <div class="novel-info">
            <h1><?php echo $novel_name; ?></h1>
            <div class="rating">
                <span class="rating-count">Rating :<?php echo $rating; ?></span>
            </div>
            <p class="status">Status: <span><?php echo $status; ?></span></p>
            <p class="author">Author: <span><?php echo $author; ?></span></p>
        </div>
    </div>
    <div class="novel-description">
        <h2>Synopsis</h2>
        <p><?php echo $synopsis; ?></p>
    </div>
</div>
<div class="chapter-list">
    <h2>Chapters</h2>
    <ul>
        <?php foreach ($chapters as $chapter): ?>
             <li><a href="chapterPage.php?novel_id=<?php echo $novel_id; ?>&chapter_no=<?php echo $chapter['cpt_no']; ?>&chapter_title=<?php echo urlencode($chapter['chapter_title']); ?>&chapter_text=<?php echo urlencode($chapter['cpt_text']); ?>"><?php echo $chapter["chapter_title"]; ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<script src="header.js"></script>
</body>
</html>
