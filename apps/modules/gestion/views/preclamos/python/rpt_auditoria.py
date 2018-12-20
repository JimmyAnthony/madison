# -*- encoding: utf-8 -*-

import sys
import os
#os.environ['PYTHON_EGG_CACHE'] = '/tmp'
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

import warnings
warnings.filterwarnings("ignore")

param = base64.b64decode(sys.argv[1])
params = param.split('&')

path = '/sistemas/weburbano/apps/modules/gestion/views/preclamos/python/'


pdfmetrics.registerFont(TTFont('xcalibri', path + 'fonts/calibri.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriBd', path + 'fonts/calibrib.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriIt', path + 'fonts/calibrii.ttf'))
pdfmetrics.registerFont(TTFont('xcalibriBI', path + 'fonts/calibriz.ttf'))

registerFontFamily('xcalibri',normal='xcalibri',bold='xcalibriBd',italic='xcalibriIt',boldItalic='xcalibriBI')

styles = getSampleStyleSheet()
styles.add(ParagraphStyle(name = 'Justify', fontName = 'xcalibri', fontSize = 4, leading = 4.5, alignment = TA_LEFT, bulletFontName = 'xcalibri', borderWidth = 0, borderColor = '#000000'))
styles.add(ParagraphStyle(name = 'Justify1', fontName = 'xcalibri', fontSize = 4, leading = 4, alignment = TA_LEFT, bulletFontName = 'xcalibri', borderWidth = 0, borderColor = '#000000'))
styles.add(ParagraphStyle(name = 'Justify2', fontName = 'xcalibriBd', fontSize = 4, leading = 4, alignment = TA_LEFT, bulletFontName = 'xcalibriBd', borderWidth = 0, borderColor = '#000000'))
styles.add(ParagraphStyle(name = 'Justify3', fontName = 'xcalibriBd', fontSize = 9, leading = 12, alignment = TA_CENTER, bulletFontName = 'xcalibriBd', borderWidth = 0, borderColor = '#000000', textColor = black))
styles.add(ParagraphStyle(name = 'Justify4', fontName = 'xcalibri', fontSize = 8, leading = 7, alignment = TA_LEFT, bulletFontName = 'xcalibri', borderWidth = 0, borderColor = '#000000', textColor = black))
styles.add(ParagraphStyle(name = 'Justify5', fontName = 'xcalibri', fontSize = 8, leading = 12, alignment = TA_CENTER, bulletFontName = 'xcalibri', borderWidth = 0, borderColor = '#000000', textColor = black))

styleN = styles['Justify']
styleN1 = styles['Justify1']
styleN2 = styles['Justify2']
styleN3 = styles['Justify3']
styleN4 = styles['Justify4']
styleN5 = styles['Justify5']

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
        rs = cursor.fetchall()
    finally:
        cursor.close()
finally:
    conn.close()

# print len(rs)
# print query_string
# print rs

width = 210
height = 297

columnax = 0
columnay = 0

y = 0
x = 0
ealto = 73 # Alto de la etiqueta en mm
eancho = 200 # Ancho de la etiqueta en mm
NX = 1     # cantidad de etiquetas para x entero
NY = 4    # Cantidad de etiquetas para y entero
etiquetas = NX * NY # No tocar

def draw_init():
    p = canvas.Canvas("/sistemas/weburbano/public_html/tmp/reclamos/rpt_auditoria-"+str(params[5])+".pdf", pagesize=(width*mm, height*mm))

    p.setAuthor('@remicioluis')
    p.setTitle('manifiesto')
    p.setSubject('manifiesto')
    p.translate(0, height*mm)
    ################################################################
    # p.setFont('xcalibri', 9)
    # p.drawString(5*mm, -((4)*mm), query_string)
    ################################################################

    arrayData = []
    arraySearch = []
    for row in rs:
        if row['reclamo'] not in arraySearch:
            arrayData.append(
                {   'reclamo': row['reclamo'],'rec_num': row['rec_num'], 'fec_reclamo': row['fec_reclamo'], 'agencia': row['agencia'], 'shipper': row['shipper'], 
                    'servicio': row['servicio'], 'ciclo': row['ciclo'], 'cliente': row['cliente'],'cli_codigo': row['cli_codigo'], 'direccion': row['direccion'], 
                    'referencia': row['referencia'], 'distrito': row['distrito'], 'estado': row['estado'], 'fec_estado': row['fec_estado'], 
                    'manifiesto': row['manifiesto'], 'zon_ld': row['zon_ld'], 'fecha_ld': row['fecha_ld'], 'mot_reclamo': row['mot_reclamo'], 
                    'det_reclamo': row['det_reclamo'], 'codigo_courier': row['codigo_courier'], 'id_imprime': row['id_imprime'], 'chid': groupData(row['reclamo'])
                })
            arraySearch.append(row['reclamo'])

    ################################################################

    index = 0
    for value in arrayData:
        index+=1
        # draw_corner(p)
        draw_header(p, value)
        draw_contenido(p, value)

        if index != len(arrayData):
            p.showPage()
            p.translate(0, height*mm)       
    
    
    # p.showPage()
    ################################################################
    p.save()

