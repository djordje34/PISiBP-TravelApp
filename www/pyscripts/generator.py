import datetime
import os
import random
import string
import time

import matplotlib.pyplot as plt
import mysql.connector
import numpy as np
import pandas as pd
import requests
from bs4 import BeautifulSoup
from faker import Faker
from mysql.connector import Error  # OBRISI PRAZNA POLJA
from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
from utils import (BED_NUM, TYPE, companyGPT, dodajRandomAktivnost,
                   dodajRandomGrad, hotelGPT, plotTools, roundStars,
                   translateElement)

"""
    
NOTES TO SELF:
TESTIRATI GENERATORE

    """
global link
link = 'https://www.worldometers.info/geography/7-continents/'          

PATH_DATA = "pydata/py_csv/"

def getDriver():#na kraju prevesti celokupni df
    
    browser = webdriver.Chrome()
    return browser

def getContinents():
    
    kontinenti = pd.DataFrame(columns = ['naziv'])
    
    
    browser = getDriver()
    global link
    browser.get(link)
    time.sleep(2)
    content = browser.page_source
    soup = BeautifulSoup(content,features="lxml")
    names = []
    for a in soup.findAll('table', attrs={'class':'table'}):
        for name in a.findAll('a'):
            names.append(name.text) #names sadrzi engl kontinente
        for name in names:
        #continents.append(cyrillic_to_latin(tss.google(name, fr, to)))
            kontinenti.loc[len(kontinenti.index)] = name

    return kontinenti
    
def getContinentsAndCountries():    
    global link
    kontinenti = getContinents()
    browser = getDriver()
    
    drzave = pd.DataFrame(columns = ['naziv','kontinent'])

    for name in kontinenti['naziv'].values:
        temp = name.replace(" ","-")
        newlink = link + '/'+name.lower().replace(' ','-')+'/'

        browser.get(newlink)
        time.sleep(2)
        content = browser.page_source
        soup = BeautifulSoup(content,features="lxml")
        for a in soup.findAll('table', attrs={'class':'table'}):
            howmuch = 15 if name == 'Europe' else 3                                               #koliko drzava po kontinentu?
            
            for country in a.findAll('td'):
                if not howmuch:
                    break
                
                if all(x.isalpha() or x.isspace() for x in country.text):
                    
                    drzave.loc[len(drzave.index)] = [country.text,kontinenti.loc[kontinenti['naziv']==name,'naziv'].values[0]]
                    howmuch-=1
    return kontinenti,drzave
    
    
def getAllGeography():
    
    kontinenti,drzave = getContinentsAndCountries()
    
    newlink = 'https://worldpopulationreview.com/countries/cities/'
    
    browser = getDriver()    
    gradovi = pd.DataFrame(columns = ['naziv','drzava','kontinent'])
    for name in drzave['naziv'].values:
        clink = newlink + name.lower().replace(' ','-')

        browser.get(clink)
        time.sleep(3)
        content = browser.page_source
        
        soup = BeautifulSoup(content,features="lxml")
        try:
            browser.find_element(By.CLASS_NAME('_3p_1XEZR')).submit()

        except:
            pass
        for a in soup.findAll('table', attrs={'class':'table'}):
            howmuch = 5 if drzave.loc[drzave["naziv"]==name,"kontinent"].values[0] == 'Europe' else 5              
            for city in a.findAll('td'):

                if not howmuch:
                    break
                
                if all(x.isalpha() or x.isspace() for x in city.text):
                    gradovi.loc[len(gradovi.index)] = [city.text,drzave.loc[drzave['naziv']==name,'naziv'].values[0],drzave.loc[drzave['naziv']==name,'kontinent'].values[0]]
                    howmuch-=1
                    
    gradovi.to_csv(PATH_DATA+'cities.csv',index=None)
    
    
    
    
    
    gradovi['naziv']=gradovi['naziv'].apply(translateElement)
    gradovi['drzava']=gradovi['drzava'].apply(translateElement)
    gradovi['kontinent']=gradovi['kontinent'].apply(translateElement)
    gradovi.to_csv(PATH_DATA+'gradovi.csv',index=None)
    return gradovi,drzave,kontinenti
    
    
    
    
