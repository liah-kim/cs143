import json

# load data
data = json.load(open("/home/cs143/data/nobel-laureates.json", "r"))

personOutput = open("PersonInfo.del", "w")
orgOutput = open("OrgInfo.del", "w")
prizeOutput = open("PrizeInfo.del", "w")
affOutput = open("Affiliation.del", "w")
receivedOutput = open("Received.del", "w")
isAffOutput = open("IsAffiliated.del", "w")
affDict = {}

for laureate in data["laureates"]:
    id = laureate["id"]
    if not (laureate.get("gender") is None):
        try:
            familyName = laureate["familyName"]["en"]
        except:
            familyName = "NULL"
        givenName = laureate["givenName"]["en"]
        gender = laureate["gender"]
        try:
            birthDate = laureate["birth"]["date"]
        except:
            birthDate = "NULL"
        try:
            birthCity = laureate["birth"]["place"]["city"]["en"]
        except:
            birthCity = "NULL"
        try:
            birthCountry = laureate["birth"]["place"]["country"]["en"]
        except:
            birthCountry = "NULL"
        personLine = id + "\t" + familyName + "\t" + givenName + "\t" + gender + "\t" + birthDate + "\t" + birthCity + "\t" + birthCountry 
        personOutput.write(personLine)
        personOutput.write("\n")
        for prize in laureate["nobelPrizes"]:
            awardYear = prize["awardYear"]
            try:
                for affiliation in prize["affiliations"]:
                    affName = affiliation["name"]["en"]
                    affCity = affiliation["city"]["en"]
                    affCountry = affiliation["country"]["en"]
                    affLine =  affName + "\t" + affCity + "\t" + affCountry
                    if affLine not in affDict:
                        affID = len(affDict) + 1
                        affDict[affLine] = affID
                        outputAffLine = str(affID) + "\t" + affLine
                        affOutput.write(outputAffLine)
                        affOutput.write("\n")
                    isAffLine = id + "\t" + str(affDict[affLine]) + "\t" + awardYear
                    isAffOutput.write(isAffLine)
                    isAffOutput.write("\n")
            except:
                continue
    else:
        orgName = laureate["orgName"]["en"]
        try:
            foundedDate = laureate["founded"]["date"]
        except:
            foundedDate = "NULL"
        try:
            foundedCity = laureate["founded"]["place"]["city"]["en"]
        except:
            foundedCity = "NULL"
        try:
            foundedCountry = laureate["founded"]["place"]["country"]["en"]
        except:
            foundedCountry = "NULL"
        orgLine = id + "\t" + orgName + "\t" + foundedDate + "\t" + foundedCity + "\t" + foundedCountry
        orgOutput.write(orgLine)
        orgOutput.write("\n")
        
    for prize in laureate["nobelPrizes"]:
        awardYear = prize["awardYear"]
        category = prize["category"]["en"]
        sortOrder = prize["sortOrder"]
        prizeLine = awardYear + "\t" + category + "\t" + sortOrder
        prizeOutput.write(prizeLine)
        prizeOutput.write("\n")
        receivedLine = id + "\t" + prizeLine 
        receivedOutput.write(receivedLine)
        receivedOutput.write("\n")
