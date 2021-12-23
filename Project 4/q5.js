db.laureates.aggregate([
    { $unwind: "$orgName.en"},
    { $project : {_id : 0, years : "$nobelPrizes.awardYear"}},
    { $unwind : "$years"},
    {
        $group: {
           _id:1, 
           years: {$addToSet: "$years"}
        }
    },
    {
        $project : {
            _id : 0,
            years: {$size : "$years" }
        }
    }
]).pretty()