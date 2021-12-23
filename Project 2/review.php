<?php
error_reporting(-1);
ini_set("display_errors", "1");
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
// connect to db
$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']'); 
}

if (isset($_GET['id'])) {
    $mid = $_GET['id'];
} else {
    $mid = $_POST['mid'];
}

// get movie from movie id
$movieQuery = "SELECT title FROM Movie WHERE id=$mid";
$movieResult = $db->query($movieQuery);
if (!$movieResult) {
    $errmsg = $db->error; 
    print "Query for movie ID $mid failed: $errmsg <br>"; 
    exit(1); 
}

while ($row = $movieResult->fetch_assoc()) { 
    $title = $row['title'];
    echo "<h2>Add a new review for $title </h2>";
}

// drop down for rating
echo"<FORM METHOD='POST'>
<p>Your Name: <INPUT TYPE='text' NAME='name'></p>
<p>Rating:
<select NAME='rating'>
  <option value=1>1</option>
  <option value=2>2</option>
  <option value=3>3</option>
  <option value=4>4</option>
  <option value=5>5</option>
</select></p>
<p>Comment: <INPUT TYPE='text' NAME='comment'></p>
<p><INPUT TYPE='Submit'></p>
</form>";

if(isset($_POST['comment'])) {
    $userName = $_POST['name'];
    $userRating = $_POST['rating'];
    $userComment = $_POST['comment'];

    // insert user review into db
    $reviewQuery = "INSERT INTO Review VALUES ('$userName', CURRENT_TIMESTAMP, '$mid', '$userRating', '$userComment')";
    $reviewResult = $db->query($reviewQuery);

    if($reviewResult == true) {
        print "Record created sucessfully! <a href='movie.php?id=$mid'>Click here to go back to the movie page!</a>";
    }
}

$movieResult->free();

// close connection
$db->close();
?>