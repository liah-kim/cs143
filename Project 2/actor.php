<?php
$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']');
}

// style
echo "<style>
        table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
        }
    </style>";

$actorId = $_GET['id'];
$query = "SELECT * FROM Actor WHERE id=$actorId";
$rs = $db->query($query);
if (!$rs) {
    $errmsg = $db->error; 
    print "Query failed: $errmsg <br>"; 
    exit(1); 
}

echo "<table>
            <tr>
                <th>Actor ID</th>
                <th>Name</th>
                <th>Sex</th>
                <th>Date of Birth</th>
                <th>Date of Death</th>
            </tr>";
while ($row = $rs->fetch_assoc()) { 
    $id = $row['id']; 
    $first = $row['first']; 
    $last = $row['last'];
    $dod = $row['dod'];
    if (!$dod) {
        $dod = "Still alive";
    }
    echo "<h2>Information for $first $last</h2>";
    echo "<tr><td>$id</td><td>$first $last</td><td>" . $row['sex'] . "</td><td>" . $row['dob'] . "</td><td>$dod</td></tr>";
}
echo "</table><br>";

$movieQuery = "SELECT id, title FROM Movie WHERE id IN (SELECT mid FROM MovieActor WHERE aid = $actorId)";
$movies = $db->query($movieQuery);
if (!$movies) {
    $errmsg = $db->error; 
    print "Movie ID query failed: $errmsg <br>"; 
    exit(1); 
}

echo "<table>
            <tr>
                <th>Movie ID</th>
                <th>Name</th>
            </tr>";

while ($row = $movies->fetch_assoc()) { 
    $mid = $row['id'];
    $title = $row['title'];
    echo "<tr><td>$mid</td><td><a href='movie.php?id=$mid'>$title</a></td></tr>";
}
echo "</table><br>";

$rs->free();
$movies->free();
$db->close();
?>