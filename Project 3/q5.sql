WITH S AS (
    SELECT category, award_year 
    FROM Received R 
    WHERE ID IN (SELECT id from OrgInfo) 
    GROUP BY category, award_year HAVING COUNT(category) >= 1
    )
SELECT COUNT(*) num FROM S;