SELECT family_name 
FROM PersonInfo P, Received R 
WHERE P.id = R.id 
GROUP BY family_name HAVING COUNT(*) >=5;