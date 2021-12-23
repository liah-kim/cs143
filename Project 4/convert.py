import json

# load data
data = json.load(open("/home/cs143/data/nobel-laureates.json", "r"))

# create output file laureates.import
output = open("laureates.import", "w")

# process every json object in data["laureates"] and write
# to laureates.import

for laureate in data["laureates"]:
    json.dump(laureate, output)
    output.write("\n")
