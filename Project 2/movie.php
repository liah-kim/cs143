<?php
// connect to db
$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']'); 
}

$movieId = $_GET['id'];

// style
echo "<style>
        table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
        }
    </style>";

// get movie info from movie id
$movieQuery = "SELECT * FROM Movie WHERE id=$movieId";
$movieResult = $db->query($movieQuery);
if (!$movieResult) {
    $errmsg = $db->error; 
    print "Query for movie ID $movieId failed: $errmsg <br>"; 
    exit(1); 
}

echo "<table>
            <tr>
                <th>Movie ID</th>
                <th>Title</th>
                <th>Year</th>
                <th>Rating</th>
                <th>Company</th>
            </tr>";
while ($row = $movieResult->fetch_assoc()) { 
    $mid = $row['id']; 
    $title = $row['title'];
    echo "<h2>Information for $title </h2>";
    echo "<tr><td>$mid</td><td>$title</td><td>" . $row['year'] . "</td><td>" . $row['rating'] . "</td><td>" . $row['company'] . "</td></tr>";
}
echo "</table><br>";
$movieResult->free();

// get actors in movie from movie id
$actorsQuery = "SELECT aid, role, first, last FROM MovieActor JOIN Actor ON aid = id WHERE mid=$mid";
$actorsResult = $db->query($actorsQuery);
if (!$actorsResult) {
    $errmsg = $db->error; 
    print "Query for actors of movie ID $movieId failed: $errmsg <br>"; 
    exit(1); 
}

if ($actorsResult->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Actor ID</th>
                <th>Name</th>
                <th>Role</th>
            </tr>";

    while($row = $actorsResult->fetch_assoc()) {
        $aid = $row['aid'];
        echo "<tr><td>$aid</td><td><a href='actor.php?id=$aid'>" . $row['first'] . " " . $row['last'] . "</a></td><td>" . $row['role'] . "</td></tr>";
    }
    echo "</table><br>";
}
$actorsResult->free();

// get user comments from movie id
echo "<h3>User Comments:</h3>";
$userCommentsQuery = "SELECT * FROM Review WHERE mid=$movieId";
$userCommentsResult = $db->query($userCommentsQuery);
if (!$userCommentsResult->num_rows) {
    print "No user reviews. <a href='review.php?id=$movieId'>Click here to leave a review!</a>";
} else {
    while ($row = $userCommentsResult->fetch_assoc()) { 
        $reviewerName = $row['name'];
        $time = $row['time'];
        $rating = $row['rating'];
        $comment = $row['comment'];
        print "<p>$reviewerName rated this movie a $rating at $time:<br> $comment</p>";
    }
    print "<a href='review.php?id=$movieId'>Click here to leave a review!</a>";
}
$userCommentsResult->free();

// close connection
$db->close();
?>