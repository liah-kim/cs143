DROP TABLE IF EXISTS PersonInfo, OrgInfo, PrizeInfo, Received, Affiliation, IsAffiliated;

CREATE TABLE PersonInfo(id INT PRIMARY KEY, family_name VARCHAR(30), given_name VARCHAR(30), gender VARCHAR(8), birth_date DATE, birth_city VARCHAR(20), birth_country VARCHAR(20));
CREATE TABLE OrgInfo(id INT PRIMARY KEY, org_name VARCHAR(100), founded_date DATE, founded_city VARCHAR(20), founded_country VARCHAR(20));
CREATE TABLE PrizeInfo(award_year INT, category VARCHAR(30), sort_order INT);
CREATE TABLE Received(id INT, award_year INT, category VARCHAR(30), sort_order INT, PRIMARY KEY(id, award_year, category, sort_order));
CREATE TABLE Affiliation(id INT PRIMARY KEY, name VARCHAR(100), city VARCHAR(30), country VARCHAR(30));
CREATE TABLE IsAffiliated(person_id INT, aff_id INT, award_year INT, PRIMARY KEY(person_id, aff_id, award_year));

LOAD DATA LOCAL INFILE './PersonInfo.del' INTO TABLE PersonInfo
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
(id, @family_name, given_name, gender, @birth_date, @birth_city, @birth_country)
SET 
family_name = NULLIF(@family_name, 'NULL'),
birth_date = NULLIF(@birth_date, 'NULL'),
birth_city = NULLIF(@birth_city, 'NULL'),
birth_country = NULLIF(@birth_country, 'NULL')
;

LOAD DATA LOCAL INFILE './OrgInfo.del' INTO TABLE OrgInfo
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
(id, org_name, @founded_date, @founded_city, @founded_country)
SET 
founded_date = NULLIF(@founded_date, 'NULL'),
founded_city = NULLIF(@founded_city, 'NULL'),
founded_country = NULLIF(@founded_country, 'NULL')
;

LOAD DATA LOCAL INFILE './PrizeInfo.del' INTO TABLE PrizeInfo
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE './Received.del' INTO TABLE Received
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE './Affiliation.del' INTO TABLE Affiliation
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE './IsAffiliated.del' INTO TABLE IsAffiliated
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n';
