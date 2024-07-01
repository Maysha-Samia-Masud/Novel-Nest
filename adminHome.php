<?php
session_start();
include 'dbconn.php';
// Check if the user is not logged in

if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novel Nest</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    />
    <link rel="stylesheet" href="stylesHome.css">
    <link rel="stylesheet" href="navbarStyles.css">
    <script>
        // Define your functions here to ensure they are loaded before the body content
        function searchNovels() {
            var searchInput = document.getElementById('searchInput').value;
            // Implement search functionality here
        }

        function toggle() {
            let profileDropdownList = document.querySelector(".profile-dropdown-list");
            let btn = document.querySelector(".profile-dropdown-btn");
            let classList = profileDropdownList.classList;
            classList.toggle("active");
        }

        window.addEventListener("DOMContentLoaded", () => {
            // Ensuring all elements are loaded before attaching event listeners
            window.addEventListener("click", function (e) {
                let btn = document.querySelector(".profile-dropdown-btn");
                let classList = document.querySelector(".profile-dropdown-list").classList;
                if (!btn.contains(e.target)) classList.remove("active");
            });
        });
    </script>
</head>
<body>

<header>
<a href="#" class="logo"><img src="assets/NovelNestLogo.png" alt="Novel Next Logo"></a>
<div class="search-bar">
    <form id="searchForm" action="novel_list.php" method="GET">
        <input type="text" id="searchInput" name="query" placeholder="Search novels...">
        <button type="submit" onclick="searchNovels()">Search</button>
    </form>
</div>
    <div class="profile-dropdown">
        <div onclick="toggle()" class="profile-dropdown-btn">
          <div class="profile-img">
            <i class="fa-solid fa-circle"></i>
          </div>

          <span>
          <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin'; ?> <!-- Display the username here -->
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
            <a href="insert_delete.php">
              <i class="fa-solid fa-book-journal-whills"></i>
              Add Novels
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
        <li><a href="adminallnovels.php">Novel List</a></li>
    </ul>
</nav>

<div class="slider">
    <!-- list Items -->
    <div class="list">
        <?php
        // Fetch top 5 novels based on Read_Count from the database
        $query = "SELECT * FROM novel ORDER BY Read_Count DESC LIMIT 5";
        $result = mysqli_query($con, $query);
        

        // Check if there are any novels
        if (mysqli_num_rows($result) > 0) {
            $isActive = true; // To set the first item as active
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="item' . ($isActive ? ' active' : '') . '">';
                echo '<img src="' . $row['novel_img_link'] . '">';
                echo '<div class="content">';
                echo '<p>trending</p>';
                echo '<h2>' . $row['novel_name'] . '</h2>';
                echo '<p>' . $row['intro'] . '</p>';
                echo '<a href="noveldetails.php?novel_id=' . $row['novel_id'] . '" class="card__button" style="font-size: 25px;color:#F0FFFF">Read More</a>';
                echo '</div>';
                echo '</div>';
                $isActive = false;
            }
        } else {
            echo "No trending novels found.";
        }
        ?>
    </div>

    <!-- button arrows -->
    <div class="arrows">
    <button id="prev"><</button>
    <button id="next">></button>
</div>

    <!-- thumbnail -->
    <div class="thumbnail">
        <?php
        // Fetch top 5 novels based on Read_Count from the database (re-fetch for thumbnails)
        $query = "SELECT * FROM novel ORDER BY Read_Count DESC LIMIT 5";
        $result = mysqli_query($con, $query);

        // Check if there are any novels
        if (mysqli_num_rows($result) > 0) {
            $isActive = true; // To set the first item as active
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="item' . ($isActive ? ' active' : '') . '">';
                echo '<img src="' . $row['novel_img_link'] . '">';
                echo '<div class="content">';
                echo '</div>';
                echo '</div>';
                $isActive = false;
            }
        } else {
            echo "No trending novels found.";
        }
        ?>
    </div>
</div>

<script src="scriptHome.js"></script><br>


