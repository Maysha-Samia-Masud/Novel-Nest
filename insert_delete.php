<?php
session_start();
include 'dbconn.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}

// Check if the form for inserting a novel is submitted
if (isset($_POST['insert_novel'])) {
    // Retrieve data from the form
    $novelName = $_POST['novel_name'];
    $status = $_POST['status'];
    $intro = $_POST['intro'];
    $novelImgLink = $_POST['novelImgLink'];
    $action = isset($_POST['Action']) ? $_POST['Action'] : 0;
    $adventure = isset($_POST['Adventure']) ? $_POST['Adventure'] : 0;
    $fantasy = isset($_POST['Fantasy']) ? $_POST['Fantasy'] : 0;
    $isekai = isset($_POST['Isekai']) ? $_POST['Isekai'] : 0;
    $sliceOfLife = isset($_POST['Slice_of_Life']) ? $_POST['Slice_of_Life'] : 0;

    // Insert data into the novel table
    $query = "INSERT INTO novel (novel_name, status, intro, novel_img_link, Action, Adventure, Fantasy, Isekai, Slice_of_Life) 
    VALUES ('$novelName', '$status', '$intro', '$novelImgLink', $action, $adventure, $fantasy, $isekai, $sliceOfLife)";

    $insert_novel_result = mysqli_query($con, $query);

    if ($insert_novel_result) {
        // Redirect to the same page to refresh after successful insertion
        header('Location: ' . $_SERVER['PHP_SELF'] . '?insert_success=true');
        exit();
    } else {
        echo "Error inserting novel: " . mysqli_error($con);
    }
}

if (isset($_POST['insertChapter'])) {
    // Retrieve data from the form
    $novelID = $_POST['novelID'];
    $chapterNo = $_POST['cpt_no'];
    $chapterTitle = $_POST['chapter_title'];
    $chapterText = $_POST['cpt_text'];

    // Insert data into the novel chapter table
    $insertChapterQuery = "INSERT INTO novel_chapter (novel_id, cpt_no, chapter_title, cpt_text) 
                           VALUES ('$novelID', '$chapterNo', '$chapterTitle', '$chapterText')";
    $insertChapterResult = mysqli_query($con, $insertChapterQuery);

    if ($insertChapterResult) {
        // Redirect to the same page to refresh after successful insertion
        header('Location: ' . $_SERVER['PHP_SELF'] . '?insert_success=true');
        exit();
    } else {
        echo "Error inserting novel chapter: " . mysqli_error($con);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novel Nest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="insdel.css">
    <link rel="stylesheet" href="navbarStyles.css">
</head>
<body>

<header>
    <a href="home.php" class="logo"><img src="assets/NovelNestLogo.png" alt="Novel Nest Logo"></a>
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
                <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?>
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

<div class="content">
    <!-- Novel Form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h2>Insert Novel Data</h2>
        <label for="novel_name">Novel Name:</label>
        <input type="text" id="novel_name" name="novel_name" required>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required>

        <label for="intro">Introduction:</label>
        <textarea id="intro" name="intro" required></textarea>

        <label for="novelImgLink">Novel Image Link:</label>
        <input type="text" id="novelImgLink" name="novelImgLink" required>

        <div class="checkbox-row">
            <label for="Action">Action:</label>
            <input type="checkbox" id="Action" name="Action" value="1">
        </div>
        <div class="checkbox-row">
            <label for="Adventure">Adventure:</label>
            <input type="checkbox" id="Adventure" name="Adventure" value="1">
        </div>
        <div class="checkbox-row">
            <label for="Fantasy">Fantasy:</label>
            <input type="checkbox" id="Fantasy" name="Fantasy" value="1">
        </div>
        <div class="checkbox-row">
            <label for="Isekai">Isekai:</label>
            <input type="checkbox" id="Isekai" name="Isekai" value="1">
        </div>
        <div class="checkbox-row">
            <label for="Slice_of_Life">Slice of Life:</label>
            <input type="checkbox" id="Slice_of_Life" name="Slice_of_Life" value="1">
        </div>

        <button type="submit" name="insert_novel">Insert Novel</button>
    </form>

    <!-- Novel Chapter Form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h2>Insert Novel Chapter Data</h2>
        <label for="novelID">Novel ID:</label>
        <input type="text" id="novelID" name="novelID" required>

        <label for="cpt_no">Chapter Number:</label>
        <input type="number" id="cpt_no" name="cpt_no" required>

        <label for="chapter_title">Chapter Title:</label>
        <input type="text" id="chapter_title" name="chapter_title" required>

        <label for="cpt_text">Chapter Text:</label>
        <textarea id="cpt_text" name="cpt_text" required></textarea>

        <button type="submit" name="insertChapter">Insert Chapter</button>
    </form>
</div>

<script>
    // Check if the URL contains a query parameter indicating a successful submission
    const urlParams = new URLSearchParams(window.location.search);
    const insertSuccess = urlParams.get('insert_success');
    const insertError = urlParams.get('insert_error');

    // If the URL contains the query parameter, display an alert accordingly
    if (insertSuccess) {
        alert("Insertion successful.");
        window.location.href = window.location.pathname; // Reload the page without query parameters
    }

    if (insertError) {
        alert("Error occurred during insertion.");
        window.location.href = window.location.pathname; // Reload the page without query parameters
    }
</script>
<script src="header.js"></script>
</body>
</html>
