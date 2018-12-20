#!/usr/bin/env python
# -*- encoding: utf-8 -*-

'''

Utilidad de línea de comandos
'''

import sys, os, glob
import codecs, string

BASE_DIR = os.getcwd()

options = sys.argv
options.pop(0)

'''
Plantilla para capa Controller
'''
def get_template_controller(name):
    fo = codecs.open(BASE_DIR + '/console/templates/controller.php', "r", encoding='utf-8')
    a = fo.read()
    fo.close()
    return a.replace('[template]', name)

'''
Plantilla para capa Models
'''
def get_template_model(name):
    fo = codecs.open(BASE_DIR + '/console/templates/models.php', "r", encoding='utf-8')
    a = fo.read()
    fo.close()
    return a.replace('[template]', name)

'''
Plantilla para las vistas
'''
def get_template_view(name):
    fo = codecs.open(BASE_DIR + '/console/templates/extjs_tab.html', "r", encoding='utf-8')
    a = fo.read()
    fo.close()
    a = a.replace('{name_template}', name)
    a = a.replace('{contenedor}', 'tabContent')
    return a

'''
Funcion para empezar la creación de módulos.
'''
def startapp():
    index = options.index('startapp')
    try:
        module = options[index + 1]
        directorio = BASE_DIR + '/apps/modules/' + module
        if not os.path.isdir(directorio):
            os.mkdir(directorio)
            if os.path.isdir(directorio):
                directorio = BASE_DIR + '/apps/modules/' + module + '/controllers'
                os.mkdir(directorio)
                directorio = BASE_DIR + '/apps/modules/' + module + '/models'
                os.mkdir(directorio)
                directorio = BASE_DIR + '/apps/modules/' + module + '/views'
                os.mkdir(directorio)
    except IndexError, e:
        print 'Debe de ingresar el nombre del modulo.'

'''
Se encarga de crear las plantillas de muestra
'''
def make_controller():
    index = options.index('-c')
    name = options[index + 1]
    directorio = BASE_DIR + '/apps/modules/' + options[index + 2]
    if os.path.isdir(directorio):
        os.chdir(directorio + '/controllers')
        fo = codecs.open( name + "Controller.php", "w", encoding='utf-8')
        fo.write(get_template_controller(name))
        fo.close()
        os.chdir(directorio + '/models')
        fo = codecs.open( name + "Models.php", "w", encoding='utf-8')
        fo.write(get_template_model(name))
        fo.close()
        directorio = BASE_DIR + '/apps/modules/' + options[index + 2] + '/views/' + name
        if not os.path.isdir(directorio):
            os.mkdir(directorio)
        os.chdir(directorio)
        fo = codecs.open( "form_index.php", "w", encoding='utf-8')
        fo.write(get_template_view(name))
        fo.close()
    else:
        startapp()
        make_controller()

'''
Crea plantillas de vistas
'''
def make_view():
    index = options.index('-cv')
    name = options[index + 1]
    name_obj = options[index + 4]
    directorio = BASE_DIR + '/apps/modules/' + options[index + 3]
    if os.path.isdir(directorio):
        os.chdir(directorio + '/views/' + options[index + 2])
        fo = codecs.open( name, "w", encoding='utf-8')
        fo.write(get_template_view(name_obj))
        fo.close()

'''
Opciones de líneas de comando
-----------------------------
Para iniciar proyecto:
---------------------
=> ./geekode.py startapp login -c index login

---------------------
'''
op = ['startapp', '-c', '-cv']
for option in options:
    if op.count(option) > 0:
        if option == 'startapp':
            startapp()
        elif option == '-c':
            make_controller()
        elif option == '-cv':
            make_view()