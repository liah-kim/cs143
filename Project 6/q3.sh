zcat /home/cs143/data/googlebooks-eng-all-1gram-20120701-s.gz | datamash groupby 1 sum 3 | awk '$2 > 1000000' 