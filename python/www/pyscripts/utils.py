import os
import random
import re
import sys
import time
from math import ceil

import matplotlib.pyplot as plt
import numpy as np
import openai
import pandas as pd
import translators as ts
import translators.server as tss
from bing_image_downloader import downloader
from googletrans import Translator
from srtools import cyrillic_to_latin

PATH_DATA = "pydata/py_csv/"
    # Svaki hotel approx 250 soba->standardna distribucija generate random da bude mean 250 i ukupno ~60000 oglasa, znaci->broj soba ~250 na 240hotela
global fr
global to
fr,to = 'en_US','sr-Cyrl_RS'

BED_NUM = [1,2,3,4]
TYPE = {
    'SUITE':["Soba u hotelu (dve prostorije koje ne moraju biti odvojene vratima) odgovarajuće kvadrature za kapacitet od četiri osobe sa minimum dva pomoćna ležaja ili sofom na otvaranje.Sadrži stabilnu internet konekciju, frižider, TV i AC.",1.75],
    'FAMILY':["Porodična soba (jedna prostorija) odgovarajuće kvadrature za četiri osobe sa minimum jednim pravim pravim i jednim pomoćnim ležajem ili sofom na otvaranje, za decu.Sadrži stabilnu internet konekciju, frižider, TV i AC.",1.5],
    "SUPERIOR ROOM":["Soba u hotelu veće kvadrature i kvalitetnije opreme od standardne. Sadrži stabilnu internet konekciju, frižider, TV, klimu i sobni sef.",2],
    "SOBA (STANDARDNA)":["Smeštajna jedinica u hotelu ili vili koja nema kuhinjske elemente, ni terasu. Sadrži stabilnu internet konekciju i TV.",1],
    "STUDIO":["“Garsonjera” - smeštajna jedinica u hotelu, bez predsoblja, u kojoj se u istom prostoru nalazi deo sa kuhinjskim elementima sa osnovnim priborom za jelo.Sadrži stabilnu internet konekciju, TV i frižider.",1.25]
}

def dodajRandomGrad(y):
    x = pd.read_csv(PATH_DATA+"gradovi.csv")
    return random.choice(range(1,len(x.index)+1))


def dodajRandomAktivnost(y):
    x = pd.read_csv(PATH_DATA+"aktivnosti.csv")
    return random.choice(range(1,len(x.index)+1))
#TYPE SADRZI OPIS I 
# MULTIPLIERE ZA CENE SOBA
def roundStars(x,base=0.5):
    if x>=5:
        return 5
    if x<=3:
        return 3
    return base * round(x/base)

def translateElement(el):
    """Prevodi crawlovane podatke sa engleskog na srpski, latinica

    Args:
        el (str): mesto/rec na engleskom

    Returns:
        str: mesto/rec na srpskom, spakovano kao latinica
    """
    global fr,to

    return cyrillic_to_latin(tss.lingvanex(el, fr, to)['text'])


def dataframeCleaner(df,path):
    """Usluzna funkcija koja cisti dataframe-ove tako sto izbacuje prazne elemente

    Args:
        df (DataFrame): DataFrame koji je potrebno ocistiti
        path (str): Putanja

    Returns:
        DataFrame: Ociscen DataFrame
    """
    df = df.loc[df['naziv']!='']

    df.to_csv('pydata/'+path,index = None)
    
    return df


def setGPT():
    """Poziva GPT3 

    """

    
    with open('pydata/api.txt') as f:
        openai.api_key = f.readlines()[0]
    
    