def groupData(idReclamo):
    arrayData = []
    for row in rs:
        if row['reclamo'] == idReclamo:
            arrayData.append(
                {   'reclamo': row['reclamo'],'rec_num': row['rec_num'], 'fec_reclamo': row['fec_reclamo'], 'agencia': row['agencia'], 'shipper': row['shipper'], 
                    'servicio': row['servicio'], 'ciclo': row['ciclo'], 'cliente': row['cliente'],'cli_codigo': row['cli_codigo'], 'direccion': row['direccion'], 
                    'referencia': row['referencia'], 'distrito': row['distrito'], 'estado': row['estado'], 'fec_estado': row['fec_estado'], 
                    'manifiesto': row['manifiesto'], 'zon_ld': row['zon_ld'], 'fecha_ld': row['fecha_ld'], 'mot_reclamo': row['mot_reclamo'], 
                    'det_reclamo': row['det_reclamo'], 'codigo_courier': row['codigo_courier'], 'id_imprime': row['id_imprime']
                })
    return arrayData

def draw_contenido(p, rs):
    x = 10
    y = 10 + 6 + 8

    p.setFont('xcalibriBd', 12)
    p.drawString(x*mm, -((y)*mm), codecs.decode(valida_none('FORMATO DE AUDITORIA'), 'latin-1'))

    wCuadro = (width - 20) / 2
    hCuadro = (height - 20) / 5

    y+=5
    p.setFont('xcalibriBd', 9)
    p.drawString(x*mm, -((y)*mm), 'Shipper: ')
    p.setFont('xcalibri', 9)
    p.drawString((x + 12)*mm, -((y)*mm), codecs.decode(valida_none(rs['shipper']), 'latin-1').strip()[:50])

    p.setFont('xcalibriBd', 9)
    p.drawString((x + 110)*mm, -((y)*mm), 'Producto: ')
    p.setFont('xcalibri', 9)
    p.drawString((x + 125)*mm, -((y)*mm), codecs.decode(valida_none(rs['servicio']), 'latin-1').strip()[:40])
    y+=5
    p.setFont('xcalibriBd', 9)
    p.drawString(x*mm, -((y)*mm), 'Cliente: ')
    p.setFont('xcalibri', 9)
   # cliente = rs['cli_codigo']
    
    p.drawString((x + 11)*mm, -((y)*mm), codecs.decode(valida_none(rs['cli_codigo']+' - '+rs['cliente']), 'latin-1').strip()[:55])
    y+=5
    p.setFont('xcalibriBd', 9)
    p.drawString(x*mm, -((y)*mm), codecs.decode('Dirección: ', 'utf-8'))
    p.setFont('xcalibri', 9)
    p.drawString((x + 14)*mm, -((y)*mm), codecs.decode(valida_none(rs['direccion']), 'latin-1').strip()[:55])
    y+=5
    p.setFont('xcalibriBd', 9)
    p.drawString(x*mm, -((y)*mm), 'Referencia:')
    p.setFont('xcalibri', 9)
    p.drawString((x + 16)*mm, -((y)*mm), codecs.decode(valida_none(rs['referencia']), 'latin-1').strip()[:55])
    y+=5
    p.setFont('xcalibriBd', 9)
    p.drawString(x*mm, -((y)*mm), 'Distrito:')
    p.setFont('xcalibri', 9)
    p.drawString((x + 12)*mm, -((y)*mm), codecs.decode(valida_none(rs['distrito']), 'latin-1').strip()[:35])
    y+=5
    p.setFont('xcalibriBd', 9)
    p.drawString(x*mm, -((y)*mm), 'Motivo de reclamo:')
    p.setFont('xcalibri', 9)
    p.drawString((x + 27)*mm, -((y)*mm), codecs.decode(valida_none(rs['mot_reclamo']), 'latin-1').strip()[:35])
    p.setFont('xcalibriBd', 9)
    p.drawString((x + 110)*mm, -((y)*mm), 'Agencia:')
    p.setFont('xcalibri', 9)
    p.drawString((x + 123)*mm, -((y)*mm), codecs.decode(valida_none(rs['agencia']), 'latin-1'))
    p.setFont('xcalibriBd', 9)
    p.drawString((x + 162)*mm, -((y)*mm), 'Fecha:')
    p.setFont('xcalibri', 9)
    p.drawString((x + 172)*mm, -((y)*mm), codecs.decode(valida_none(str(rs['fec_reclamo'].strftime('%d/%m/%Y'))), 'latin-1'))
    y+=5
    p.setFont('xcalibriBd', 9)
    p.drawString(x*mm, -((y)*mm), 'Detalle reclamo:')
    p.setFont('xcalibri', 9)
    p.drawString((x + 23)*mm, -((y)*mm), codecs.decode(valida_none(rs['det_reclamo']), 'latin-1').strip()[:40])

    y+=1
    wCuadro = width - 21
    hCuadro = 5.5

    for i in range(0, int(len(rs['chid'])) +1 ):
        draw_square(p, x, (y + (hCuadro * i)), wCuadro, hCuadro, fill = 0)

    h = 5.5
    textos = ['Ciclo','Servicio','Estado','Fecha','Zona','Id.Manif.','Cod.Courier']
    metric = [0, 1, 3, 5, 6, 8, 9]
    metric01 = [((wCuadro/10)*1), ((wCuadro/10)*2), ((wCuadro/10)*2), ((wCuadro/10)*1), ((wCuadro/10)*2), ((wCuadro/10)*1), ((wCuadro/10)*1)]

    for i in range(0, 7):
        texto_parrafo = textos[i]
        parrafo = Paragraph(texto_parrafo, styleN3)
        aW = ( 5.5 + metric01[i] )
        aH = 5
        data = [[parrafo]]
        table = Table(data, aW*mm, aH*mm)
        table.setStyle([('VALIGN',(0,0),(0,-1),'CENTER')])
        table.wrap(aW*mm, -((y)*mm))
        table.drawOn(p, ( (x - 2) + ( ((wCuadro/10) + 0.8) * metric[i] ) )*mm, -(( (y + 5) *mm)) )

    p.setLineWidth(mm*0.075)
    p.setStrokeColor(gray)
    metric = [1, 3, 5, 6, 8, 9]
    for i in range(0, 6):
        p.line((x + ( ((wCuadro/10) + 0.8) * metric[i] ) )*mm, -((y)*mm), (x + ( ((wCuadro/10) + 0.8) * metric[i] ) )*mm, -((y + (hCuadro * (int(len(rs['chid'])) +1) ))*mm))
    p.setStrokeColor(black)
    p.setLineWidth(mm*0.35)

    metric = [0, 1, 3, 5, 6, 8, 9]
    metric01 = [((wCuadro/10)*1), ((wCuadro/10)*2), ((wCuadro/10)*2), ((wCuadro/10)*1), ((wCuadro/10)*2), ((wCuadro/10)*1), ((wCuadro/10)*1)]
    i = 0
    wCuadro = width - 21
    hCuadro = 5.5
    for row in rs['chid']:
        aH = 7

        texto_parrafo = codecs.decode(valida_none(str(row['ciclo'].strftime('%d/%m/%Y'))), 'latin-1')
        parrafo = Paragraph(texto_parrafo, styleN5)
        aW = ( 5 + metric01[0] )
        data = [[parrafo]]
        table = Table(data, aW*mm, aH*mm)
        table.setStyle([('VALIGN',(0,0),(0,-1),'CENTER')])
        table.wrap(aW*mm, -((y)*mm))
        table.drawOn(p, ( (x - 1) + ( (wCuadro/10) * metric[0] ) )*mm, -((y + (hCuadro * (i + 2) ))*mm) )

        texto_parrafo = codecs.decode(valida_none(str(row['servicio'])), 'latin-1')
        parrafo = Paragraph(texto_parrafo, styleN4)
        aW = ( 5 + metric01[1] )
        data = [[parrafo]]
        table = Table(data, aW*mm, aH*mm)
        table.setStyle([('VALIGN',(0,0),(0,-1),'CENTER')])
        table.wrap(aW*mm, -((y)*mm))
        table.drawOn(p, ( (x - 0.5) + ( (wCuadro/10) * metric[1] ) )*mm, -((y + (hCuadro * (i + 2) ))*mm) )

        texto_parrafo = codecs.decode(valida_none(str(row['estado'])), 'latin-1')
        parrafo = Paragraph(texto_parrafo, styleN4)
        aW = ( 5 + metric01[2] )
        data = [[parrafo]]
        table = Table(data, aW*mm, aH*mm)
        table.setStyle([('VALIGN',(0,0),(0,-1),'CENTER')])
        table.wrap(aW*mm, -((y)*mm))
        table.drawOn(p, ( (x + 1) + ( (wCuadro/10) * metric[2] ) )*mm, -((y + (hCuadro * (i + 2) ))*mm) )

        texto_parrafo = codecs.decode(valida_none(str(row['fec_estado'].strftime('%d/%m/%Y'))), 'latin-1')
        parrafo = Paragraph(texto_parrafo, styleN5)
        aW = ( 5 + metric01[3] )
        data = [[parrafo]]
        table = Table(data, aW*mm, aH*mm)
        table.setStyle([('VALIGN',(0,0),(0,-1),'CENTER')])
        table.wrap(aW*mm, -((y)*mm))
        table.drawOn(p, ( (x + 2) + ( (wCuadro/10) * metric[3] ) )*mm, -((y + (hCuadro * (i + 2) ))*mm) )

        texto_parrafo = codecs.decode(valida_none(str(row['zon_ld'])), 'latin-1')
        parrafo = Paragraph(texto_parrafo, styleN4)
        aW = ( 5 + metric01[4] )
        data = [[parrafo]]
        table = Table(data, aW*mm, aH*mm)
        table.setStyle([('VALIGN',(0,0),(0,-1),'CENTER')])
        table.wrap(aW*mm, -((y)*mm))
        table.drawOn(p, ( (x + 3.5) + ( (wCuadro/10) * metric[4] ) )*mm, -((y + (hCuadro * (i + 2) ))*mm) )

        texto_parrafo = codecs.decode(valida_none(str(row['manifiesto'])), 'latin-1')
        parrafo = Paragraph(texto_parrafo, styleN5)
        aW = ( 5 + metric01[5] )
        data = [[parrafo]]
        table = Table(data, aW*mm, aH*mm)
        table.setStyle([('VALIGN',(0,0),(0,-1),'CENTER')])
        table.wrap(aW*mm, -((y)*mm))
        table.drawOn(p, ( (x + 4) + ( (wCuadro/10) * metric[5] ) )*mm, -((y + (hCuadro * (i + 2) ))*mm) )

        texto_parrafo = codecs.decode(valida_none(str(row['codigo_courier'])), 'latin-1')
        parrafo = Paragraph(texto_parrafo, styleN5)
        aW = ( 5 + metric01[6] )
        data = [[parrafo]]
        table = Table(data, aW*mm, aH*mm)
        table.setStyle([('VALIGN',(0,0),(0,-1),'CENTER')])
        table.wrap(aW*mm, -((y)*mm))
        table.drawOn(p, ( (x + 5.5) + ( (wCuadro/10) * metric[6] ) )*mm, -((y + (hCuadro * (i + 2) ))*mm) )

        i+=1

    y+=35
    

    p.setLineWidth(mm*0.1)
    p.line((x)*mm, -((y)*mm), (width - 10)*mm, -((y)*mm))
    p.setLineWidth(mm*0.1)
    # ---------------------------------------------------------------------
    y+=4
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), 'FECHA DE AUDITORIA')
    p.drawString((x + 75)*mm, -((y)*mm), 'HORA DE AUDITORIA')
    p.drawString((x + 111)*mm, -((y)*mm), 'CÓDIGO')
    p.drawString((x + 141)*mm, -((y)*mm), 'AUDITOR')
    y+=1
    x1 = x
    wCuadro = 7
    hCuadro = 9
    for i in range(0, 2):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    draw_cuadro_guion(p, x1, y, hCuadro)
    x1+=4
    for i in range(0, 2):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    draw_cuadro_guion(p, x1, y, hCuadro)
    x1+=4
    for i in range(0, 4):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7    
    
    x1+=11
    for i in range(0, 2):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    draw_cuadro_puntos(p, x1, y, hCuadro)
    x1+=4
    for i in range(0, 2):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7

    x1+=4
    for i in range(0, 4):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7

    x1+=2
    wCuadro = 48
    hCuadro = 9
    for i in range(0, 1):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    # ---------------------------------------------------------------------
    y+=15
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), 'PARENTESCO')
    p.drawString((x + 67)*mm, -((y)*mm), codecs.decode('NÚMERO DE DNI', 'utf-8'))
    p.drawString((x + 126)*mm, -((y)*mm), codecs.decode('NÚMERO DE TELÉFONO', 'utf-8'))
    # p.drawString((x + 77)*mm, -((y)*mm), 'SUMINISTRO')
    # p.drawString((x + 133)*mm, -((y)*mm), codecs.decode('NÚMERO DE DNI', 'utf-8'))
    y+=1
    x1 = x
    wCuadro = 7
    hCuadro = 9
    for i in range(0, 9):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    x1+=3.5
    for i in range(0, 8):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    x1+=3.5
    for i in range(0, 9):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    
    # ---------------------------------------------------------------------
    y+=15
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), 'NOMBRE DEL ENTREVISTADO')
    y+=1
    x1 = x
    wCuadro = width - 21
    hCuadro = 9
    for i in range(0, 1):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    # ---------------------------------------------------------------------
    y+=15
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), 'SUMINISTRO')
    p.drawString((x + 56)*mm, -((y)*mm), 'TIPO DE VIVIENDA')
    p.drawString((x + 182)*mm, -((y)*mm), 'PISOS')
    y+=1
    x1 = x
    wCuadro = 7
    hCuadro = 9
    for i in range(0, 7):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    x1+=7
    wCuadro = 119
    hCuadro = 9
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    x1+=10
    draw_cuadro_write_small(p, x1, y, 'CASA')
    x1+=24
    draw_cuadro_write_small(p, x1, y, 'QUINTA')
    x1+=26
    draw_cuadro_write_small(p, x1, y, 'EDIFICIO')
    x1+=26
    draw_cuadro_write_small(p, x1, y, 'CONDOMINIO')
    wCuadro = 7
    hCuadro = 9
    x1+=40
    for i in range(0, 1):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    # ---------------------------------------------------------------------
    y+=15
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), 'COLOR DE FACHADA')
    
    y+=1
    x1 = x
    wCuadro = width - 21
    hCuadro = 9
    for i in range(0, 1):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    
    # ---------------------------------------------------------------------
    y+=15
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), 'DATOS DE PUERTA EXTERIOR')
    p.drawString((x + 64)*mm, -((y)*mm), 'VENTANA(EN FACHADA)')
    p.drawString((x + 99)*mm, -((y)*mm), 'DATOS ADICIONALES: LA VIVIENDA CUENTA CON...')
    p.setFont('xcalibriBd', 7)
    p.drawString((x + 172)*mm, -((y - 2)*mm), 'SE PUEDE DEJAR')
    p.drawString((x + 172)*mm, -((y)*mm), 'BP O BZ')
    p.setFont('xcalibriBd', 8.5)
    y+=1
    x1 = x
    wCuadro = 56
    hCuadro = 9
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    x1+=2
    draw_cuadro_write_small(p, x1, y, 'VIDRIO')
    x1+=17
    draw_cuadro_write_small(p, x1, y, 'MADERA')
    x1+=19
    draw_cuadro_write_small(p, x1, y, 'METAL')
    x1+=26
    wCuadro = 28
    hCuadro = 9
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    x1+=5
    draw_cuadro_write_small(p, x1, y, codecs.decode('SÍ', 'utf-8'))
    x1+=10
    draw_cuadro_write_small(p, x1, y, codecs.decode('NO', 'utf-8'))
    x1+=20
    wCuadro = 70
    hCuadro = 9
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    x1+=2
    draw_cuadro_write_small(p, x1, y, codecs.decode('BUZÓN', 'utf-8'))
    x1+=17
    draw_cuadro_write_small(p, x1, y, 'TIMBRE')
    x1+=17
    draw_cuadro_write_small(p, x1, y, 'INTERCOMUNICADOR')
    x1+=37
    wCuadro = 17
    hCuadro = 9
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    x1+=4
    draw_cuadro_write_small(p, x1, y, 'SÍ')
    # ---------------------------------------------------------------------
    y+=15
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), codecs.decode('DIRECCIÓN CORRECTA', 'utf-8'))
    p.drawString((x + 84)*mm, -((y)*mm), 'OBSERVACIONES')
    y+=1
    x1 = x
    wCuadro = 77
    hCuadro = 9
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    x1+=2
    draw_cuadro_write_small(p, x1, y, 'OK')
    x1+=11.5
    draw_cuadro_write_small(p, x1, y, 'DI')
    x1+=11.5
    draw_cuadro_write_small(p, x1, y, 'DD')
    x1+=11.5
    draw_cuadro_write_small(p, x1, y, 'DN')
    x1+=11.5
    draw_cuadro_write_small(p, x1, y, 'FZ')
    x1+=11.5
    draw_cuadro_write_small(p, x1, y, 'OTROS')
    wCuadro = 7
    hCuadro = 9
    x1+=24
    for i in range(0, 15):
        draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
        x1+=7
    # ---------------------------------------------------------------------
    y+=15
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), 'RESULTADO DE LA AUDITORIA')
    p.setFont('xcalibriBd', 7.5)
    p.drawString((x + 154)*mm, -((y)*mm), 'DATOS DEL CARGO COINCIDEN')
    p.setFont('xcalibriBd', 8.5)
    y+=1

    ciclo = rs['ciclo']
    meses = meses_reclamos(ciclo)

    y1=y
    x1 = x
    wCuadro = 150.5
    hCuadro = 23
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    x1+=2
    draw_cuadro_write_small(p, (x1 + 2), y, 'NO LLEGÓ EECC', False)
    draw_cuadro_write_small(p, (x1 + 40), y, 'RECLAMÓ POR LA FACTURACIÓN', False)
    draw_cuadro_write_small(p, (x1 + 97), y, 'NO BRINDÓ INFORMACIÓN', False)
    y+=7
    x1 = x
    x1+=2
    draw_cuadro_write_small(p, (x1 + 2), y, 'LLEGÓ VENCIDO', False)
    draw_cuadro_write_small(p, (x1 + 40), y, 'LLEGÓ AL VECINO', False)
    draw_cuadro_write_small(p, (x1 + 97), y, 'NO REALIZÓ NINGÚN RECLAMO', False)
    y+=7
    x1 = x
    x1+=2
    draw_cuadro_write_small(p, (x1 + 2), y, 'SI LLEGÓ', False)
    draw_cuadro_write_small(p, (x1 + 40), y, 'DESCONOCE MOTIVO DEL RECLAMO', False)
    draw_cuadro_write_small(p, (x1 + 97), y, 'LLEGÓ ABIERTO', False)

    # ---------------------------------------------------------------------
    x1 = 163.5
    wCuadro = 35
    hCuadro = 15
    draw_cuadro_write(p, x1, y1, wCuadro, hCuadro)
    p.setFont('xcalibriBd', 8.5)
    y1+=5
    p.drawString((x1 + 8)*mm, -((y1)*mm), nombre_mes(ciclo))
    y1-=1
    draw_cuadro_write_small(p, x1 + 8, y1, codecs.decode('SÍ', 'utf-8'), False)
    draw_cuadro_write_small(p, x1 + 19, y1, codecs.decode('NO', 'utf-8'), False)
    # ---------------------------------------------------------------------
    y1+=14
    x1 = 163.5
    
    xshipper = codecs.decode(valida_none(rs['shipper']), 'latin-1').strip().split('-')

    if int(xshipper[0]) == 348:
        wCuadro = 35
        hCuadro = 27
        draw_cuadro_write(p, x1, y1, wCuadro, hCuadro)
        y1+=23.5
        p.setDash(2,1)
        p.setLineWidth(mm*0.075)
        p.setStrokeColor(gray)
        p.line((x1 + 1)*mm, -((y1 - 3)*mm), (x1 + 34)*mm, -((y1 - 3)*mm))
        p.setStrokeColor(black)
        p.setLineWidth(mm*0.35)
        p.setDash(1,0)
        p.setFont('xcalibriBd', 8.5)
        p.setFillColor(gray)
        p.drawCentredString((x + 171)*mm, -((y1)*mm), 'FIRMA DEL')
        y1+=3
        p.drawCentredString((x + 171)*mm, -((y1)*mm), 'ENTREVISTADO')
        p.setFillColor(black)

        y1+=3.5
        p.setFont('xcalibri', 8)
        p.drawString((x + 154)*mm, -((y1)*mm), codecs.decode('Recibí conforme una copia', 'utf-8'))
        y1+=3
        p.setFont('xcalibri', 8)
        p.drawString((x + 154)*mm, -((y1)*mm), codecs.decode('del EECC.', 'utf-8'))
    else:
        wCuadro = 35
        hCuadro = 33
        draw_cuadro_write(p, x1, y1, wCuadro, hCuadro)
        y1+=29

        p.setDash(2,1)
        p.setLineWidth(mm*0.075)
        p.setStrokeColor(gray)
        p.line((x1 + 1)*mm, -((y1 - 3)*mm), (x1 + 34)*mm, -((y1 - 3)*mm))
        p.setStrokeColor(black)
        p.setLineWidth(mm*0.35)
        p.setDash(1,0)

        p.setFont('xcalibriBd', 8.5)
        p.setFillColor(gray)
        p.drawCentredString((x + 171)*mm, -((y1)*mm), 'FIRMA DEL')
        y1+=3
        p.drawCentredString((x + 171)*mm, -((y1)*mm), 'ENTREVISTADO')
        p.setFillColor(black)
    
    # ---------------------------------------------------------------------
    y+=14
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x)*mm, -((y)*mm), 'RECEPCIÓN')
    y+=1
    x1 = x
    wCuadro = 150.5
    hCuadro = 22
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    x1+=2
    draw_cuadro_write_small(p, x1, y, 'SOLO POR MEDIO DE SELLO', False)
    x1+=55
    draw_cuadro_write_small(p, x1, y, 'NO HAY PORTERO', False)
    x1+=38
    draw_cuadro_write_small(p, x1, y, 'NO HAY ACCESO AL INTERIOR', False)
    y+=6
    x1 = x
    x1+=2
    draw_cuadro_write_small(p, x1, y, 'PORTERO NO RECIBIÓ DOCUMENTOS', False)
    x1+=55
    draw_cuadro_write_small(p, x1, y, 'PREDIO CON INQUILINOS', False)
    y+=6
    x1 = x
    x1+=2
    draw_cuadro_write_small(p, x1, y, 'CON SELLO Y/O DATOS', False)
    # ---------------------------------------------------------------------
    y+=13
    x1 = x
    wCuadro = 188.5
    hCuadro = 24
    draw_cuadro_write(p, x1, y, wCuadro, hCuadro)
    
    y+=3
    p.setFont('xcalibriBd', 8.5)
    p.drawString((x + 1)*mm, -((y)*mm), 'OBSERVACIONES:')
    draw_imge(p,codecs.decode(valida_none(str(rs['rec_num']))))
    
