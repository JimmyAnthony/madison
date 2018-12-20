#!/usr/bin/env python
# -*- encoding: utf-8 -*-

import sys, os, ftputil, base64

param = base64.b64decode(sys.argv[1])
params = param.split('&')

id_bd = params[0]
server_ftp = params[1]
user_ftp = params[2]
key_ftp = params[3]
path_ftp = params[4]
file_name = params[5]

source_file = '/sistemas/weburbano/public_html/uploads/'
target_file = path_ftp
'''
Var for description of error
----------------------------
0 => No connection
1 => No errors
2 => No exists directory
4 => Upload error
'''
error = 0

ftp_host = ftputil.FTPHost(server_ftp, user_ftp, key_ftp)

with ftputil.FTPHost(server_ftp, user_ftp, key_ftp) as ftp_host:
    error = 1
    if ftp_host.path.isdir(target_file):
        upload = ftp_host.upload_if_newer(source_file + file_name, target_file + file_name)
        if upload:
            error = 1
        else:
            error = 4
    else:
        error = 2

os.chdir(source_file)
os.remove(file_name)

print error