def hotelGPT(place):
    """Poziva GPT3 kako bi generisao listu imena hotela za dati grad

    Args:
        place (str): Neki grad

    Returns:
        list: Listu stringova generisanih naziva hotela
    """
    
    setGPT()
    rgx = '[^a-zA-ZčćžšđČĆŽŠĐ ]+'
    prompt = "Generiši 3 nasumična imena za hotele u mestu "+place+" koji ne sadrže ime mesta u nazivu."
    response = openai.Completion.create(
    engine="text-davinci-003",
    prompt=prompt,temperature=0.9,
    max_tokens=150,
    top_p=1,
    frequency_penalty=0.0,
    presence_penalty=0.6,)
    h_list = response.choices[0]["text"].split("\n")
    
    #REGEX TO THE RESCUE
    h_list = [re.sub(rgx, '', x).replace(' ','',1) for x in h_list if x!='']
    h_list = [x for x in h_list if x!='' and len(x)!=1]
    time.sleep(2)   #greedy OpenAI dozvoljava samo 60 entry-ja po minutu tako da procesor mora da odmori malo!!!
    return h_list

#setGPT("Šangaj")
def companyGPT():
    setGPT()
    prompt = "Generiši 4 nasumična jednostavna imena kompanija za prevoz turista do turističke destinacije."
    with open('pydata/api.txt') as f:
        openai.api_key = f.readlines()[0]
    
    
    response = openai.Completion.create(
    engine="text-davinci-003",
    prompt=prompt,temperature=0.9,
    max_tokens=150,
    top_p=1,
    frequency_penalty=0.0,
    presence_penalty=0.8,)
    rgx = '[^a-zA-Z ]+'
    h_list = response.choices[0]["text"].split("\n")
    h_list = [re.sub(rgx, '', x).replace(' ','',1) for x in h_list if x!='']
    h_list = [x for x in h_list if x!='' and len(x)!=1]
    return h_list

def plotTools(case,what,
              title='',x='',y='',path=''):
    """Plotuje raspodelu NORMALNO rasporedjenih elemenata u pd.series-u

    Args:
        what (pd.series): Sta se plotuje
        sigma (float): Standardna devijacija
        mu (int/float): Mean/srednja vrednost
    """
    if case==1:
        #mu = 3.5
        #sigma = 0.5
        
        #count, bins, ignored = plt.hist(what, 30, density=True)
        #plt.plot(bins, 1/(sigma * np.sqrt(2 * np.pi)) *np.exp( - (bins - mu)**2 / (2 * sigma**2) ),
            #linewidth=2, color='r')
        what['zvezdice'].value_counts().sort_index().plot(kind="bar")
        mean = round(what['zvezdice'].mean(),2)
        median = what['zvezdice'].median()
        plt.axvline(mean-what['zvezdice'].min()+0.5, color='r', linestyle='--',label='Srednja vrednost')
        plt.axvline(median-what['zvezdice'].min()+0.5, color='g', linestyle='--',label='Medijana')
        plt.legend(loc='upper right')

    elif case == 2:
        what['drzava'].value_counts().plot.bar()

        #"Raspodela zvezdica hotela"
        #"Broj zvezdica"
        #"Kolicina"
    elif case ==3 :
        what['kontinent'].value_counts().plot.bar()
    elif case ==4 :
        what['br_soba'].plot(kind = 'bar',xticks=[])
        mean = round(what['br_soba'].mean())
        median = what['br_soba'].median()
        plt.axhline(mean, color='r', linestyle='--',label='Srednja vrednost')
        plt.axhline(median, color='g', linestyle='--',label='Medijana')
        plt.legend(loc='upper right')
        
        
    plt.title(title)
    plt.xlabel(x)
    plt.ylabel(y)
    plt.savefig('pydata/pyplots/'+path)
    plt.cla()
    plt.clf()
    
    
def paramValuesGenerator(s):
    return  ','.join(['%s' for x in range(len(s[s.find("(")+1:s.find(")")].split(",")))])
    
def trimPonude():
    #aranzman(aran_id,naziv,krece,vraca,nap,smestaj_id,p_id)
    aranzman = pd.read_csv(PATH_DATA+"aranzmani.csv")
    filteredCopy = pd.DataFrame()
    filteredCopy['naziv'] = aranzman['ime']
    filteredCopy['krece'] = aranzman['datum_pocetka']
    filteredCopy['vraca'] = aranzman['datum_zavrsetka']
    filteredCopy['smestaj_id'] = aranzman['smestaj_id']
    filteredCopy['p_id'] = aranzman['p_id']
    
    return filteredCopy

    