def draw_imge(p,rec_numero):
    global y
    global x
    global columnax
    global columnay

    x = 0
    y = 0
    columnax = 0
    columnay = 0

    os.environ['INFORMIXSERVER'] = 'ol_urbano'
    conn = informixdb.connect('scm30@ol_urbano', user='informix', password='mh_1C$_2sX')
    cursor = conn.cursor(rowformat = informixdb.ROW_AS_DICT)
    #query_string = "call scm_reclamo_impresion_img(6406);"
    query_string = "call scm_reclamo_impresion_img("+rec_numero+");"
    cursor.execute(query_string)
    imagenes = cursor.fetchall()
    
    vi = 0
    index = 0
    for value in imagenes:
        vi += 1
        index += 1
        if index == 1:
            p.showPage()
            p.translate(0, 297*mm)

        draw_imgtif(p, index, value)
        index = 0 if (index % etiquetas == 0) else index


    cursor.close()

def draw_imgtif(p,index,value):
    global y
    global x
    global columnax
    global columnay

    if ((index-1) % NX == 0 ):
        y = ealto * columnay +76
        x = 5
        columnax = 0 
        columnay += 1
    else:
        columnax += 1
        x = eancho * columnax
    if (columnax == NX):
        columnax = 0
        
    if (columnay == NY):
        columnay = 0    


    p.drawString((x)*mm, -((y-66)*mm), codecs.decode(valida_none('Guia: '+str(value['gui_numero'])), 'latin-1').strip()[:50])    
    #p.drawImage(codecs.decode(valida_none(value['img_path']), 'latin-1'), (x)*mm, -((y-5)*mm), 200*mm, 60*mm, mask=None)
    #p.drawImage('/img2/LIMM2989/DIG00000002/ACUSE05.TIF', (x)*mm, -((y-5)*mm), 200*mm, 60*mm, mask=True)

    ruta = codecs.decode(valida_none(value['img_path']), 'latin-1')
    #print ruta
    os.system('convert '+ruta+' /sistemas/weburbano/public_html/tmp/reclamos/'+str(value['gui_numero'])+'.jpg')
    p.drawImage('/sistemas/weburbano/public_html/tmp/reclamos/'+str(value['gui_numero'])+'.jpg', (x)*mm, -((y-5)*mm), 200*mm, 60*mm, mask=None)
    


