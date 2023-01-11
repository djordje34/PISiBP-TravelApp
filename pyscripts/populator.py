import os
import time

import mysql.connector
import requests
from bs4 import BeautifulSoup
from mysql.connector import Error
from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select


def getDriver():
    browser = webdriver.Chrome()
    
    link = 'https://www.worldometers.info/geography/7-continents/'
    browser.get(link)
    time.sleep(2)
    content = browser.page_source
    soup = BeautifulSoup(content,features="lxml")
    names = []
    for a in soup.findAll('table', attrs={'class':'table'}):
        for name in a.findAll('a'):
            names.append(name.text)
        
    print(names)

    
    
    
    
def generator():
    pass
    
    
def connect():
    try:
        connection = mysql.connector.connect(host='localhost',
                                            database='mydb',
                                            user='root',
                                            password='')
        if connection.is_connected():
            db_Info = connection.get_server_info()
            print("Connected to MySQL Server version ", db_Info)
            cursor = connection.cursor()
            cursor.execute("select database();")
            record = cursor.fetchone()
            print("You're connected to database: ", record)

    except Error as e:
        print("Error while connecting to MySQL", e)
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("MySQL connection is closed")
            
getDriver()