def dataTrimming():
#zbog baze mora da se menja redosled kolona....

    
    def switchToKey(df,col,x):  #col ako nije  ukazuje da se radi o dataframe else o series
        if col:
            ret = np.where(df[col]==x)
            return ret[0][0]+1

        return np.where(df==x)[0][0]+1
    
    
    
    kontinenti = pd.read_csv(PATH_DATA+'gradovi.csv').kontinent.unique()   #DONE
    drzave = pd.read_csv(PATH_DATA+'gradovi.csv').drop_duplicates(subset = 'drzava').drop(columns=['naziv'] ,axis=1)
    drzave['kontinent']=drzave['kontinent'].apply(lambda x :switchToKey(kontinenti,None,x))#DONE
    drzave.reset_index(drop=True,inplace=True)
    
    gradovi = pd.read_csv(PATH_DATA+'gradovi.csv').drop(columns=['kontinent'],axis=1)
    gradovi['drzava'] = gradovi['drzava'].apply(lambda x :switchToKey(drzave,'drzava',x))            #DONE
    
    hoteli = pd.read_csv(PATH_DATA+'hoteli.csv')
    hoteli['grad'] = hoteli['grad'].apply(lambda x:switchToKey(gradovi,'naziv',x))
    
    sobe = pd.read_csv(PATH_DATA+"sobe.csv")
    hoteli = hoteli.iloc[:,[0,4,3,2,1]]
    sobe = sobe.iloc[:,[0,1,3,2]]
    
    prevoz = pd.read_csv(PATH_DATA+'prevoz.csv')
    
    
    np.set_printoptions(threshold=sys.maxsize)
    #komb = np.empty([ceil(hoteli['br_soba'].sum()),2])
    komb = np.array([0,0])
    for i in range(1,len(hoteli.index)+1):
        k=1
        while k<=hoteli.loc[hoteli.index==i-1,"br_soba"].values[0]:
            komb=np.vstack([komb,[i,k%(len(sobe.index)+1) if k%(len(sobe.index)+1)!=0 else 1]])

            
            k+=1
    komb = komb.astype(int)        
    kmb = pd.DataFrame(komb,columns=["h_id","s_id"])
    kmb = kmb.loc[kmb.index!=0]
    kmb=kmb.reset_index(drop=True)
    kmb.to_csv(PATH_DATA+"combinations.csv",index=None)
    kmb["h_id"],kmb["s_id"]  = kmb["h_id"].astype(str),kmb["s_id"].astype(str)
    rez = pd.read_csv(PATH_DATA+"rand_rez.csv")
    aranzman = trimPonude()
    aktivnosti = pd.read_csv(PATH_DATA+"aktivnosti.csv")
    akt_u_gradu = pd.read_csv(PATH_DATA+"aktivnosti_u_gradu.csv")
    akt_u_gradu['g_id'],akt_u_gradu['akt_id'],akt_u_gradu['smestaj_id'] = akt_u_gradu['g_id'].astype(str),akt_u_gradu['akt_id'].astype(str),akt_u_gradu['smestaj_id'].astype(str)
    akt_aran = pd.read_csv(PATH_DATA+"ima_aktivnost.csv")
    akt_aran['aran_id'],akt_aran['akt_id'] = akt_aran['aran_id'].astype(str),akt_aran['akt_id'].astype(str)
    return kontinenti,drzave,gradovi,hoteli,sobe,prevoz,kmb,aranzman,aktivnosti,akt_u_gradu,akt_aran,rez
    #return kontinenti,drzave,gradovi,hoteli,sobe

def downloadCityImages(items):
    
    for x in items:
        x=x+"_city_center"
        downloader.download('_'.join([y for y in x.split(' ')]), limit=1, output_dir='slikeGradova', adult_filter_off=True, force_replace=False, timeout=90, verbose=True,filter="photo")
#setGPT()    
#companyGPT()
#dataTrimming()
#print(hotelGPT("Liege"))


#downloadCityImages(pd.read_csv(PATH_DATA+"gradovi.csv")['naziv'].values)