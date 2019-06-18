import random
import codecs

ff = codecs.open("taglist.txt",'w',encoding='utf8')
for i in range(20):
    f = codecs.open("C:/wamp64/www/insta_detector/data/list"+str(i+19)+".txt", 'r', encoding='utf8')
    while True:
        line = f.readline()
        ff.write(line)
        if not line: break
    f.close()