def draw_line_cuadro(p, x, y, w):
    p.setLineWidth(mm*0.075)
    p.setStrokeColor(gray)
    p.line((x + 2)*mm, -((y + 8)*mm), (x + w)*mm, -((y + 8)*mm))
    p.setStrokeColor(black)
    p.setLineWidth(mm*0.35)

def draw_cuadro_write(p, x, y, wCuadro, hCuadro):
    p.setLineWidth(mm*0.075)
    p.setStrokeColor(gray)
    p.rect(x*mm, -((y + hCuadro)*mm), wCuadro*mm, hCuadro*mm, 1, 0)
    p.setStrokeColor(black)
    p.setLineWidth(mm*0.35)

def draw_cuadro_write_small(p, x, y, text, bold = False):
    w = 4
    h = 4
    if bold:
        fuente = 'xcalibriBd'
    else:
        fuente = 'xcalibri'
    p.setLineWidth(mm*0.075)
    p.setStrokeColor(gray)
    p.rect(x*mm, -((y + h + 2.5)*mm), w*mm, h*mm, 1, 0)
    p.setFont(fuente, 8.5)
    p.drawString((x + w + 0.5)*mm, -((y + 5.5)*mm), text)
    p.setStrokeColor(black)
    p.setLineWidth(mm*0.35)