def getAllDFs():
    gradovi,drzave,kontinenti = getAllGeography()
    hoteli = pd.DataFrame(columns=["naziv","grad"])
    for index, val in gradovi['naziv'].items():

        imena = hotelGPT(val)
        for ime in imena:
            hoteli.loc[len(hoteli.index)] = [ime,val]
            
    
    hoteli.to_csv(PATH_DATA+'hoteli.csv',index=None)

    return hoteli,gradovi,drzave,kontinenti



def generateRooms():   #testirati sumu broja soba
    hoteli = pd.read_csv(PATH_DATA+'hoteli.csv')
    np.random.seed(5)
    mu = 75
    sigma = 25
    hoteli['br_soba'] = np.random.normal(mu, sigma, hoteli.shape[0])*hoteli['zvezdice']
    hoteli['br_soba']=hoteli['br_soba'].apply(round)
    hoteli.to_csv(PATH_DATA+'hoteli.csv',index = None)
    

def hotelStarsDistribution():   #koristiti kasnije openAI da generise opis hotela
    
    np.random.seed(5)
    mu = 3.5
    sigma = 0.5
    
    try:    
        hoteli = pd.read_csv(PATH_DATA+'hoteli.csv')
        
    except:
        hoteli,_,_,_ = getAllDFs()
        
    hoteli['zvezdice'] = np.random.normal(mu, sigma, hoteli.shape[0])
    hoteli['zvezdice'] = hoteli['zvezdice'].apply(roundStars)
    hoteli.to_csv(PATH_DATA+'hoteli.csv',index = None)
    
def hotelGenerateAddress():
    try:
        hoteli = pd.read_csv(PATH_DATA+'hoteli.csv')
    except:
        hoteli,_,_,_ = getAllDFs()
    fake = Faker()
    cutSpaces = np.vectorize(lambda x: x.replace('  ',' ')) #[''.join(y) for y in x[0:len(x.split(' '))-1]]
    cutAptNums = np.vectorize(lambda x: (' '.join([''.join(y) for y in x.split(' ')[0:len(x.split(' '))-1]]) if x.split(' ')[len(x.split(' '))-1].isnumeric() else x))
    addresses = cutAptNums(cutSpaces(np.array([' '.join([y if not ('Suite' in y or 'Apt.' in y) else '' for y in fake.address().split('\n')[0].split(' ') ]) for i in range(len(hoteli.index))],dtype=str))) 
        #this is why we love python
    hoteli['adresa'] = addresses
    hoteli.to_csv(PATH_DATA+'hoteli.csv',index=None)

def PlotGeneratedInfos():
    hoteli = pd.read_csv(PATH_DATA+'hoteli.csv')
    gradovi = pd.read_csv(PATH_DATA+'gradovi.csv')
    plotTools(what=hoteli,case=1,title="Raspodela zvezdica hotela",x="Broj zvezdica",y="Kolicina",path = "hotel_stars_normal.png")
    plotTools(what=gradovi,case=2,title="Raspodela kolicine hotela po zemljama",x="Drzava",y="Broj hotela",path = "country_to_no_hotels.png")
    plotTools(what=gradovi,case=3,title="Raspodela kolicine hotela po kontinentima",x="Kontinent",y="Broj hotela",path = "continent_to_no_hotels.png")
    #plotTools(what=hoteli['zvezdice'],case=1,title="Raspodela zvezdica hotela",x="Broj zvezdica",y="Kolicina",path = "hotel_stars_normal.png")
    plotTools(what=hoteli,case=4,title="Raspodela soba po hotelima",x="Hoteli",y="Broj soba",path = "rooms_to_hotels.png")

def sobaParamGen(base=10):
    sobe = pd.DataFrame(columns=['tip','br_kreveta','opis','gen_cena'])


    for i in list(TYPE.keys()): #da vrati samo tip,broj kreveta, pocetnu cenu
        for j in BED_NUM:
            kreveti_multiplier = 1 if j<2 else 0.67        

            sobe.loc[len(sobe.index)] = [i,j,TYPE[i][0],round(TYPE[i][1]*j*kreveti_multiplier * base,2)]       
            
    sobe.to_csv(PATH_DATA+'sobe.csv',index = None)

def generatePrevoznik():
    prevoz = pd.DataFrame(columns=['tip','tip_komp'])
    prevoz['tip'] = ['airplane', 'bus', 'cruise', 'train']
    prevoz['tip_komp'] = companyGPT()
    prevoz['cena'] = [random.choice(range(1000,3000)),random.choice(range(100,250)),random.choice(range(250,950)),random.choice(range(100,150))]
            
    prevoz.loc[len(prevoz.index)] = ['lično vozilo', 'samostalni prevoz',0]
    prevoz.to_csv(PATH_DATA+'prevoz.csv',index=None)

    
    
    
    
