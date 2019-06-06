
import pandas as pd
import numpy as np
import re
# 해시태그를 분석하기 위한 Twitter 모듈
from konlpy.tag import Twitter
# 크롬 브라우저 조작을 위한 모듈
from selenium import webdriver
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By

# 페이지 스크롤링을 위한 모듈
from selenium.webdriver.common.keys import Keys
# 크롤링할 url 주소
url = "https://www.instagram.com/explore/tags/JMT/"
# 다운로드 받은 driver 주소
DRIVER_DIR = "C:/Users/Gangmin/Desktop/gangmin/3-1/DW/teamproject/for git/chromedriver.exe"

# 크롬 드라이버를 이용해 임의로 크롬 브라우저를 실행시켜 조작한다.
driver = webdriver.Chrome(DRIVER_DIR)
# 암묵적으로 웹 자원을 (최대) 5초 기다리기
driver.implicitly_wait(5)
# 크롬 브라우저가 실행되며 해당 url로 이동한다.
driver.get(url)
# body 태그를 태그 이름으로 찾기
elem = driver.find_element_by_tag_name("body")
# alt 속성의 값을 담을 빈 리스트 선언
alt_list = []
#첫번째 게시물 클릭
first_div = driver.find_element_by_xpath("//*[@id=\"react-root\"]/section/main/article/div[2]/div/div[1]/div[1]")


first_div.click()
#서울데이터뽑기, 리스트제거하기, 
#file
f = open("list.txt",'w',encoding = 'utf-8')
#여기부터 반복구간
for j in range(100):
    #피드찾기
    while(True):
            try:
                wait = WebDriverWait(driver, 5)
                wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, ".EZdmt")))
                feed = driver.find_element_by_css_selector("body > div._2dDPU.vCf6V > div.zZYga > div > article > div.eo2As > div.EtaWk > ul > li > div > div > div.C4VMK > span")
                break;
            except :
                print('err')
                arrow = driver.find_element_by_css_selector("body > div._2dDPU.vCf6V > div.EfHg9 > div > div > a.HBoOv.coreSpriteRightPaginationArrow")
                arrow.click()
            
    
    
    text = feed.text
    text = text.replace("\n","")
    emoji = re.compile("[" 
    u"\U0001F600-\U000E007F" # emoticons 
    u"\U0001F300-\U0001F5FF" # symbols & pictographs 
    u"\U0001F680-\U0001F6FF" # transport & map symbols 
    u"\U0001F1E0-\U0001F1FF" # flags (iOS) 
    u"\U00002700-\U000027BF"
         "]+", flags=re.UNICODE)
    text = emoji.sub(r'',text)
    tlist = text.split(" ")
    rlist = []
    for i in tlist:
        if(len(i)>0 and i[0]=='#'):
           tag = ""
           for j in range(len(i)):
               if j == len(i)-1:
                   tag += i[j]
                   rlist.append(tag)
               elif i[j] == '#':
                   if j ==0:
                       tag+=i[j]
                   else:
                       rlist.append(tag)
                       tag = ""
                       tag += i[j]
               else:
                   tag += i[j]
    print(rlist)
    if(len(rlist)!=0):
        for p in rlist:
            f.write(p)
        f.write('\n')
    
    arrow = driver.find_element_by_css_selector("body > div._2dDPU.vCf6V > div.EfHg9 > div > div > a.HBoOv.coreSpriteRightPaginationArrow")
    arrow.click()
f.close()

#개행문자 제거, 태그 모아놓기

# 드라이버를 종료한다.
driver.close()