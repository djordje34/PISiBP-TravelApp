import base64
import io
import os

import mysql.connector
import numpy as np
import pandas as pd
from generator import PATH_DATA
from mysql.connector import Error
from utils import dataTrimming, paramValuesGenerator


class Populator(object):
    
    def __new__(cls):

        if not hasattr(cls, 'instance'):
            cls.instance = super(Populator, cls).__new__(cls)
        return cls.instance
        

    def Connect(self):
        try:
            self.connection = mysql.connector.connect(host='localhost',
                                                database='travel',
                                                user='root',
                                                password='')
            if self.connection.is_connected():
                db_Info = self.connection.get_server_info()
                print("Povezan ", db_Info)
                self.cursor = self.connection.cursor()
                self.cursor.execute("select database();")
                record = self.cursor.fetchone()
                print("Povezan sa bazom: ", record)

        except Error as e:
            print("Greška u konekciji ", e)

    def Close(self):
        if self.connection.is_connected():
            self.cursor.close()
            self.connection.close()            
                
                
    def Setup(self):
        queries = [
        'DELETE FROM ima_aktivnost;','DELETE FROM grad_ima_sliku;','DELETE FROM soba_ima_sliku;',
        'DELETE FROM akt_u_gradu;','DELETE FROM aktivnosti;','DELETE FROM aranzmani;','DELETE FROM soba;',
        'DELETE FROM smestaj;','DELETE FROM grad;','DELETE FROM drzava;','DELETE FROM kontinent;',
        'DELETE FROM sobatip_hash;','DELETE FROM prevoz;','ALTER TABLE soba AUTO_INCREMENT = 1;',
        'ALTER TABLE kontinent AUTO_INCREMENT = 1;','ALTER TABLE drzava AUTO_INCREMENT = 1;','ALTER TABLE grad AUTO_INCREMENT = 1;',
        'ALTER TABLE smestaj AUTO_INCREMENT = 1;','ALTER TABLE sobatip_hash AUTO_INCREMENT = 1;','ALTER TABLE prevoz AUTO_INCREMENT = 1;',
        'ALTER TABLE aranzmani AUTO_INCREMENT = 1;','ALTER TABLE aktivnosti AUTO_INCREMENT = 1;'];
        
        for query in queries:
            self.cursor.execute(query)     
            self.connection.commit()
        
        
    def Exec(self,stmt,df):

        if type(df) == type(np.zeros(1)):
            conv_df = [tuple([x]) for x in df]
            self.cursor.executemany(stmt, conv_df)
            self.connection.commit()
            return
        
        conv_df = [tuple(x) for x in df.to_numpy()]
        self.cursor.executemany(stmt, conv_df)
        self.connection.commit()
                    
    def Append(self):
        
        
        tables = ['kontinent(ime)','drzava(ime,k_id)','grad(ime,d_id)','smestaj(naziv,adresa,kapacitet,br_zvezdica,g_id)','sobatip_hash(tip,br_kreveta,gen_cena,opis)','prevoz(tip,ime_komp,cena)','soba(smestaj_id,tip)','aranzmani(naziv,krece,vraca,smestaj_id,p_id)','aktivnosti(naziv)','akt_u_gradu(g_id,akt_id,smestaj_id)','ima_aktivnost(aran_id,akt_id)','rezervacije(ime,prezime,br_kartice,email,broj_odr,broj_dece,cena,kom,kontakt,aran_id)']
        inserts = list(dataTrimming())
        for i,table in enumerate(tables):
            vals = paramValuesGenerator(table)
            PATTERN = f"INSERT INTO {table} VALUES({vals})" 
            #self.Exec(PATTERN,)
            self.Exec(PATTERN,inserts[i])

        self.insertSobaSlike()
        self.insertGradSlike()

        return True;



    def insertBLOB(self,id,photo,where="soba_ima_sliku",param="soba_id"):
        mycursor=self.connection.cursor()
        sql = "insert into "+where+"("+param+",slika) VALUES (%s,%s)"
        mycursor.execute(sql,(id,photo))
        self.connection.commit()

    
    
    
    def insertSobaSlike(self):
        num = len(pd.read_csv(PATH_DATA+"sobe.csv").index)+1  #broj soba
        path = "slike_travel\\"
        #format slike -> {tip}-{br-k}bed-{koja_po_redu}.jpg
        #prvo iter do 3 i insert za trenutni ID posle id+=1
        tp=1
        x=1
        while tp<=num//4:
            for y in range(1,5):
                for z in range(1,4):
                    photo = path + f"{tp}-{y}beds-{z}.jpg".format(tp=tp,y=z,z=z)
                    self.insertBLOB(x,photo)
                x+=1
            tp+=1
            
            
    def insertGradSlike(self):
        gradovi = pd.read_csv(PATH_DATA+"gradovi.csv")
        imena = gradovi['naziv'].values
        id = list(range(1,len(gradovi.index)+1))
        idG=1
        for ime in imena:
            ime=ime.replace(' ','_')
            path = "slikeGradova\\"+ime+"_city_center\\Image_1.jpg"

            if os.path.exists(path):
                self.insertBLOB(idG,path,where="grad_ima_sliku",param="grad_id")
            else:
                self.insertBLOB(idG,"slikeGradova\\Šangaj_city_center\\Image_1.jpg",where="grad_ima_sliku",param="grad_id")
            idG+=1
            
pop = Populator()

pop.Connect()

pop.Setup()

pop.Append()
