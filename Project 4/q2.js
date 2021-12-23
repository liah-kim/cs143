db.laureates.aggregate([
    { $unwind : "$nobelPrizes"},
    { $unwind: "$nobelPrizes.affiliations"},
    { $match : {"nobelPrizes.affiliations.name.en" : "CERN" } },
    { $project : {_id : 0, "nobelPrizes.affiliations.country.en" : 1 } },
    { $replaceRoot : { newRoot : { country : "$nobelPrizes.affiliations.country.en"}}},
    { $limit : 1}
]).pretty()