def generatePonude():
    """
    aranzman(aran_id,naziv,krece,vraca,nap,smestaj_id,p_id)
    
    naziv->
    
    """
    gradovi = pd.read_csv(PATH_DATA+'gradovi.csv')
    hoteli = pd.read_csv(PATH_DATA+'hoteli.csv')
    prevoz = pd.read_csv(PATH_DATA+'prevoz.csv')
    aranzman = pd.DataFrame(columns = ["naziv","krece","vraca","smestaj","p_id"])
    hoteli['tmp'] = 1
    prevoz['tmp'] = 1
    prevoz['p_id'] = prevoz.index+1
    prevoz['prevod'] = ["avionom","autobusom","krstarenje/brodom","vozom","samostalni prevoz"]
    prevoz['tmp']=1

    temp=pd.DataFrame()
    temp['naziv'] = hoteli['naziv']
    temp['smestaj_id'] = temp.index+1
    aranzman = pd.merge(hoteli, prevoz, on=['tmp'])
    
    def startDateGenerator(margin,min_date,max_date): #uzmi random dan za start iz dana iz dates array gde vazi da zadnji dan meseca-start nije vece od margin(trajanje putovanja)
        maxed_date   = datetime.datetime.strptime(max_date, '%Y-%m-%d') - datetime.timedelta(days=int(margin))
        #minimized_date = datetime.strptime(min_date, '%Y-%m-%d') + pd.DateOffset(days=margin)
        
        dates = pd.date_range(min_date,maxed_date,freq='d').to_list()
        
        start_date = random.choice(dates)
        #end_date = datetime.strptime(start_date, '%Y-%m-%d') + pd.DateOffset(days=margin)
        
        return start_date + datetime.timedelta(hours=random.choice(range(1,20)))
    """
    def endDateGenerator(margin,min_date,max_date):
        
        minimized_date = datetime.strptime(min_date, '%Y-%m-%d') + pd.DateOffset(days=margin)
        
        dates = pd.date_range(minimized_date,max_date,freq='d').to_list()
        
        end_date = random.choice(dates)
        #end_date = datetime.strptime(start_date, '%Y-%m-%d') + pd.DateOffset(days=margin)
        
        return end_date
    """
    
    def endDateGenerator(margin,start_date):
        return start_date + datetime.timedelta(days=int(margin)) + datetime.timedelta(hours=random.choice(range(0,3)))
    #x = datetime.datetime(2018, 6, 1)

    #print(x.strftime("%B")
    #df['NewCol'] = df.apply(lambda x: segmentMatch(x['TimeCol'], x['ResponseCol']), axis=1)
    timeline = pd.DataFrame()
    timeline['broj_dana'],timeline['tmp'] = ['3','5','7','10','14'],1
    meseci = pd.DataFrame()
    meseci['mesecMin'],meseci['mesecMax'],meseci['tmp'] = ['2023-1-1','2023-6-1','2023-7-1','2023-8-1','2023-9-1','2023-10-1'],['2023-1-31','2023-6-30','2023-7-31','2023-8-31','2023-9-30','2023-10-31'],1
    meseci['mesec'],meseci['mpr'] = ['1','6','7','8','9','10'],["Januar","Jun","Jul","Avgust","Septembar","Oktobar"]
    aranzman = pd.merge(aranzman, timeline, on=['tmp'])
    aranzman = pd.merge(aranzman, meseci, on=['tmp'])
    aranzman=aranzman.merge(temp,on=['naziv'],how='left')
    aranzman = aranzman.drop('tmp', axis=1)
    aranzman['datum_pocetka'] = aranzman.apply(lambda x: startDateGenerator(x['broj_dana'],x['mesecMin'],x['mesecMax']),axis=1)
    aranzman['datum_zavrsetka'] = aranzman.apply(lambda x: endDateGenerator(x['broj_dana'],x['datum_pocetka']),axis=1)
    aranzman = aranzman.drop(columns=['mesecMax','mesecMin'], axis=1)

    aranzman['godina'] = aranzman['datum_pocetka'].dt.strftime('%Y')
    aranzman['m_str'] = aranzman['datum_pocetka'].dt.strftime('%')
    #hoteli.loc[hoteli['naziv']==aranzman['naziv'],"grad"].values() + 
    aranzman['ime'] = aranzman['grad'] + " " + aranzman['mpr'] + " "+ aranzman['godina'] + " " + aranzman['naziv'] + " " + aranzman['prevod']+ " " + aranzman['broj_dana'] + " dana"
    aranzman.to_csv(PATH_DATA+"aranzmani.csv",index=None)
    
