# -*- encoding: utf-8 -*-

'''
@Robert Salvatierra -> robertsalvatierraq@gmail.com
'''
import sys
import os
import codecs
import string
import base64
import random
import json
from datetime import datetime, date, time
import informixdb

from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import A4, letter
from reportlab.lib.units import inch, cm, mm
from reportlab.lib.enums import TA_JUSTIFY
from reportlab.platypus import Spacer, SimpleDocTemplate, Table, TableStyle, Preformatted, PageBreak
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus.paragraph import Paragraph
from reportlab.platypus.frames import Frame
from reportlab.platypus.flowables import XBox, KeepTogether
from reportlab.graphics.shapes import Drawing
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.lib import colors
from reportlab.graphics.barcode.common import *
from reportlab.graphics.barcode.code128 import *
from reportlab.graphics.barcode import createBarcodeDrawing
from reportlab.graphics.shapes import Drawing
from reportlab.graphics import renderPDF
from reportlab.pdfbase.pdfmetrics import registerFontFamily

param = base64.b64decode(sys.argv[1])
params = param.split('&')

path = '/sistemas/weburbano/apps/modules/gestion/views/procesar_ftp/python/'
pdfmetrics.registerFont(TTFont('xcalibri', path + 'fonts/calibri.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriBd', path + 'fonts/calibrib.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriIt', path + 'fonts/calibrii.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriBI', path + 'fonts/calibriz.ttf'))

registerFontFamily('xcalibri',normal='xcalibri',bold='xcalibriBd',italic='xcalibriIt',boldItalic='xcalibriBI')

styles = getSampleStyleSheet()
styles.add(ParagraphStyle(name = 'Justify', fontName = 'xcalibri', fontSize = 7, leading = 5, alignment = TA_JUSTIFY, bulletFontName = 'xcalibri', borderWidth = 5))#, borderColor = '#FFFFFF'#fondo de la celda

styleN = styles['Justify']

'''
Parámetros de conexión
'''
os.environ['INFORMIXSERVER'] = 'ol_urbano'
conn = informixdb.connect('scm30@ol_urbano', user='scm30', password='scm30urbano')
cursor = conn.cursor(rowformat = informixdb.ROW_AS_DICT)
#query_string = "call spv_impresioncargo_sp1("+params[0]+","+params[2]+","+params[1]+");"
query_string = "call scm_gestor_ftp_pu_print_label("+params[0]+",1);"

cursor.execute(query_string)
rows = cursor.fetchall()

width = 63 #210
height = 30 #297
#salto = height / 16
#espacio = width / 4 
columnax = 0
columnay = 0
y = 0
x = 0

ealto = 17 # Alto de la etiqueta en mm
eancho = 50 # Ancho de la etiqueta en mm
NX = 1     # cantidad de etiquetas para x entero
NY = 1    # Cantidad de etiquetas para y entero

etiquetas = NX * NY # No tocar


def init():
	width, height = letter

	p = canvas.Canvas("/sistemas/weburbano/public_html/tmp/etiqueta_acuse/etiqueta-"+str(params[0])+".pdf", pagesize=(63*mm, 30*mm))

	p.setAuthor('@Robert')
	p.setTitle('Etiquetas')
	p.setSubject('Etiquetas')
	p.translate(0, 30*mm)
	################################################################
	
	vi = 0
	index = 0
	count = rows.__len__()
	for value in rows:
		vi += 1
		index += 1


		draw_contenido(p, index, value)
		index = 0 if (index % etiquetas == 0) else index
		if index == 0:
			if count != vi:
				p.showPage()
				p.translate(0, 30*mm)
	
	#p.showPage()
	################################################################
	p.save()

def draw_contenido(p, index, value):

	global y
	global x
	global columnax
	global columnay

	if ((index-1) % NX == 0 ):
		y = ealto * columnay +20
		x = 0
		columnax = 0 
		columnay += 1
	else:
		columnax += 1
		x = eancho * columnax
	if (columnax == NX):
		columnax = 0
		
	if (columnay == NY):
		columnay = 0

	#print ealto	

	barcode = createBarcodeDrawing('Code128', value = value['gui_numero'], barHeight= 20,barWidth = 0.015 * inch, fontSize = 8, humanReadable = True )
	
	barcode.drawOn(p, ((x+1) * mm), -((y-5)*mm)) #dibuja la barra
	#p.rect((x+6)*mm, -(y*mm), eancho*mm, ealto*mm, 1, 0)#dibuja el rectangulo

	p.setFont('xcalibri', 7)
	p.drawString((x+4)*mm, -((y-15)*mm), codecs.decode(valida_none(value['shipper']), 'latin-1'))
	p.setFont('xcalibriBd', 7)
	p.drawString((x+4)*mm, -((y-3.5)*mm),  codecs.decode(valida_none(value['cli_nombre']), 'latin-1'))#value['cli_nombre'][:45]
	p.drawRightString((x+60)*mm, -((y-13)*mm), codecs.decode(valida_none(value['cod_rastreo']), 'latin-1'))
	p.drawString((x+4)*mm, -((y+3)*mm),  codecs.decode('DE:'+valida_none(value['prov_origen'])+' A '+valida_none(value['prov_destino'])+'                   PESO:'+valida_none(value['peso'])+'                       PIEZA:'+valida_none(value['piezas']), 'latin-1'))#value['cli_nombre'][:45]
	p.setFont('xcalibri', 7)
	p.drawRightString((x+60)*mm, -((y+6)*mm), codecs.decode(valida_none(value['distrito']), 'latin-1'))

	calle = codecs.decode(valida_none((value['dir_calle']+' '+value['dir_referen'])[:200] ), 'latin-1')#value['dir_calle'].strip()
	parrafo = Paragraph(calle, styleN)




	
	aW = (width-10 )
	aH = 4
	data = [[parrafo]]
	table = Table(data, aW*mm, aH*mm)
	table.setStyle([('VALIGN',(0,0),(0,0),'TOP')])
	#table.setStyle([('GRID',(0,0),(-1,-1),0.25,colors.grey)])
	table.wrap(aW*mm, -((y - 10)*mm))
	table.drawOn(p, (x+2)*mm, -((y - 1)*mm))
	

def valida_none(cadena):
	cadena = '' if (cadena == None) else cadena
	return cadena

init()
print 'Se genero correctamente!'