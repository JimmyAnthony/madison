# -*- encoding: utf-8 -*-

import sys, os, json, codecs, informixdb

id_via = 47651
ciu_id = 1417
usr_id = 1

os.environ['INFORMIXSERVER'] = 'ol_urbano'
conn = informixdb.connect('scm30@ol_urbano', user='informix', password='mh_1C$_2sX')
conn.autocommit = True
try:
    cursor = conn.cursor(rowformat = informixdb.ROW_AS_DICT)
    try:
        cursor.callproc("gis_busca_via_segmentos", (id_via, ciu_id, usr_id))
        rs = cursor.fetchall()
    finally:
        cursor.close()
finally:
    conn.close()
    
print rs