def generateAktivnosti():
    df = pd.DataFrame(columns = ["naziv"])
    
    for x in ["Setnja po gradu","Obilazak nacionalnog parka","Poseta muzeju","Nocenje","Fakultativni izleti","Slobodno vreme- obilazak lokalnog soping centra",
                "Obilazak obliznjih lokaliteta","Organizovani nocni provod"]:
        df.loc[len(df.index)]=x;
        
    df.to_csv(PATH_DATA+"aktivnosti.csv",index=None);
    
    
def smestajImaAktivnost():#g_id	akt_id	smestaj_id	

        
    gradovi = pd.read_csv(PATH_DATA+"gradovi.csv").index+1
    akt = pd.DataFrame(columns = ["g_id","akt_id","smestaj_id"])
    akt['smestaj_id'] = pd.read_csv(PATH_DATA+"hoteli.csv").index+1
    akt=akt.fillna('0')
    akt['g_id'] = akt['g_id'].apply(dodajRandomGrad)
    akt['akt_id'] = akt['akt_id'].apply(dodajRandomAktivnost)
    akt.to_csv(PATH_DATA+"aktivnosti_u_gradu.csv",index=None)
    #akt['g_id'] = 
    
def generateImaAktivnost():
    decompose = lambda x: [1 for i in range(int(x))]
    
    aran = pd.read_csv(PATH_DATA+"aranzmani.csv")
    aran['broj_dana'] = aran['broj_dana'].apply(lambda x: x-1)

    akt = pd.read_csv(PATH_DATA+"aktivnosti.csv")
    ids = akt.index+1
    ima = pd.DataFrame(columns=['aran_id','akt_id'])
    aran=aran.drop(columns=['naziv','grad','br_soba','adresa','tip','tip_komp','p_id','prevod','mesec','mpr','datum_pocetka','datum_zavrsetka','godina','m_str','ime','zvezdice'])
    aran['aran_id'] = aran.index+1
    aran['akt_id']=aran['broj_dana'].apply(decompose)
    ima = aran.explode('akt_id')
    ima = ima.drop(columns=['broj_dana','smestaj_id'])
    ima['akt_id'] = np.random.choice(ids, ima.shape[0])
    ima.to_csv(PATH_DATA+"ima_aktivnost.csv",index=None)
    

def generateRandomRezervacije(n):
    #ime prezime	br_kartice	email	broj_odr	broj_dece	cena	kom	kontakt	aran_id	korisnik_id	
    faker = Faker()
    rez = pd.DataFrame(columns=['ime','prezime','br_kartice','email','broj_odr','broj_dece','cena','kom','kontakt','aran_id'])
    
    rez['ime'] = [faker.name().split(" ")[0] for _ in range(n)]
    rez['prezime'] = [faker.name().split(" ")[1] for _ in range(n)]
    rez['br_kartice'] = [''.join(random.choices(string.ascii_lowercase, k=8)) for _ in range(n)]
    rez['email'] = [faker.name().split(" ")[1]+"@gmail.com" for _ in range(n)]
    rez['broj_odr'] = [random.randint(0,3) for _ in range(n)]
    rez['broj_dece'] = [random.randint(0,3) for _ in range(n)]
    rez['cena'] = [random.randint(100,500) for _ in range(n)]
    rez['kom'] = [random.randint(1,5) for _ in range(n)]
    rez['kontakt'] = [''.join(random.choices(string.ascii_lowercase, k=10)) for _ in range(n)]
    rez['aran_id'] = [random.randint(1,50000) for _ in range(n)]
    
    rez.to_csv(PATH_DATA+"rand_rez.csv",index=None)

def generator():
    getAllDFs()
    hotelStarsDistribution()
    generateRooms()
    hotelGenerateAddress()
    sobaParamGen()
    PlotGeneratedInfos()
    generatePrevoznik()
    generatePonude()
    generateAktivnosti()        
    smestajImaAktivnost()
    generateImaAktivnost()
    generateRandomRezervacije(150)


start = time.time()
generator()
end = time.time()
print(end - start)