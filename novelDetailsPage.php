<?php
session_start();
include 'dbconn.php';

// Assuming you have a variable $novel_id that holds the ID of the selected novel
$novel_id = $_GET['novel_id'];

$sql = "SELECT * FROM novels WHERE novel_id = $novel_id";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $novel_name = $row["novel_name"];
        $rating = $row["rating"];
        $status = $row["status"];
        $author = $row["author"];
        $synopsis = $row["synopsis"];

        $tags_sql = "SELECT tag_name FROM tags WHERE tag_id IN (SELECT tag_id FROM novel_tags WHERE novel_id = $novel_id)";
        $tags_result = $conn->query($tags_sql);
        $tags = array();
        while ($tag_row = $tags_result->fetch_assoc()) {
            $tags[] = $tag_row["tag_name"];
        }

        $chapters_sql = "SELECT * FROM chapters WHERE novel_id = $novel_id ORDER BY chapter_number ASC";
        $chapters_result = $con->query($chapters_sql);
        $chapters = array();
        while ($chapter_row = $chapters_result->fetch_assoc()) {
            $chapters[] = $chapter_row;
        }
    }
} else {
    echo "0 results";
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novel Details</title>
    <link rel="stylesheet" href="testStyles.css">
</head>

<body>
    <div class="container">
        <div class="novel-header">
            <img src="cover.jpg" alt="Cover Image">
            <div class="novel-info">
                <h1><?php echo $novel_name; ?></h1>
                <div class="rating">
                    <span class="rating-count"><?php echo $rating; ?></span>
                </div>
                <p class="status">Status: <span><?php echo $status; ?></span></p>
                <p class="author">Author: <span><?php echo $author; ?></span></p>
            </div>
        </div>
        <div class="novel-description">
            <h2>Synopsis</h2>
            <p><?php echo $synopsis; ?></p>
        </div>
        <div class="tags">
            <h3>Tags</h3>
            <ul>
                <?php foreach ($tags as $tag): ?>
                    <li><?php echo $tag; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="chapter-list">
        <div class="chapter-header">
            <div class="first-chapter">First Chapter: <?php echo $chapters[0]["chapter_title"]; ?></div>
            <div class="new-chapter">New Chapter: <?php echo $chapters[count($chapters) - 1]["chapter_title"]; ?></div>
        </div>
        <input type="text" class="search-chapters" placeholder="Search Chapters: Example 25 or TR">
        <ul class="chapters">
            <?php foreach ($chapters as $chapter): ?>
                <li><a href="#"><?php echo $chapter["chapter_title"]; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script src="searchNovels.js"></script>
</body>

</html>