def draw_cuadro_puntos(p, x, y, hCuadro):
    x+=1
    y+= (hCuadro / 2) + (hCuadro / 4)
    p.setFont('xcalibriBd', 16)
    p.drawString((x)*mm, -((y)*mm), ':')

def draw_cuadro_guion(p, x, y, hCuadro):
    x+=1
    y+= hCuadro / 2
    p.setLineWidth(mm*0.5)
    p.line((x)*mm, -((y)*mm), (x + 2)*mm, -((y)*mm))
    p.setLineWidth(mm*0.35)

def draw_header(p, rs):
    y = 10 + 6
    p.drawImage(path + 'images/logo_header_urbano.jpg', (10)*mm, -(y*mm), 29*mm, 9*mm, mask=None)
    y+= 5
    barcode = createBarcodeDrawing('Code128', value = rs['reclamo'], barWidth = 0.2 * mm, width = 100 * mm, barHeight = 11 * mm, fontSize = 8, humanReadable = False, fontName = 'xcalibri', borderWidth = 1)

    barcode.drawOn(p, 105*mm, -((y - 5)*mm))

    p.setFont('xcalibri', 12)
    p.drawCentredString(155*mm, -((y - 1.5)*mm), codecs.decode(valida_none(str(rs['reclamo'])),'latin-1'))

