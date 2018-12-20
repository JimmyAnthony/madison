# -*- encoding: utf-8 -*-

# /---------------------------\
# | Developed by Luis Remicio |
# \ ------------------------- /

import sys, os, base64, informixdb, codecs

param = base64.b64decode(sys.argv[1])
params = param.split('&')

'''
Parámetros de conexión
'''
os.environ['INFORMIXSERVER'] = 'ol_urbano'
conn = informixdb.connect('scm30@ol_urbano', user='informix', password='mh_1C$_2sX')
conn.autocommit = True
try:
    cursor = conn.cursor(rowformat = informixdb.ROW_AS_DICT)
    try:
        cursor.callproc("scm_reclamo_impresion_texto", (str(params[1]), str(params[0]), str(params[6]), str(params[2]), str(params[3]), str(params[4])))
        rs = cursor.fetchall()
    finally:
        cursor.close()
finally:
    conn.close()

def valida_none(cadena):
    cadena = '' if (cadena == None) else cadena
    return cadena

path = '/sistemas/weburbano/public_html/tmp/reclamos/'

txt = codecs.open(path +"rpt_auditoria-" + str(params[5])+".txt", "w", encoding='utf-8')
for row in rs:
    cadena = ''
    cadena = valida_none(str(row['ciclo'].strftime('%d/%m/%Y'))) + ',' + str(codecs.decode(valida_none(row['codigo']), 'latin-1'))
    txt.write(cadena + "\r\n")
txt.close()