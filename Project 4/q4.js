db.laureates.aggregate([
    { $unwind: "$nobelPrizes"},
    { $unwind: "$nobelPrizes.affiliations"},
    { $group: { 
        _id : "$nobelPrizes.affiliations.name.en",
        location : { "$addToSet" : "$nobelPrizes.affiliations.city.en"}
    }},
    { $match : {_id : "University of California"}},
    { $project : {_id : 0, name : "$_id", location : "$location"}},
    { $project : {_id : 0, locations : {$size : "$location"}}}
]).pretty()