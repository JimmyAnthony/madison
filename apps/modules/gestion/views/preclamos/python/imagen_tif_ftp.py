#!/usr/bin/env python
# -*- encoding: utf-8 -*-

'''
Developed by @remicioluis
'''
import sys, os, glob, base64, pysftp, paramiko, ConfigParser

def ConfigSectionMap(section):
    dict1 = {}
    options = Config.options(section)
    for option in options:
        try:
            dict1[option] = Config.get(section, option)
            if dict1[option] == -1:
                DebugPrint("skip: %s" % option)
        except:
            print("exception on %s!" % option)
            dict1[option] = None
    return dict1

BASE_DIR = os.path.dirname(os.path.dirname(__file__)) + '/python/'

path_ini = BASE_DIR + 'config.ini'
server_config = 'sftp_imagen'

Config = ConfigParser.ConfigParser()
Config.read(path_ini)

server_host = str(ConfigSectionMap(server_config)['server_host'])
server_user = str(ConfigSectionMap(server_config)['server_user'])
server_pass = str(ConfigSectionMap(server_config)['server_pass'])
server_port = int(ConfigSectionMap(server_config)['server_port'])
server_local_path = str(ConfigSectionMap(server_config)['server_local_path'])

param = base64.b64decode(sys.argv[1])
params = param.split('&')

print params

id_reclamo = params[2]

path_local_base = '/sistemas/' + server_local_path + '/public_html/tmp/reclamos/'

path_remote_base = '/sistemas/tmp/reclamos/'

imagen01 = params[0]
imagen02 = params[1]

img1 = str(id_reclamo) + '-cargo.jpg'
img2 = str(id_reclamo) + '-imagen.jpg'

try:
    srv = pysftp.Connection(host=server_host, username=server_user, password=server_pass, port=server_port)

    if srv.exists(path_remote_base):
        '''
        I'll to remote directory
        '''
        srv.chdir(path_remote_base)
        if srv.exists(imagen01):
            srv.execute('convert ' + imagen01 + ' ' + path_remote_base + img1)
            if srv.exists(img1):
                srv.get(img1, path_local_base + img1)
                srv.remove(img1)
        if srv.exists(imagen02):
            srv.execute('convert ' + imagen02 + ' ' + path_remote_base + img2)
            if srv.exists(img2):
                srv.get(img2, path_local_base + img2)
                srv.remove(img2)
    else:
        print 'Not exists remote directory.'
    ######################
    srv.close()
except pysftp.ConnectionException:
    print 'Error of connection.'
except paramiko.ssh_exception.AuthenticationException:
    print 'Error of authentication.'
except paramiko.ssh_exception.SSHException:
    print 'Error of ssh.'

os.chdir(path_local_base)
files = [0, 0]
if os.path.isfile(img1):
    files[0] = 1
if os.path.isfile(img2):
    files[1] = 1
for f in files:
    print f
