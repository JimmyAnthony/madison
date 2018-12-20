#!/usr/bin/env python
# -*- encoding: utf-8 -*-

import sys, os, glob
import informixdb, sqlite3
from datetime import datetime, date, time
from xlrd import open_workbook, cellname, xldate_as_tuple
import openpyxl

'''
Developed by @remicioluis
'''

class FileMasivo:

    PATH_UPLOADS = '/sistemas/weburbano/public_html/uploads/'
    PATH_DB = '/sistemas/weburbano/db_sqlite/carga_masiva_telefonica'
    PATH_TMP = '/sistemas/weburbano/public_html/tmp/reclamos/'

    FILE = ''
    EXTENSIONS = ['xls', 'xlsx']

    consqlite = ''
    cursorsqlite = ''

    def __init__(self, file_excel):
        self.FILE = file_excel
        if self.isValidExtension():
            self.consqlite = sqlite3.connect(self.PATH_DB)
            self.cursorsqlite = self.consqlite.cursor()

            self.process()
        else:
            print 'Extension no valida!'

    def getExtension(self):
        return os.path.splitext(self.FILE)[1][1:]

    def isValidExtension(self):
        extension = self.getExtension()
        return True if extension in self.EXTENSIONS else False

    def create_tmp(self, table):
        query_sqlite = 'CREATE TABLE ' + str(table) + ' ('
        query_sqlite+= ' id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,'
        query_sqlite+= ' shipper TEXT,'
        query_sqlite+= ' producto TEXT,'
        query_sqlite+= ' ciclo TEXT,'
        query_sqlite+= ' cli_codigo TEXT,'
        query_sqlite+= ' mot_reclamo TEXT,'
        query_sqlite+= ' det_reclamo TEXT,'
        query_sqlite+= ' posicion INTEGER,'
        query_sqlite+= ' error_sql INTEGER,'
        query_sqlite+= ' error_info TEXT,'
        query_sqlite+= ' guia INTEGER'
        query_sqlite+= ' )'

        self.cursorsqlite.execute('drop table if exists ' + str(table))

        self.cursorsqlite.execute(query_sqlite)

    def process(self):
        extension = self.getExtension()
        if extension == 'xls':
            workbook = open_workbook(self.PATH_UPLOADS + self.FILE)
            worksheet = workbook.sheet_by_index(0)

            for row_index in range(worksheet.nrows):
                if row_index != 0:
                    print worksheet.cell(row_index,0).value


FileMasivo('test.xls')