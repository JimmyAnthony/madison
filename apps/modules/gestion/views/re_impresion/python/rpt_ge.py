# -*- encoding: utf-8 -*-

'''
Developed by Jimmy A.B.S.
'''

import sys
import os
import codecs
import string
import re
import unicodedata as ud
import base64
import random
import json
from datetime import datetime, date, time
from dateutil.relativedelta import relativedelta
import informixdb

from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import A4, letter
from reportlab.lib.units import inch, cm, mm
from reportlab.lib.enums import TA_JUSTIFY, TA_LEFT, TA_CENTER
from reportlab.platypus import Spacer, SimpleDocTemplate, Table, TableStyle, Preformatted, PageBreak
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus.paragraph import Paragraph
from reportlab.platypus.frames import Frame
from reportlab.platypus.flowables import XBox, KeepTogether
from reportlab.graphics.shapes import Drawing
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.lib import colors
from reportlab.lib.colors import Color, pink, black, red, blue, green, gray, white
from reportlab.graphics.barcode.common import *
from reportlab.graphics.barcode.code128 import *
from reportlab.graphics.barcode import createBarcodeDrawing
from reportlab.graphics.shapes import Drawing
from reportlab.graphics import renderPDF
from reportlab.pdfbase.pdfmetrics import registerFontFamily

