from itertools import combinations

# set up SparkContext for bookPairs application
from pyspark import SparkContext
sc = SparkContext("local", "bookPairs")

# helper function to split data to only contain
# books reviewed for each user
def helper1(line):
    # split the data to extract only the books
    books = line.split(":")
    # disregard user id
    books = books[1]
    # split string to make each book an index
    books = books.split(",")
    return books

# helper function to return the combinations of 
# book pairs for each user
def helper2(books):
    return list(combinations(list(books), 2))

# part b : computing high-frequency book pairs
lines = sc.textFile("/home/cs143/data/goodreads.user.books")

# generate all possible pairs
books = lines.map(helper1)
pairs = books.flatMap(helper2)

# filter based on if a pair is in a person's review list
existingPairs = pairs.map(lambda x: (x, 1))

# use reduceByKey to count up the number of pairs
pairTotals = existingPairs.reduceByKey(lambda a, b: a + b)

# filter to only generate output for book pairs that appear
# in more than 20 users' lists
largest = pairTotals.filter(lambda x: x[1] > 20)

# save output
largest.saveAsTextFile("output")
