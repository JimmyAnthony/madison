# -*- encoding: utf-8 -*-

'''
@Robert Salvatierra -> rsalvatierra@celats.org

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

path = '/sistemas/weburbano/apps/modules/gestion/views/preclamos/python/'


pdfmetrics.registerFont(TTFont('xcalibri', path + 'fonts/calibri.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriBd', path + 'fonts/calibrib.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriIt', path + 'fonts/calibrii.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriBI', path + 'fonts/calibriz.ttf'))



registerFontFamily('xcalibri',normal='xcalibri',bold='xcalibriBd',italic='xcalibriIt',boldItalic='xcalibriBI')

styles = getSampleStyleSheet()
styles.add(ParagraphStyle(name = 'Justify', fontName = 'xcalibri', fontSize = 7, leading = 6, alignment = TA_JUSTIFY, bulletFontName = 'xcalibri', borderWidth = 0, borderColor = '#000000'))

styleN = styles['Justify']

'''
Parámetros de conexión
'''
os.environ['INFORMIXSERVER'] = 'ol_urbano'
conn = informixdb.connect('scm30@ol_urbano', user='informix', password='mh_1C$_2sX')
conn.autocommit = True

try:
	cursor = conn.cursor(rowformat = informixdb.ROW_AS_DICT)
	try:
		#print "call scm_reclamo_impresion(" + str(params[1])+","+ str(params[0])+","+str(params[6])+","+str(params[2])+","+str(params[3])+","+str(params[7])+","+str(params[4]) + ")"
		cursor.callproc("scm_reclamo_impresion", (str(params[1]), str(params[0]), str(params[6]), str(params[2]), str(params[3]), str(params[7]), str(params[4])))
		rows = cursor.fetchall()
	finally:	
		cursor.close()
finally:
	conn.close()

#print rows

#query_string = "call spv_impresioncargo_sp1("+params[0]+","+params[2]+","+params[1]+");"

#cursor.execute(query_string)
#rows = cursor.fetchall()

width = 210
height = 297
#salto = height / 16
#espacio = width / 4 
columnax = 0
columnay = 0
y = 0
x = 0

ealto = 29 # Alto de la etiqueta en mm
eancho = 66 # Ancho de la etiqueta en mm
NX = 3     # cantidad de etiquetas para x entero
NY = 10    # Cantidad de etiquetas para y entero

etiquetas = NX * NY # No tocar


def init():
	#width, height = letter
	p = canvas.Canvas("/sistemas/weburbano/public_html/tmp/reclamos/rpt_etiquetas-"+str(params[5])+".pdf", pagesize=(210*mm, 297*mm))
	p.setAuthor('@Robert')
	p.setTitle('Etiquetas')
	p.setSubject('Etiquetas')
	p.translate(0, 297*mm)
	################################################################
	
	arrayData = []
	arraySearch = []
	for row in rows:
		if row['reclamo'] not in arraySearch:
			arrayData.append(
                {   'reclamo': row['reclamo'], 'fec_reclamo': row['fec_reclamo'], 'agencia': row['agencia'], 'shipper': row['shipper'], 
                    'servicio': row['servicio'], 'ciclo': row['ciclo'], 'cliente': row['cliente'],'barra': row['barra'],'cli_codigo': row['cli_codigo'], 'direccion': row['direccion'], 
                    'referencia': row['referencia'], 'distrito': row['distrito'], 'estado': row['estado'], 'fec_estado': row['fec_estado'], 
                    'manifiesto': row['manifiesto'], 'zon_ld': row['zon_ld'], 'fecha_ld': row['fecha_ld'], 'mot_reclamo': row['mot_reclamo'], 
                    'det_reclamo': row['det_reclamo'], 'codigo_courier': row['codigo_courier'], 'id_imprime': row['id_imprime']
                })
			arraySearch.append(row['reclamo'])

	vi = 0
	index = 0
	for value in arrayData:
		vi += 1
		index += 1


		draw_contenido(p, index, value)
		index = 0 if (index % etiquetas == 0) else index
		if index == 0:
			p.showPage()
			p.translate(0, 297*mm)
	
	p.showPage()
	################################################################
	p.save()

def draw_contenido(p, index, value):

	global y
	global x
	global columnax
	global columnay

	if ((index-1) % NX == 0 ):
		y = ealto * columnay +34
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

	barcode = createBarcodeDrawing('Code128', value = value['barra'], barHeight= 17,barWidth = 0.010 * inch, fontSize = 8, humanReadable = True )
	
	barcode.drawOn(p, ((x+3) * mm), -((y-19)*mm)) #dibuja la barra
	#p.rect((x+6)*mm, -(y*mm), eancho*mm, ealto*mm, 1, 0)#dibuja el rectangulo

	p.setFont('xcalibri', 8)
	#p.drawString((x+20)*mm, -((y-19)*mm), codecs.decode(valida_none(str(value['barra'])), 'latin-1'))
	
	p.setFont('xcalibriBd', 8)
	p.drawString((x+9)*mm, -((y-16)*mm),  codecs.decode(valida_none(value['cliente'][:42]), 'latin-1'))
	#p.drawRightString((x+53)*mm, -((y-13)*mm), codecs.decode(valida_none(value['serie']), 'latin-1'))
	p.setFont('xcalibri', 8)
	p.drawRightString((x+65)*mm, -((y-4)*mm), codecs.decode(valida_none(value['distrito']), 'latin-1'))

	calle = codecs.decode(valida_none(value['direccion'].strip() +' '+ value['referencia'].strip()), 'latin-1')
	parrafo = Paragraph(calle, styleN)

	
	aW = (width - 148)
	aH = 4
	data = [[parrafo]]
	table = Table(data, aW*mm, aH*mm)
	table.setStyle([('VALIGN',(0,0),(0,0),'TOP')])
	#--table.setStyle([('GRID',(0,0),(-1,-1),0.25,colors.grey)])
	table.wrap(aW*mm, -((y - 100)*mm))
	table.drawOn(p, (x+7)*mm, -((y - 12.5)*mm))
	

def valida_none(cadena):
	cadena = '' if (cadena == None) else cadena
	return cadena

init()
print 'Se genero correctamente!'