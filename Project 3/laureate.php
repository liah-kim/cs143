<?php
// get the id parameter from the request
$id = intval($_GET['id']);

// set the Content-Type header to JSON, 
// so that the client knows that we are returning JSON data
header('Content-Type: application/json');

// connect to db
$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$personQuery = "SELECT * FROM PersonInfo WHERE id=$id";
$orgQuery = "SELECT * FROM OrgInfo WHERE id=$id";
$personResult = $db->query($personQuery);
$orgResult = $db->query($orgQuery);
if (!$personResult || !$orgResult) {
    $errmsg = $db->error; 
    print "Query for laureate ID $id failed: $errmsg <br>"; 
    exit(1); 
}

if ($personResult->num_rows > 0) {
    while ($row = $personResult->fetch_assoc()) { 
        $id = $row['id']; 
        $familyName = $row['family_name'];
        $givenName = $row['given_name'];
        $gender = $row['gender'];
        $birthDate = $row['birth_date'];
        $birthCity = $row['birth_city'];
        $birthCountry = $row['birth_country'];
    }
    $prizeQuery = "SELECT * FROM Received R, PersonInfo P WHERE R.id = $id AND P.id = $id";
    $prizeResult = $db->query($prizeQuery);
    if (!$prizeQuery) {
        $errmsg = $db->error; 
        print "Prize query for laureate ID $id failed: $errmsg <br>"; 
        exit(1); 
    }
    $prizes = array();
    while ($row = $prizeResult->fetch_assoc()) { 
        $id = $row['id']; 
        $awardYear = $row['award_year'];
        $category = $row['category'];
        $sortOrder = $row['sort_order'];
        $affQuery = "SELECT * FROM IsAffiliated I, Affiliation A WHERE I.person_id = $id AND I.aff_id = A.id AND I.award_year = $awardYear";
        $affResult = $db->query($affQuery);
        if (!$affQuery) {
            $errmsg = $db->error; 
            print "Affiliation query for laureate ID $id failed: $errmsg <br>"; 
            exit(1); 
        }
        $affs = array();
        while ($row = $affResult->fetch_assoc()) {
            $name = $row['name'];
            $city = $row['city'];
            $country = $row['country'];
            $affs[] = (object) [
                "name" => (object) [
                    "en" => strval($name)
                ],
                "city" => (object) [
                    "en" => strval($city)
                ],
                "country" => (object) [
                    "en" => strval($country)
                ]
            ];
        }
        $prizes[] = (object) [
            "awardYear" => $awardYear,
            "category" => (object) [
                "en" => strval($category)
            ],
            "sortOrder" => $sortOrder,
            "affiliations" => $affs
        ];
    }
    
    $output = (object) [
        "id" => strval($id),
        "givenName" => (object) [
            "en" => strval($givenName)
        ],
        "familyName" => (object) [
            "en" => strval($familyName)
        ],
        "gender" => strval($gender),
        "birth" => (object) [
            "date" => strval($birthDate),
            "place" => (object) [
                "city" => (object) [
                    "en" => strval($birthCity)
                ],
                "country" => (object) [
                    "en" => strval($birthCountry)
                ]
            ]
        ],
        "nobelPrizes" => $prizes
    ];
    $outputFinal = (object) array_filter((object) $output);
} else if ($orgResult->num_rows > 0) {
    while($row = $orgResult->fetch_assoc()) {
        $id = $row['id'];
        $orgName = $row['org_name'];
        $foundedDate = $row['founded_date'];
        $foundedCity = $row['founded_city'];
        $foundedCountry = $row['founded_country'];
    }
    $prizeQuery = "SELECT * FROM Received R, OrgInfo O WHERE R.id = $id AND O.id = $id";
    $prizeResult = $db->query($prizeQuery);
    if (!$prizeQuery) {
        $errmsg = $db->error; 
        print "Prize query for laureate ID $id failed: $errmsg <br>"; 
        exit(1); 
    }
    $prizes = array();
    while ($row = $prizeResult->fetch_assoc()) { 
        $id = $row['id']; 
        $awardYear = $row['award_year'];
        $category = $row['category'];
        $sortOrder = $row['sort_order'];
        $prizes[] = (object) [
            "awardYear" => $awardYear,
            "category" => (object) [
                "en" => strval($category)
            ],
            "sortOrder" => $sortOrder
        ];
    }
    
    $output = (object) [
        "id" => strval($id),
        "orgName" => (object) [
            "en" => strval($orgName)
        ],
        "founded" => (object) [
            "date" => strval($foundedDate),
            "place" => (object) [
                "city" => (object) [
                    "en" => strval($foundedCity)
                ],
                "country" => (object) [
                    "en" => strval($foundedCountry)
                ]
            ]
        ],
        "nobelPrizes" => $prizes
    ];
}

$json = json_encode($output);
echo preg_replace('/"\w+?"\s*:\s*"",?/',"",$json);

?>