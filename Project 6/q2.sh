zcat /home/cs143/data/googlebooks-eng-all-1gram-20120701-s.gz | sort -k2,2 | awk '$4 > 10000' | cut -f 2 | head -1