<fieldset class="field">
    <div class="heading"><h1><a href="#">Action</a></h1></div>
    <div class="container">
        <div class="card__container">
            <?php
            // Fetch novels with Action genre from the database
            $query = "SELECT * FROM novel WHERE Action = 1";
            $result = mysqli_query($con, $query);

            // Check if there are any novels with Action genre
            if (mysqli_num_rows($result) > 0) {
                // Iterate over each novel and create a card article
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<article class="card__article">';
                    echo '<img src="' . $row['novel_img_link'] . '" alt="image" class="card__img">';
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
                    echo '<span class="card__description">' . trim($description) . '</span>';
                    echo '<h2 class="card__title">' . $row['novel_name'] . '</h2>';
                    echo '<a href="noveldetails.php?novel_id=' . $row['novel_id'] . '" class="card__button">Read More</a>';
                    echo '</div>';
                    echo '</article>';
                }
            } else {
                echo "No action novels found.";
            }
            ?>
        </div>
    </div>
</fieldset>


<fieldset class="field">
    <div class="heading"><h1><a href="#">Fantasy</a></h1></div>
    <div class="container">
        <div class="card__container">
            <?php
            // Fetch novels with Action genre from the database
            $query = "SELECT * FROM novel WHERE Fantasy = 1";
            $result = mysqli_query($con, $query);

            // Check if there are any novels with Action genre
            if (mysqli_num_rows($result) > 0) {
                // Iterate over each novel and create a card article
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<article class="card__article">';
                    echo '<img src="' . $row['novel_img_link'] . '" alt="image" class="card__img">';
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
                    echo '<span class="card__description">' . trim($description) . '</span>';
                    echo '<h2 class="card__title">' . $row['novel_name'] . '</h2>';
                    echo '<a href="noveldetails.php?novel_id=' . $row['novel_id'] . '" class="card__button">Read More</a>';
                    echo '</div>';
                    echo '</article>';
                }
            } else {
                echo "No action novels found.";
            }
            ?>
        </div>
    </div>
</fieldset>

    <fieldset class="field">
    <div class="heading"><h1><a href="#">Adventure</a></h1></div>
    <div class="container">
        <div class="card__container">
            <?php
            // Fetch novels with Action genre from the database
            $query = "SELECT * FROM novel WHERE Adventure = 1";
            $result = mysqli_query($con, $query);

            // Check if there are any novels with Action genre
            if (mysqli_num_rows($result) > 0) {
                // Iterate over each novel and create a card article
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<article class="card__article">';
                    echo '<img src="' . $row['novel_img_link'] . '" alt="image" class="card__img">';
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
                    echo '<span class="card__description">' . trim($description) . '</span>';
                    echo '<h2 class="card__title">' . $row['novel_name'] . '</h2>';
                    echo '<a href="noveldetails.php?novel_id=' . $row['novel_id'] . '" class="card__button">Read More</a>';
                    echo '</div>';
                    echo '</article>';
                }
            } else {
                echo "No action novels found.";
            }
            ?>
        </div>
    </div>
</fieldset>
		
 <fieldset class="field">
    <div class="heading"><h1><a href="#">All Novels</a></h1></div>
    <div class="container">
        <div class="card__container">
            <?php
            // Fetch novel data from the database
            $query = "SELECT * FROM novel";
            $result = mysqli_query($con, $query);

            // Check if there are any novels
            if (mysqli_num_rows($result) > 0) {
                // Iterate over each novel and create a card article
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<article class="card__article">';
                    echo '<img src="' . $row['novel_img_link'] . '" alt="image" class="card__img">';
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
                    echo '<span class="card__description">' . $description . '</span>';
                    echo '<h2 class="card__title">' . $row['novel_name'] . '</h2>';
                  echo '<a href="noveldetails.php?novel_id=' . $row['novel_id'] . '" class="card__button">Read More</a>';
                    echo '</div>';
                    echo '</article>';
                }
            } else {
                echo "No novels found.";
            }
            ?>
        </div>
    </div>
</fieldset>
</body>
</html>