def draw_square(p, x, y, w, h, **kwargs):
    p.setLineWidth(mm*0.075)
    p.setStrokeColor(gray)
    fill = getValue('fill', 0, **kwargs)
    stroke = getValue('stroke', 1, **kwargs)
    p.rect((x)*mm, -((y + h)*mm), w*mm, h*mm, stroke = stroke, fill = fill )
    p.setStrokeColor(black)
    p.setLineWidth(mm*0.35)

def getValue(k, v, **kwargs):
    devuelto = 0
    if kwargs != None:
        try:
            devuelto = kwargs[k]
        except KeyError:
            devuelto = v
    return devuelto

def draw_corner(p):
    for i in range(0, 4):
        get_corner(p, i)

def get_corner(p, index):
    p.setLineWidth(mm*0.35)
    tcuadro = 6
    if index == 0:
        x = 10
        y = tcuadro
        p.rect(x*mm, -((y + 10)*mm), tcuadro*mm, tcuadro*mm, 1, 1)
    elif index == 1:
        x = (width - 10) - tcuadro
        y = tcuadro
        p.rect(x*mm, -((y + 10)*mm), tcuadro*mm, tcuadro*mm, 1, 1)
    elif index == 2:
        x = 10
        y = height - 10
        p.rect(x*mm, -((y)*mm), tcuadro*mm, tcuadro*mm, 1, 1)
    elif index == 3:
        x = (width - 10) - tcuadro
        y = height - 10
        p.rect(x*mm, -((y)*mm), tcuadro*mm, tcuadro*mm, 1, 1)

    x = (width / 2) - 1

    p.setLineWidth(mm*0.35)

def valida_none(cadena):
    cadena = '' if (cadena == None) else cadena
    return cadena

def get_name_file(path):
    path = path.split('/')
    return path[len(path) - 1]

def nombre_mes(ciclo):
    mes = int(ciclo.strftime('%m'))
    meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE']
    return meses[mes - 1]

def meses_reclamos(ciclo):
    meses = [ciclo]
    for i in range(1, 3):
        meses.append(ciclo - relativedelta(months=i))
    for i in range(1, 3):
        meses.append(ciclo + relativedelta(months=i))
    return sorted(meses)

draw_init()

'''
Closing connections
'''
#cursor.close()