class RUN_PDF:
	'''reporte x.'''
	def __init__(self,params):
		self.params=params

	def connect(self):
		os.environ['INFORMIXSERVER'] = 'ol_urbano'
		conn = informixdb.connect('scm30@ol_urbano', user='informix', password='mh_1C$_2sX')
		self.cursor = conn.cursor(rowformat = informixdb.ROW_AS_DICT)
		return self

	def query(self):
		self.params = self.params.split('&')
		records= self.params[0].replace('[','')
		records= records.replace(']','')
		records= records.replace('"','')
		records= records.split(',')
		self.query = []
		for ge in records:
			self.query.append("call scm_ss_print_ge("+ge+",null,null,null,null,"+self.params[4]+")")
		
		#print self.query
		return self

	def execute(self):
		#print self.query
		self.rs=[]
		for query in self.query:
			self.cursor.execute(query)
			self.rs.append(self.cursor.fetchall())
		#¡print self.rs
		return self

	def valida_str(self,strg):
		strg = self.valida_ascii(strg)
		if (strg == 'None'):
			strg = ''
		else:
			'''
			encoding = chardet.detect(strg)
			if encoding['encoding'] == 'ascii':
				strg = strg.decode(encoding['encoding']).encode('ascii')
			else:
				strg = codecs.decode(strg, 'latin-1')
			'''
			strg = codecs.decode(strg, 'latin-1')
				#strg = str(strg)
		return strg

	def valida_ascii(self,strg):
		'''VALIDAR CARACTERES EXTRAÑOS'''
		list_ascii=[chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8), chr(9), chr(10), chr(11), chr(12), chr(13), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19), chr(20), chr(21), chr(22), chr(23), chr(24), chr(25), chr(26), chr(27), chr(28), chr(29), chr(30), chr(31), chr(127)];

		for ascii_ in list_ascii:
			strg = strg.replace(ascii_, '')
		strg = strg.strip()
		strg = strg.rstrip()
		strg = strg.rstrip('\r\n')
		strg = strg.replace('\n', '')

		return strg

	def generate_pdf(self):
		c = canvas.Canvas("/sistemas/weburbano/public_html/tmp/"+self.params[3],pagesize=A4)#A4 - (210*mm, 302*mm)
		#c.translate(inch,inch)
		c.setAuthor('@jim')
		self.path = '/sistemas/weburbano/'
		c.setLineWidth(.3)
		c.setFont('Helvetica', 12)
		min_cal = 840 / 2
		self.height_ = min_cal
		self.width_  = 590
		eval_=1
		#RD-15960331-16500211
		'''height = 840 / 2
		width  = 590
		c.line(0,height,width,height)#medida ancho A4
		c.line(width/2,0,width/2,height*2)#medida ancho A4''' 
		#---------------------------------
		for indice in range(int(len(self.rs))):
			for row in self.rs[indice]:

				if self.height_ != 0:
					self.gen_formato_next(c,row)
					self.height_=0
				else:
					self.gen_formato_next(c,row)
					self.height_=min_cal
					c.showPage()
		#---------------------------------
		
		c.save()
		return 'El PDF se genero correctamente!'

	def gen_formato_next(self,c,row):
		self.height = self.height_
		self.width  = self.width_
		"""self.gen_rec(c,20,(self.width/2)-5,10,50,0,0,"","",0)#C
		#CONTENIDO
		ALT = self.height+35
		c.setFont('Helvetica', 7)
		c.drawString(25,ALT,"OBSERVACIONES :")
		c.drawString(90,ALT,"...............................................................................................")
		ALT -= 10
		c.drawString(25,ALT,".................................................................................................................................")
		ALT -= 10
		c.drawString(25,ALT,".................................................................................................................................")
		"""
		self.gen_rec(c,20,(self.width/2)-5,10,130,35,50,"RECEPTOR","",0)#C
		#CONTENIDO
		ALT = self.height+105
		c.setFont('Helvetica', 7)
		c.drawString(40,ALT,"FIRMA :")
		c.drawString(80,ALT,"....................................................................................................")
		ALT -= 19
		c.drawString(40,ALT,"NOMBRE :")
		c.drawString(80,ALT,"....................................................................................................")
		ALT -= 19
		c.drawString(40,ALT,"VINCULO :")
		c.drawString(80,ALT,"....................................................................................................")
		ALT -= 19
		c.drawString(40,ALT,"DNI :")
		c.drawString(80,ALT,"....................................................................................................")
		ALT -= 19
		c.drawString(40,ALT,"FECHA :")
		c.drawString(80,ALT,"........\........\........")

		c.drawString(160,ALT,"HORA :")
		c.drawString(190,ALT,"..........................")
		#+++++++++++++++++++++++++++++++++	
		self.height = self.height + 130
		self.gen_rec(c,20,(self.width/2)-5,10,130,35,50,"DATOS","",0)#B
		#CONTENIDO
		ALT = self.height+120
		c.setFont('Helvetica', 7)
		c.drawString(40,ALT,"LOGISTICA LIVIANA")
		ALT -= 17
		c.drawString(40,ALT,"SERVICIO :")
		c.drawString(83,ALT,self.valida_str(str(row['servicio'].strip()[:31])))#servicio
		c.drawString(40,ALT-8,self.valida_str(str(row['servicio'].strip()[31:150])))

		c.drawString(220,ALT,"FECHA :")
		c.drawString(250,ALT,self.valida_str(str(row['fec_ingreso'].strftime('%d/%m/%Y'))))

		ALT -= 17
		c.drawString(40,ALT,"SEGURO :")
		c.drawString(80,ALT,self.valida_str(str(row['seguro'])))

		c.drawString(100,ALT,"DOC. REF.:")
		c.drawString(140,ALT,self.valida_str(str(row['cod_rastreo'])))

		c.drawString(200,ALT,"NRO. DOC.:")
		c.drawString(240,ALT,self.valida_str(str(row['doc_numero'])))

		if row['sobre_peso'] + row['sobre_pieza'] != 0:
			ALT -= 15
			c.drawString(40,ALT,"PESO SOBRE :")
			c.drawString(100,ALT,self.valida_str(str(row['sobre_peso'])))

			c.drawString(140,ALT,"NRO. SOBRES :")
			c.drawString(205,ALT,self.valida_str(str(row['sobre_pieza'])))

		if row['valija_peso'] + row['valija_pieza'] != 0:
			ALT -= 15
			c.drawString(40,ALT,"PESO VALIJA :")
			c.drawString(100,ALT,self.valida_str(str(row['valija_peso'])))

			c.drawString(140,ALT,"CANT.VALIJAS:")
			c.drawString(205,ALT,self.valida_str(str(row['valija_pieza'])))

		if row['paquete_peso'] + row['paquete_pieza'] != 0:
			ALT -= 15
			c.drawString(40,ALT,"PESO PAQUETE :")
			c.drawString(100,ALT,self.valida_str(str(row['paquete_peso'])))

			c.drawString(140,ALT,"CANT.PAQUETES:")
			c.drawString(205,ALT,self.valida_str(str(row['paquete_pieza'])))

			ALT -= 13
			c.drawString(40,ALT,"DETALLE CONTENIDO :")
			c.drawString(120,ALT,self.valida_str(str(row['sku_descri'].strip()[:40])))#sku_descri
			c.drawString(40,ALT-8,self.valida_str(str(row['sku_descri'].strip()[40:150])))

		#+++++++++++++++++++++++++++++++++
		self.height = self.height + 130

		#IMAGEN
		c.drawImage(self.path + 'public_html/images/logo.jpg', 20, self.height+132, 55, 23, mask=None)
		c.setFont('Helvetica', 6)

		c.drawString(85,self.height+144,"www.urbanoexpress.com")
		c.drawString(85,self.height+137.5,"Telf:415-1800")
		c.drawString(85,self.height+132,"Av. Argentina 3127 - Lima Cercado")
		self.gen_rec(c,20,(self.width/2)-5,10,130,35,50,"REMITENTE",self.valida_str(str(row['sigla'])),(self.width/2)-41)#A
		#CONTENIDO
		c.setFont('Helvetica', 7)
		ALT = self.height+118
		c.drawString(40,ALT,"CLIENTE :")
		c.drawString(80,ALT,self.valida_str(str(row['shipper'])))
		ALT -= 17
		c.drawString(40,ALT,"DOMICILIO :")
		c.drawString(83,ALT,self.valida_str(str(row['dir_calle'].strip()[:47])))#dir_calle
		c.drawString(40,ALT-8,self.valida_str(str(row['dir_calle'].strip()[47:150])))
		ALT -= 17
		c.drawString(40,ALT,"DISTRITO :")
		c.drawString(80,ALT,self.valida_str(str(row['distrito'])))
		ALT -= 17
		c.drawString(40,ALT,"TELEFONO :")
		c.drawString(84,ALT,'')
		ALT -= 17
		c.drawString(40,ALT,"REMITENTE :")
		c.drawString(87,ALT,self.valida_str(str(row['remitente'])))
		ALT -= 17
		c.drawString(40,ALT,"FECHA RECOJO :")
		c.drawString(100,ALT,"")

		c.drawString(190,ALT,"HORA RECOJO :")
		c.drawString(250,ALT,"")

		ALT -= 17
		c.drawString(40,ALT,"COD COURIER :")
		c.drawString(95,ALT,"")
		#---------------------------------
		#reset
		self.height = self.height_
		self.width  = self.width_
		#---------------------------------

		#self.gen_rec(c,300,self.width-20,50,130,0,0,"","",0)#F

		

		#+++++++++++++++++++++++++++++++++
		self.height = self.height + 130
		barcode = createBarcodeDrawing('Code128', value = str(row['gui_numero']), barWidth = 0.2 * mm, width = 150 * mm, barHeight = 11 * mm, fontSize = 8, humanReadable = False, fontName = 'xcalibri', borderWidth = 1)
		barcode.drawOn(c, 220, self.height+85)
		c.setFont('Helvetica', 14)
		c.drawString((self.width/2)+100,self.height+120,self.valida_str(str(row['gui_numero'])))

		

		#self.gen_rec(c,300,self.width-20,10,80,0,0,"","",0)#E
		#CONTENIDO
		c.setFont('Helvetica', 6)
		ALT = self.height+61
		ANC = self.width_-20

		c.rect(300,self.height-120,(self.width/2)-25,195, fill=0)
		c.line(480,self.height-120,480,self.height+75)
		c.line(465,self.height-120,465,self.height+75)
		c.line(450,self.height-120,450,self.height+75)
		c.line(375,self.height+16,375,self.height+75)


		c.drawString(ANC-83,ALT+3,"MOTIVO DE NO ENTREGA")
		c.drawString(ANC-99,ALT+3,"1")
		c.drawString(ANC-114,ALT+3,"2")
		c.drawString(ANC-182,ALT+3,"SEGUNDA VISITA")
		c.drawString(ANC-255,ALT+3,"PRIMERA VISITA")

		c.line(300,ALT,ANC,ALT)
		ALT -= 15
		c.line(300,ALT,ANC,ALT)
		c.drawString(ANC-83,ALT+3,"AUSENTE")
		c.drawString(ANC-189,ALT+3,"FECHA:")
		c.drawString(ANC-265,ALT+3,"FECHA:")

		ALT -= 15
		c.line(300,ALT,ANC,ALT)
		c.drawString(ANC-83,ALT+3,"DIRECCIÓN ERRADA")
		c.drawString(ANC-189,ALT+3,"HORA  :")
		c.drawString(ANC-265,ALT+3,"HORA  :")

		ALT -= 15
		c.line(300,ALT,ANC,ALT)
		c.drawString(ANC-83,ALT+3,"DESCONOCIDO")
		c.drawString(ANC-189,ALT+3,"CÓDIGO:")
		c.drawString(ANC-265,ALT+3,"CÓDIGO:")

		ALT -= 15
		c.line(300,ALT,ANC,ALT)
		c.drawString(ANC-83,ALT+3,"RECHAZADO")
		c.drawString(ANC-220,ALT+3,"OBSERVACIONES")

		ALT -= 15
		c.line(450,ALT,ANC,ALT)
		c.drawString(ANC-83,ALT+3,"SE MUDO")
		c.drawString(ANC-264, ALT+3, "...................................................................................")

		ALT -= 15
		c.line(450,ALT,ANC,ALT)
		c.drawString(ANC-83,ALT+3,"FALTA DE DATOS")
		c.drawString(ANC-264, ALT+3, "...................................................................................")

		ALT -= 15
		c.line(450,ALT,ANC,ALT)
		c.drawString(ANC-83,ALT+3,"SE DAÑO/ROTURA")
		c.drawString(ANC-264, ALT+3, "...................................................................................")

		ALT -= 15
		c.line(450,ALT,ANC,ALT)
		c.drawString(ANC-83,ALT+3,"OTROS")
		c.drawString(ANC-264, ALT+3, "...................................................................................")

		ALT -= 15
		c.line(450,ALT,ANC,ALT)
		c.drawString(ANC-264, ALT+3, "...................................................................................")
		ALT -= 15
		c.line(450,ALT,ANC,ALT)
		c.drawString(ANC-264, ALT+3, "...................................................................................")
		ALT -= 15
		c.line(450,ALT,ANC,ALT)
		c.drawString(ANC-264, ALT+3, "...................................................................................")

		#+++++++++++++++++++++++++++++++++
		self.height = self.height + 130
		self.gen_rec(c,300,self.width-20,10,130,((self.width)/2)+20,50,"DESTINATARIO",self.valida_str(str(row['sigla_des'])),(self.width)-57)#D
		#CONTENIDO
		c.setFont('Helvetica', 7)
		ALT = self.height+118
		ANC = (self.width_/2)-15
		c.drawString(ANC+40,ALT,"DESTINATARIO :")
		c.drawString(ANC+100,ALT,self.valida_str(str(row['cli_nombre'])))
		ALT -= 17
		c.drawString(ANC+40,ALT,"DOMICILIO :")
		c.drawString(ANC+83,ALT,self.valida_str(str(row['dir_calle_des'].strip()[:47])))
		c.drawString(ANC+40,ALT-8,self.valida_str(str(row['dir_calle_des'].strip()[47:150])))
		ALT -= 22
		c.drawString(ANC+40,ALT,"DISTRITO :")
		c.drawString(ANC+80,ALT,self.valida_str(str(row['distrito_des'])))
		ALT -= 17
		c.drawString(ANC+40,ALT,"REFERENCIA :")
		c.drawString(ANC+92,ALT,self.valida_str(str(row['referencia_des'].strip()[:45])))
		c.drawString(ANC+40,ALT-8,self.valida_str(str(row['referencia_des'].strip()[45:150])))
		ALT -= 22
		c.drawString(ANC+40,ALT,"TELEFONO :")
		c.drawString(ANC+84,ALT,self.valida_str(str(row['tel_numero_des'])))
		ALT -= 17
		c.drawString(ANC+40,ALT,"CONTACTO :")
		c.drawString(ANC+84,ALT,"")
		#
		return self

	def gen_rec(self,c,wf,wr,hf,hr,rf,htxr,txr,tx,wtx):#hf = 10 hr = 130
		c.line(wf,self.height+hr,wr,self.height+hr)#Linea horizontal 1
		c.line(wf,self.height+hf,wf,self.height+hr)#Linea Vertical 1
		if rf>0:
			c.line(rf,self.height+hf,rf,self.height+hr) #Linea Referencia
		c.line(wr,self.height+hf,wr,self.height+hr) #Linea Vertical 2
		c.line(wf,self.height+hf,wr,self.height+hf) #Linea horizontal 2
		#---------------------------------
		if tx != '':
			c.setFont('Helvetica', 12)
			c.line(wr-40,self.height+hr-15,wr-40,self.height+hr) #RECUADRO Vertical 1
			c.line(wr-40,self.height+hr-15,wr,self.height+hr-15) #RECUADRO horizontal 1
			#part = tx.split()
			c.drawString(wtx,self.height+118,tx[:1]+"  "+tx[1:2]+"  "+tx[2:3])#TEXTO ORIGEN - DESTINO

		if rf!=0:
			c.rotate(90)
			# change color
			#c.setFillColorRGB(0,0,0.77)
			c.setFont('Helvetica', 8)
			c.drawString(self.height+htxr,((rf*-1)+5),txr)
			c.rotate(-90)
		return self



print RUN_PDF(base64.b64decode(sys.argv[1])).connect().query().execute().generate_pdf()