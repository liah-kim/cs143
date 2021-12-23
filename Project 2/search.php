<?php
error_reporting(-1);
ini_set("display_errors", "1");
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
print "<html>";     
print "<h2>";     
$title = "Searching Page";     
print "<title>$title</title>";     
print "</h2>";     
print "<body bgcolor=white>";     
print "<h1>$title</h1>";

print "<html>";
print "<body>";
print "<FORM METHOD='POST' ACTION='search.php'>";
print "Actor Name: <INPUT TYPE='text' NAME='actor' VALUE='' SIZE='10' MAXLENGTH='20'>   ";
print "<INPUT TYPE='submit' VALUE='Search Actor'><br><br>";
print "Movie Name: <INPUT TYPE='text' NAME='movie' VALUE='' SIZE='10' MAXLENGTH='20'>   ";
print "<INPUT TYPE='submit' VALUE='Search Movie'>";
print "</FORM>";
print "</body>";
print "</html>";

$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$searchActor = false;
if (isset($_GET['actor'])) {
    $searchActor = $_GET['actor'];
} else if (isset($_POST['actor'])) {
    $searchActor = $_POST['actor'];
}

$searchMovie = false;
if (isset($_GET['movie'])) {
    $searchMovie = $_GET['movie'];
} else if (isset($_POST['movie'])) {
    $searchMovie = $_POST['movie'];
}

if ($searchActor) {
    $searchActor = strtolower($searchActor);
    $keywords = explode(' ', $searchActor);
    $query;
    for($i = 0; $i < count($keywords); $i++) {
        if ($i == 0) {
            $query = "SELECT first, last, id FROM Actor WHERE LOWER(first) LIKE '%$keywords[$i]%' OR 
            LOWER(last) LIKE '%$keywords[$i]%'";
        } else {
            $query = $query ." INTERSECT SELECT first, last, id FROM Actor WHERE LOWER(first) LIKE '%$keywords[$i]%' OR 
            LOWER(last) LIKE '%$keywords[$i]%'";
        }
    }
    $statement = $db->prepare($query);
    $statement->execute();
    $statement->bind_result($returned_first, $returned_last, $returned_aid);

    while($statement->fetch()) {
        echo 'Name: <a href="actor.php?id='. $returned_aid .'"> '. $returned_first . ' ' . $returned_last .' </a><br>';
    }

    $statement->close();
} else if ($searchMovie) {
    $searchMovie = strtolower($searchMovie);
    $keywords = explode(' ', $searchMovie);
    $query;
    for($i = 0; $i < count($keywords); $i++) {
        if ($i == 0) {
            $query = "SELECT title, id FROM Movie WHERE LOWER(title) LIKE '%$keywords[$i]%'";
        } else {
            $query = $query ." INTERSECT SELECT title, id FROM Movie WHERE LOWER(title) LIKE '%$keywords[$i]%'";
        }
    }
    $statement = $db->prepare($query);
    $statement->execute();
    $statement->bind_result($returned_title, $returned_mid);

    while($statement->fetch()) {
        echo 'Movie: <a href="movie.php?id='. $returned_mid .'"> '. $returned_title .' </a><br>';
    }

    $statement->close();
}

$db->close();
?>