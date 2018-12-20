# -*- encoding: utf-8 -*-

# /---------------------------\
# | Developed by Luis Remicio |
# \ ------------------------- /

import sys, os, ftputil, base64, informixdb, codecs
from datetime import datetime, date, time
from xlrd import open_workbook, cellname, xldate_as_tuple
import openpyxl

param = base64.b64decode(sys.argv[1])
params = param.split('&')

id_solicitud = params[0]

# id_solicitud = 23

class Estructura:

    def __init__(self, orden, tamanio, nombre, tipo_dato):
        self.orden = orden
        self.tamanio = tamanio
        self.nombre =nombre
        self.tipo_dato = tipo_dato

    def __repr__(self):
        return repr((self.orden, self.tamanio, self.nombre, self.tipo_dato))


class FTP_procesar:

    id_solicitud = 0
    rs = []
    cursor = ''

    pathTmp = '/sistemas/weburbano/public_html/tmp/gestor_archivos/'

    estado = 0

    ftp_host_origen = ''
    ftp_user_origen = ''
    ftp_pass_origen = ''
    ftp_path_origen = ''
    ftp_file = ''

    ftp_host_destino = ''
    ftp_user_destino = ''
    ftp_pass_destino = ''
    ftp_path_destino = ''

    workbook = ''
    worksheet = ''

    extension = ''

    def __init__(self, id_solicitud):
        self.id_solicitud = id_solicitud

        '''
        Parameters of connection - Informix
        '''
        os.environ['INFORMIXSERVER'] = 'ol_urbano'
        conn = informixdb.connect('scm30@ol_urbano', user='informix', password='mh_1C$_2sX')
        self.cursor = conn.cursor(rowformat = informixdb.ROW_AS_DICT)
        self.rs = self.getDataSolicitud()
        self.rs = self.rs[0]

        self.estado = int(self.rs['estado'])

        self.ftp_host_origen = self.rs['ftp_server']
        self.ftp_user_origen = self.rs['ftp_user']
        self.ftp_pass_origen = self.rs['ftp_clave']
        self.ftp_path_origen = self.rs['ftp_path']
        self.ftp_file = self.rs['ftp_file']

        self.ftp_host_destino = self.rs['ftp_server_d']
        self.ftp_user_destino = self.rs['ftp_user_d']
        self.ftp_pass_destino = self.rs['ftp_clave_d']
        self.ftp_path_destino = self.rs['ftp_path_txt']

        self.getFileTmp()
        self.validar_file()

    def getDataSolicitud(self):
        query_string = "call scm_gestor_ftp_chk_file("+str(self.id_solicitud)+");"
        self.cursor.execute(query_string)
        rs = self.cursor.fetchall()
        return rs

    def getEstructuraFile(self):
        query_string = "call scm_gestor_ftp_chk_file_struc("+str(self.id_solicitud)+");"
        self.cursor.execute(query_string)
        rs = self.cursor.fetchall()
        return rs

    def getFileTmp(self):
        with ftputil.FTPHost(self.ftp_host_origen, self.ftp_user_origen, self.ftp_pass_origen) as ftp_host:
            download = ftp_host.download_if_newer(self.ftp_path_origen + self.ftp_file, self.pathTmp + self.ftp_file)

    def setFileTmp(self):
        fileNameTxt = self.ftp_file
        fileNameTxt = os.path.splitext(fileNameTxt)[0] + '.txt'
        with ftputil.FTPHost(self.ftp_host_destino, self.ftp_user_destino, self.ftp_pass_destino) as ftp_host:
            upload = ftp_host.upload_if_newer(self.pathTmp + fileNameTxt, self.ftp_path_destino + fileNameTxt)

    def convert_date(self, fecha):
        if self.extension == 'xls':
            try:
                date_value = xldate_as_tuple(fecha,self.workbook.datemode)
                vl_fecha = date(*date_value[:3]).strftime('%d/%m/%Y')
            except ValueError:
                vl_fecha = datetime.strptime(fecha, '%d/%m/%Y').strftime('%d/%m/%Y')
        else:
            try:
                vl_fecha = fecha.strftime('%d/%m/%Y')
            except AttributeError:
                vl_fecha = datetime.strptime(fecha, '%d/%m/%Y').strftime('%d/%m/%Y')
        return vl_fecha

    def valida_none(self, cadena, tipo_dato):
        cadena = '' if (cadena == None) else cadena
        #if ( isinstance(cadena, int) | isinstance(cadena, float) ):
        if (tipo_dato == 'D'):
            #cadena = cadena.strftime('%d/%m/%Y')
            cadena = self.convert_date(cadena)
            cadena = str(cadena)
        else:
            if (isinstance(cadena, int)):
                cadena = str(cadena)#2
            else:
                try:
                    cadena = str(cadena.encode('latin-1'))#ok 11
                except AttributeError:
                    cadena = self.convert_date(cadena)
                    #cadena = cadena.strftime('%d/%m/%Y')
                    cadena = str(cadena)

            #list_ascii=[chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8), chr(9), chr(10), chr(11), chr(12), chr(13), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19), chr(20), chr(21), chr(22), chr(23), chr(24), chr(25), chr(26), chr(27), chr(28), chr(29), chr(30), chr(31), chr(127),chr(160),chr(194),chr(195),chr(177),chr(145),chr(191)];#,,chr(241)
            list_ascii=[chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8), chr(9), chr(10), chr(11), chr(12), chr(13), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19), chr(20), chr(21), chr(22), chr(23), chr(24), chr(25), chr(26), chr(27), chr(28), chr(29), chr(30), chr(31), chr(127),chr(177),chr(145),chr(191)];#,,chr(241)
            for ascii_ in list_ascii:
                cadena = cadena.replace(ascii_, '')

            cadena = cadena.strip()
            cadena = cadena.rstrip()
            cadena = cadena.rstrip('\r\n')
            cadena = cadena.replace('\n', '')

            
            cadena = codecs.decode(cadena, 'latin-1')

            # Encoding string for replacing strangers caracter.
            #cadena = codecs.encode(cadena, 'utf-8')
            #cadena = cadena.replace("â€“", "")
            # Decoding string for problems avoid of write.
            #cadena = codecs.decode(cadena, 'utf-8')
            # ##################################################
            cadena.replace("\\", "")
        return cadena

    def validar_file(self):
        if self.estado == 1:
            self.setFileTmp()
        elif self.estado == 2:
            fileName = self.pathTmp + self.ftp_file
            self.extension = os.path.splitext(fileName)[1][1:]

            fileNameTxt = self.ftp_file
            fileNameTxt = self.pathTmp + os.path.splitext(fileNameTxt)[0] + '.txt'

            rs = self.getEstructuraFile()

            aEstructura = []
            for col in rs:
                aEstructura.append(Estructura(col['orden'], col['tamanio'], col['nombre'], col['tipo_dato']))

            aEstructura = sorted(aEstructura, key=lambda estructura: estructura.orden)
            
            if self.extension == 'xls':
                '''
                Other libraries for files .xls
                '''
                self.workbook = open_workbook(fileName)
                self.worksheet = self.workbook.sheet_by_index(0)

                txt = codecs.open(fileNameTxt, "w", encoding='latin-1')
                for row_index in range(self.worksheet.nrows):
                    if row_index != 0:
                        cadena = ''
                        for col in aEstructura:
                            cadena+= self.valida_none(self.worksheet.cell(row_index,int(col.orden - 1)).value, col.tipo_dato).strip()[:col.tamanio].ljust(col.tamanio)
                        txt.write(cadena + "\n")
                txt.close()
            else:
                self.workbook = openpyxl.load_workbook(filename = fileName)
                self.worksheet = self.workbook.get_active_sheet()


                i = 0
                txt = codecs.open(fileNameTxt, "w",encoding='latin-1')
                for cell in self.worksheet.rows:
                    if i != 0:
                        cadena = ''
                        if self.valida_none(cell[0].value, 'C').strip() != '':
                                
                            for col in aEstructura:
                                #self.valida_none(cell[0].value, 'C').strip()
                                #cadena+= self.valida_none(cell[11].value, 'C').strip()[:col.tamanio].ljust(col.tamanio)
                                cadena+= self.valida_none(cell[int(col.orden - 1)].value, col.tipo_dato).strip()[:col.tamanio].ljust(col.tamanio)
                            txt.write(cadena + "\n")
                    
                    i+=1
                txt.close()

            self.setFileTmp()

            #os.remove(fileName)
            #os.remove(fileNameTxt)
obj = FTP_procesar(id_solicitud)
