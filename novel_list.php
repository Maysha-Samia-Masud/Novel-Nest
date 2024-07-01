<?php
session_start();
include 'dbconn.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}

// Fetch the search query from the URL
$query = isset($_GET['query']) ? htmlspecialchars(trim($_GET['query'])) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novel Nest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="novel_liststyles.css">
    <link rel="stylesheet" href="navbarStyles.css">
</head>
<body>

<header>
<a href="home.php" class="logo"><img src="assets/NovelNestLogo.png" alt="Novel Next Logo"></a>
    <div class="search-bar">
        <form id="searchForm" action="novel_list.php" method="GET">
            <input type="text" id="searchInput" name="query" placeholder="Search novels..." value="<?php echo $query; ?>">
            <button type="submit">Search</button>
        </form>
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

<fieldset class="field">
    <div class="heading"><h1><a href="#">All Novels</a></h1></div>
    <div class="container">
        <div class="card__container">
            <?php
            // Prepare the SQL query based on the search term
            if ($query) {
                $sql = "SELECT * FROM novel WHERE novel_name LIKE ?";
                $stmt = $con->prepare($sql);
                $searchTerm = '%' . $query . '%';
                $stmt->bind_param('s', $searchTerm);
            } else {
                $sql = "SELECT * FROM novel";
                $stmt = $con->prepare($sql);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any novels
            if ($result->num_rows > 0) {
                // Iterate over each novel and create a card article
                while ($row = $result->fetch_assoc()) {
                    echo '<article class="card__article">';
                    echo '<img src="' . htmlspecialchars($row['novel_img_link']) . '" alt="image" class="card__img">';
                    echo '<div class="card__data">';
                    // Generating card description based on genre flags
                    $description = '';
                    if ($row['Action'] == 1) {
                        $description .= '#Action ';
                    }
                    if ($row['Adventure'] == 1) {
                        $description .= '#Adventure ';
                    }
                    if ($row['Fantasy'] == 1) {
                        $description .= '#Fantasy ';
                    }
                    if ($row['Isekai'] == 1) {
                        $description .= '#Isekai ';
                    }
                    if ($row['Slice_of_Life'] == 1) {
                        $description .= '#Slice_of_Life ';
                    }
                    echo '<span class="card__description">' . htmlspecialchars($description) . '</span>';
                    echo '<h2 class="card__title">' . htmlspecialchars($row['novel_name']) . '</h2>';
                    echo '<a href="noveldetails.php?novel_id=' . htmlspecialchars($row['novel_id']) . '" class="card__button">Read More</a>';
                    echo '</div>';
                    echo '</article>';
                }
            } else {
                echo "<p>No novels found.</p>";
            }

            $stmt->close();
            $con->close();
            ?>
        </div>
    </div>
</fieldset>
<script src="header.js"></script>

</body>
</html>
