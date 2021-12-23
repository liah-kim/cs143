WITH S as (SELECT DISTINCT name, city, country
FROM Affiliation
WHERE name LIKE 'University of California%'
GROUP BY city, country)
SELECT COUNT(*) num FROM S;