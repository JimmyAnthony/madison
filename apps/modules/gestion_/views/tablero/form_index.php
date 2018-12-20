<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('tablero-tab')){
    var tablero = {
        id: 'tablero',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/tablero/',
        category: null,
        dataset: null,
        op: 1,
        p:{
            vp_anio: null,
            vp_mes: null,
            vp_nro_meses: null,
            vp_id_semana: null,
            vp_ship_abc: null,
            vp_id_shipp: null,
            vp_id_orden: null,
            vp_tipo_filtro:1
        },
        tipo_grafico: 'B',
        init:function(){
            

            var panel = Ext.create('Ext.form.Panel',{
                id: tablero.id+'-form',
                border: false,
                layout: 'fit',
                defaults:{
                    border: false
                },
                tbar:[
                    'Año:',
                    {
                        xtype: 'combo',
                        id: tablero.id+'-anio',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'anio', type: 'int'}
                            ]
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'anio',
                        displayField: 'anio',
                        emptyText: '[ Seleccione ]',
                        width: 60,
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().loadData(<?php echo $this->getAnios($p);?>);
                                obj.setValue(<?php echo date('Y');?>);
                                tablero.getComboSemanas(<?php echo date('Y');?>);
                            }
                        }
                    },
                    'Meses:',
                    {
                        xtype: 'combo',
                        id: tablero.id+'-meses',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'descripcion', type: 'string'},
                                {name: 'id_elemento', type: 'int'},
                                {name: 'des_corto', type: 'string'},
                            ],
                            proxy:{
                                type: 'ajax',
                                url: tablero.url + 'get_scm_tabla_detalle/',
                                reader:{
                                    type: 'json',
                                    root: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id_elemento',
                        displayField: 'descripcion',
                        emptyText: '[ Seleccione ]',
                        width: 70,
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        vp_tab_id: 'MES',
                                        vp_shipper: 0
                                    },
                                    callback: function(){
                                        obj.setValue(<?php echo date('n');?>);
                                    }
                                });
                            }
                        }
                    },
                    'Meses a ver:',
                    {
                        xtype: 'numberfield',
                        id: tablero.id+'-meses-ver',
                        hideLabel: true,
                        width: 40,
                        value: 4,
                        minValue: 1,
                        maxValue: 12

                    },
                    {
                        xtype: 'checkbox',
                        id: tablero.id+'-chk-semana',
                        boxLabel: 'Ver semanas',
                        width: 90,
                        listeners:{
                            change: function(obj, newValue, oldValue, eOpts){
                                if (newValue){
                                    Ext.getCmp(tablero.id+'-semanas').show();
                                }else{
                                    Ext.getCmp(tablero.id+'-semanas').hide();
                                }
                            }
                        }
                    },
                    '-',
                    {
                        xtype: 'combo',
                        id: tablero.id+'-semanas',
                        fieldLabel: 'Semanas',
                        labelWidth: 65,
                        labelAlign: 'right',
                        hidden: true,
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'semana', type: 'string'},
                                {name: 'id_semana', type: 'int'},
                                {name: 'semana_actual', type: 'int'},
                                {name: 'inicio', type: 'string'},
                                {name: 'termina', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: tablero.url + 'get_bsc_anio_semanas/',
                                reader:{
                                    type: 'json',
                                    root: 'data'
                                }
                            },
                            listeners:{
                                load: function(obj, records, successful, eOpts){
                                    var reader = obj.getProxy().getReader();
                                    Ext.getCmp(tablero.id+'-semanas').setValue(parseInt(reader.rawData.focusId));
                                }
                            }
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id_semana',
                        displayField: 'semana',
                        emptyText: '[ Seleccione ]',
                        width: 200,
                        listConfig:{
                            minWidth: 200
                        },
                        listeners:{
                            select: function(obj, records, eOpts){
                                // console.log(records);
                            },
                            afterrender: function(obj, e){
                                
                            }
                        }
                    },
                    'Categoría Shipper:',
                    {
                        xtype: 'combo',
                        id: tablero.id+'-cat-shipper',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'descripcion', type: 'string'},
                                {name: 'id_elemento', type: 'int'},
                                {name: 'des_corto', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: tablero.url + 'get_scm_tabla_detalle/',
                                reader:{
                                    type: 'json',
                                    root: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id_elemento',
                        displayField: 'descripcion',
                        emptyText: '[ Seleccione ]',
                        width: 130,
                        listConfig:{
                            minWidth: 200
                        },
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        vp_tab_id: 'ABC',
                                        vp_shipper: 0,
                                        leaf: 1
                                    },
                                    callback: function(){
                                        obj.setValue(0);
                                    }
                                });
                            }
                        }
                    },
                    'Shipper:',
                    {
                        xtype: 'combo',
                        id: tablero.id+'-shipper',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'shi_codigo', type: 'int'},
                                {name: 'shi_nombre', type: 'string'},
                                {name: 'shi_id', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: tablero.url + 'get_usr_sis_shipper/',
                                reader:{
                                    type: 'json',
                                    root: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'shi_codigo',
                        displayField: 'shi_nombre',
                        emptyText: '[ Seleccione ]',
                        width: 140,
                        multiSelect: true,
                        listConfig:{
                            minWidth: 400
                        },
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        vp_linea: 0
                                    },
                                    callback: function(){
                                        // obj.setValue(0);
                                    }
                                });
                            },
                            select:function(obj, records, eOpts){
                                Ext.getCmp(tablero.id+'-servicio').getStore().removeAll();
                                Ext.getCmp(tablero.id+'-servicio').setValue('');
                                var ship = obj.getValue();
                                if(ship.length==1){
                                    Ext.getCmp(tablero.id+'-servicio').getStore().load({
                                            params: {vp_id_linea:3,vp_shi_codigo: ship[0]},
                                            callback:function(){

                                            }
                                    });
                                }
                            }
                        }
                    },
                    '-',
                    'Servicio:',
                    {
                        xtype: 'combo',
                        id: tablero.id+'-servicio',
                        store:Ext.create('Ext.data.Store',{
                            fields: [
                                {name: 'id_orden', type: 'int'},
                                {name: 'pro_id', type: 'int'},
                                {name: 'servicio', type: 'string'}
                            ],
                            autoLoad:false,
                            proxy:{
                                type: 'ajax',
                                url: tablero.url+'get_scm_tipo_servicios/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            },
                            listeners:{
                                load: function(obj, records, successful, opts){
                                    var count = parseInt(obj.getCount());
                                    
                                }
                            }
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id_orden',
                        displayField: 'servicio',
                        emptyText: '[ Seleccione ]',
                        width: 140,
                        multiSelect: true,
                        listConfig:{
                            minWidth: 300
                        },
                        listeners:{
                            afterrender: function(obj, e){
                                /*obj.getStore().load({
                                    params:{
                                        vp_linea: 0
                                    },
                                    callback: function(){
                                        // obj.setValue(0);
                                    }
                                });*/
                            }
                        }
                    },
                    '-',
                    {
                        text: 'Buscar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            click: function(obj, e){
                                tablero.getConsulta();
                            }
                        }
                    }
                ],
                items:[
                    {
                        xtype: 'panel',
                        id: tablero.id + '-grid-tree',
                        layout: 'fit',
                        border: false,
                        autoScroll: true,
                        defaults:{
                            border: false
                        }
                    }
                ],
                listeners:{
                    afterrender: function(obj, e){
                        
                    }
                }
            });

            tab.add({
                id: tablero.id+'-tab',
                border: false,
                autoScroll: true,
                closable: true,
                layout:{
                    type: 'fit'
                },
                items:[
                    panel
                ],
                listeners:{
                    beforerender: function(obj, opts){
                        global.state_item_menu(tablero.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(tablero.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + tablero.id_menu).text,
                            icon: Ext.getCmp('menu-' + tablero.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,tablero.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(tablero.id_menu, false);
                    }
                }
            }).show();
        },
        getComboSemanas: function(anio){
            var sem = Ext.getCmp(tablero.id+'-semanas');
            sem.getStore().load({
                params:{
                    vp_anio: anio
                },
                callback: function(){
                    // console.log(sem.getStore());
                }
            });
        },
        getDescripcionSemana: function(){
            var cmb = Ext.getCmp(tablero.id+'-semanas');
            var store = cmb.getStore();
            var cc = store.getCount();
            var seleccionado = cmb.getValue();
            var index = 0;
            for(var vi = 0; vi < cc; ++vi){
                var rec = store.getAt(vi);
                if (parseInt(rec.get('id_semana')) == seleccionado)
                    break;
                ++index;
            }
            if(index<4){
                var inicio = store.getAt(0).get('inicio');
            }else{
                var inicio = store.getAt(index - 4).get('inicio');
            }
            var fin = store.getAt(index).get('termina');
            var devuelto = '[ Desde: ' + inicio + ' Hasta: ' + fin + ']';
            return devuelto;
        },
        getConsulta: function(){
            var contenedor = Ext.getCmp(tablero.id + '-grid-tree');

            contenedor.removeAll();

            var vp_anio = Ext.getCmp(tablero.id+'-anio').getValue();
            var vp_mes = Ext.getCmp(tablero.id+'-meses').getValue();
            var vp_nro_meses = Ext.getCmp(tablero.id+'-meses-ver').getValue();
            var vp_id_semana = Ext.getCmp(tablero.id+'-chk-semana').getValue() ? Ext.getCmp(tablero.id+'-semanas').getValue() : '0';
            var vp_ship_abc = Ext.getCmp(tablero.id+'-cat-shipper').getValue();
            var vp_id_shipp = Ext.JSON.encode(Ext.getCmp(tablero.id+'-shipper').getValue());
            var vp_id_orden = Ext.JSON.encode(Ext.getCmp(tablero.id+'-servicio').getValue());

            Ext.getCmp(inicio.id+'-tabContent').el.mask('Cargando...', 'x-mask-loading');
            Ext.Ajax.request({
                url: tablero.url + 'getData_Grid_Tree/',
                params:{
                    vp_id_tablero: 1,
                    vp_anio: vp_anio,
                    vp_mes: vp_mes,
                    vp_nro_meses: vp_nro_meses,
                    vp_id_semana: vp_id_semana,
                    vp_ship_abc: vp_ship_abc,
                    vp_id_shipp: vp_id_shipp,
                    vp_id_orden: vp_id_orden,
                    vp_tipo_filtro: tablero.p.vp_tipo_filtro
                },
                success: function(response, options){
                    Ext.getCmp(inicio.id+'-tabContent').el.unmask();
                    var res = Ext.JSON.decode(response.responseText);
                    // console.log(res);

                    tablero.p.vp_anio = vp_anio;
                    tablero.p.vp_mes = vp_mes;
                    tablero.p.vp_nro_meses = vp_nro_meses;
                    tablero.p.vp_id_semana = vp_id_semana;
                    tablero.p.vp_ship_abc = vp_ship_abc;
                    tablero.p.vp_id_shipp = vp_id_shipp;
                    tablero.p.vp_id_orden = vp_id_orden;

                    /**
                     * Setting dinamic tree-grid
                     */
                    var model = [];
                    var columns = [];
                    /*var text_='';
                    switch(tablero.p.vp_tipo_filtro){
                        case 1:
                            text_='Guía';
                        break;
                        case 2:
                            text_='Peso';
                        break;
                        case 3:
                            text_='Venta';
                        break;
                    }
                     - Filtro por '+text_
                    */

                    columns.push({xtype: 'treecolumn', text: 'Indicador', width: 300, sortable: false, dataIndex: 'indicador', locked: true, menuDisabled: true});

                    iconChartHeader = '';
                    iconChartHeader+= '<div class="gk-icon-grid">';
                        iconChartHeader+= '<span class="gk-icon-barra" data-qtip="Click para ver gráfico estadístico." onclick=""></span>';
                    iconChartHeader+= '</div>';
                    columns.push({text: iconChartHeader, style: 'padding-top: 13px;', width: 30, sortable: false, dataIndex: '', align: 'center', locked: true, menuDisabled: true,
                        renderer: function(value, metaData, record, rowIndex, colIndex){
                            metaData.style = "padding: 0px; margin: 0px";
                            var icon = '';
                            var imgIcono = '';
                            switch(parseInt(record.get('nivel'))){
                                case 1:
                                    imgIcono = 'gk-icon-pie-1';
                                break;
                                case 2:
                                    imgIcono = 'gk-icon-pie-2';
                                break;
                                case 3:
                                    imgIcono = 'gk-icon-pie-3';
                                break;
                                case 4:
                                    imgIcono = 'gk-icon-pie-4';
                                break;
                            }

                            // console.log(record.get('id_graf_1'));
                            if (parseInt(record.get('id_graf_1')) > 0){

                                // console.log(record.get('id_graf_1'));

                                // tablero.p.vp_id_grafic = record.get('id_graf_1');
                                icon+= '<div class="gk-icon-grid">';
                                    icon+= '<span class="'+imgIcono+'" data-qtip="Click para ver gráfico estadístico." onclick="tablero.evt_charts('+parseInt(record.get('nivel'))+', \''+record.get('indicador')+'\', \''+record.get('id_graf_1')+'\', \''+record.get('t_graf_1')+'\');"></span>';
                                icon+= '</div>';
                            }
                            return icon;
                        }
                    });

                    var dinamicData = [];
                    var xIndex = [];
                    Ext.Object.each(res.children[0], function(index, value){
                        var a = index.split('-');
                        if (a.length == 3){
                            if (xIndex.indexOf(a[1]) < 0){
                                columns.push({text: a[1] == 'M' ? 'Meses' : 'Semanas ' + tablero.getDescripcionSemana(), menuDisabled: true, align: 'center', columns:[]});
                                columns[columns.length - 1].columns.push({text: value, width: 80, sortable: false, dataIndex: 'valor-' + a[1] + '-' + a[2], menuDisabled: true, itemId: 'xx-' + Ext.id(), align: 'right', 
                                    renderer: function(value, metaData, record, rowIndex, colIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                });
                                columns[columns.length - 1].columns.push({text: '%', width: 50, sortable: false, dataIndex: 'porcentaje-' + a[1] + '-' + a[2], menuDisabled: true, align: 'center',
                                    renderer: function(value, metaData, record, rowIndex, colIndex){
                                        return Ext.util.Format.number(value, '0.0');
                                    }
                                });
                                xIndex.push(a[1]);
                            }else{
                                if (a.indexOf('mes') >= 0){
                                    columns[columns.length - 1].columns.push({text: value, width: 80, sortable: false, dataIndex: 'valor-' + a[1] + '-' + a[2], menuDisabled: true, align: 'right',
                                        renderer: function(value, metaData, record, rowIndex, colIndex){
                                            return Ext.util.Format.number(value, '0,000');
                                        }
                                    });
                                    // if (Ext.util.Format.trim(value) != 'TOTALES')
                                    columns[columns.length - 1].columns.push({text: '%', width: 50, sortable: false, dataIndex: 'porcentaje-' + a[1] + '-' + a[2], menuDisabled: true, align: 'center',
                                        renderer: function(value, metaData, record, rowIndex, colIndex){
                                            return Ext.util.Format.number(value, '0.0');
                                        }
                                    });
                                }
                            }
                        }
                    });

                    var tree = Ext.create('Ext.tree.Panel',{
                        id: tablero.id + '-grid',
                        useArrows: true,
                        rootVisible: false,
                        // store: store,
                        root: res,
                        multiSelect: true,
                        columnLines: true,
                        rowLines: true,
                        columns: columns,
                        reserveScrollbar: true,
                        animate: false,
                        lbar:[
                            {
                                text: '',
                                id: tablero.id + '-btn-1',
                                icon: '/images/icon/1.png',
                                listeners:{
                                    click: function(obj, e){
                                        tablero.toggleButton(0);
                                        Ext.getCmp(tablero.id + '-grid').collapseAll();
                                    }
                                }
                            },
                            {
                                text: '',
                                id: tablero.id + '-btn-2',
                                icon: '/images/icon/2.png',
                                listeners:{
                                    click: function(obj, e){
                                        tablero.toggleButton(1);
                                        tablero.expandNodes(2);
                                    }
                                }
                            },
                            {
                                text: '',
                                id: tablero.id + '-btn-3',
                                icon: '/images/icon/3.png',
                                listeners:{
                                    click: function(obj, e){
                                        tablero.toggleButton(2);
                                        tablero.expandNodes(3);
                                    }
                                }
                            },
                            {
                                text: '',
                                id: tablero.id + '-btn-4',
                                icon: '/images/icon/4.png',
                                listeners:{
                                    click: function(obj, e){
                                        tablero.toggleButton(3);
                                        tablero.expandNodes(4);
                                    }
                                }
                            },
                            {
                                text: '',
                                id: tablero.id + '-btn-5',
                                icon: '/images/icon/5.png',
                                listeners:{
                                    click: function(obj, e){
                                        tablero.toggleButton(4);
                                        tablero.expandNodes(5);
                                    }
                                }
                            }
                        ],
                        stateful: false,
                        viewConfig:{
                            selectedItemCls: 'itemSelectIndicador',
                            getRowClass: function(record, rowIndex, rowParams, store){
                                return record.get('cls');
                            }
                        },
                        listeners:{
                            itemexpand: function(p, opts){
                                // console.log('Expandiendo');
                            },
                            headerclick: function(ct, column, e, t, eOpts){
                                var index = column.getIndex();
                                if (index == 1)
                                    tablero.evt_charts(0, 'base', '0', 'B')
                            },
                            rowcontextmenu: function(obj, record, tr, rowIndex, e, eOpts){
                                e.stopEvent();
                                tablero.showContextMenu().showAt(e.getXY());
                                return false;
                                // console.log(record);
                            }
                        }
                    });

                    contenedor.add(tree);
                    contenedor.doLayout();

                }
            });
        },
        evt_charts: function(nivel, op, graf, tgraf){
            var html_popup = '';
            Ext.get('popup_jquery_in').update('');
            switch(nivel){
                case 0: case 1: case 2: case 3: case 4:
                    html_popup+= '<div id="'+tablero.id+'-op-view'+'" class="gk-panel-op-indicadores"></div>';
                    html_popup+= '<div id="chart-container" style="width: 950px; height: 400px;">FusionCharts will render here</div>';
                    Ext.get('popup_jquery_in').update(html_popup);
                    $('#popup_jquery').bPopup({
                        modalClose: false,
                        opacity: 0.6,
                        onOpen: function(){
                            tablero.getCharts(nivel, op, graf, tgraf);
                        },
                        onClose: function(){

                        }
                    },
                    function(){
                        Ext.create('Ext.form.Panel',{
                            width: 500,
                            border: false,
                            items:[
                                {
                                    xtype: 'radiogroup',
                                    fieldLabel: 'Seleccione opción',
                                    columns: 2,
                                    vertical: true,
                                    labelWidth: 150,
                                    items:[
                                        {boxLabel: 'Ver por meses', name: tablero.id+'-rbtn01', inputValue: '1', checked: true},
                                        {boxLabel: 'Ver por semanas', name: tablero.id+'-rbtn01', inputValue: '2', hidden: Ext.getCmp(tablero.id+'-chk-semana').getValue() ? false : true}
                                    ],
                                    listeners:{
                                        change: function(obj, newValue, oldValue, eOpts){
                                            tablero.op = parseInt(newValue[tablero.id+'-rbtn01']);
                                            tablero.getCharts(nivel, op, graf, tgraf);
                                        }
                                    }
                                }
                            ],
                            renderTo: tablero.id+'-op-view'
                        });
                    });
                break;
            }
        },
        getCharts: function(nivel, op, graf, tgraf){
            switch(nivel){
                case 0:
                    var data = tablero.getDataLevel(nivel, op);
                    var category = [];
                    var aLabel = tablero.op == 1 ? data[0].nombres_meses : data[0].nombres_semanas;
                    Ext.Object.each(aLabel, function(index, value){
                        category.push({'label': value});
                    });

                    tablero.category = category;

                    var dataset = [];
                    Ext.Object.each(data, function(index, value){
                        dataset.push({'seriesname': value.indicador, 'data': []});
                        var aValor = tablero.op == 1 ? value.valores_meses : value.valores_semanas;
                        if (index != 0){
                            Ext.Object.each(aValor, function(index01, value01){
                                var aPorcentaje = tablero.op == 1 ? value.porcentaje_meses[index01] : value.porcentaje_semanas[index01];
                                dataset[index].data.push({'value': parseFloat(value01), 'showvalue': '1', 'displayValue': aPorcentaje + '%'});
                            });
                        }else{
                            Ext.Object.each(aValor, function(index01, value01){
                                dataset[index].data.push({'value': parseFloat(value01), 'showvalue': '1'});
                            });
                        }
                    });
                    dataset.push({"seriesName": "Tendencia", "renderAs": "line", "initiallyHidden": "1", "showValues": "0", "data": []});
                    var aValues = tablero.op == 1 ? data[0].valores_meses : data[0].valores_semanas;
                    Ext.Object.each(aValues, function(index, value){
                        dataset[dataset.length - 1].data.push({'value': parseFloat(value)});
                    });

                    tablero.dataset = dataset;

                    tablero.getFusionCharts(nivel, graf, tgraf);
                break;
                case 1: case 2: case 3: case 4:
                    tablero.getFusionCharts(nivel, graf, tgraf);
                break;
            }
        },
        getDataLevel: function(nivel, op){
            op = Ext.util.Format.lowercase(op);
            // console.log(op);
            var grid = Ext.getCmp(tablero.id + '-grid').getRootNode();
            if (nivel == 0){
                var data = [];
                Ext.Object.each(grid.childNodes, function(index, value){
                    var nombres_meses = [];
                    var valores_meses = [];
                    var porcentaje_meses = [];
                    var nombres_semanas = [];
                    var valores_semanas = [];
                    var porcentaje_semanas = [];
                    Ext.Object.each(value.data, function(index_v, value_v){
                        if (index_v.indexOf('mes-M') >= 0)
                            nombres_meses.push(value_v);
                        if (index_v.indexOf('valor-M') >= 0)
                            valores_meses.push(value_v);
                        if (index_v.indexOf('porcentaje-M') >= 0)
                            porcentaje_meses.push(value_v);
                        if (index_v.indexOf('mes-S') >= 0)
                            nombres_semanas.push(value_v);
                        if (index_v.indexOf('valor-S') >= 0)
                            valores_semanas.push(value_v);
                        if (index_v.indexOf('porcentaje-S') >= 0)
                            porcentaje_semanas.push(value_v);
                        // console.log(index01);
                    });
                    nombres_meses.pop();
                    valores_meses.pop();
                    porcentaje_meses.pop();
                    nombres_semanas.pop();
                    valores_semanas.pop();
                    porcentaje_semanas.pop();

                    if (nivel == 0){
                        data.push({indicador: value.data.indicador, nombres_meses: nombres_meses, valores_meses: valores_meses, porcentaje_meses: porcentaje_meses, nombres_semanas: nombres_semanas, valores_semanas: valores_semanas, porcentaje_semanas: porcentaje_semanas});
                    }/*else if(nivel == 1 && op == Ext.util.Format.lowercase(Ext.util.Format.trim(value.data.indicador))){
                        data.push({indicador: value.data.indicador, nombres_meses: nombres_meses, valores_meses: valores_meses, porcentaje_meses: porcentaje_meses, nombres_semanas: nombres_semanas, valores_semanas: valores_semanas, porcentaje_semanas: porcentaje_semanas});

                        Ext.Object.each(value.childNodes, function(index01, value01){
                            var nombres_meses = [];
                            var valores_meses = [];
                            var porcentaje_meses = [];
                            var nombres_semanas = [];
                            var valores_semanas = [];
                            var porcentaje_semanas = [];
                            Ext.Object.each(value01.data, function(index_v, value_v){
                                if (index_v.indexOf('mes-M') >= 0)
                                    nombres_meses.push(value_v);
                                if (index_v.indexOf('valor-M') >= 0)
                                    valores_meses.push(value_v);
                                if (index_v.indexOf('porcentaje-M') >= 0)
                                    porcentaje_meses.push(value_v);
                                if (index_v.indexOf('mes-S') >= 0)
                                    nombres_semanas.push(value_v);
                                if (index_v.indexOf('valor-S') >= 0)
                                    valores_semanas.push(value_v);
                                if (index_v.indexOf('porcentaje-S') >= 0)
                                    porcentaje_semanas.push(value_v);
                                // console.log(index01);
                            });
                            nombres_meses.pop();
                            valores_meses.pop();
                            porcentaje_meses.pop();
                            nombres_semanas.pop();
                            valores_semanas.pop();
                            porcentaje_semanas.pop();
                            data.push({indicador: value01.data.indicador, nombres_meses: nombres_meses, valores_meses: valores_meses, porcentaje_meses: porcentaje_meses, nombres_semanas: nombres_semanas, valores_semanas: valores_semanas, porcentaje_semanas: porcentaje_semanas});
                        });
                        return false;
                    }*/

                });
                return data;
            }
        },
        getFusionCharts: function(nivel, graf, tgraf){
            tablero.p.vp_id_grafic = graf;
            switch(tgraf){
                case 'B': tablero.tipo_grafico = 'mscombi2d'; break;
                case 'P': tablero.tipo_grafico = 'pie2d'; break;
            }
            switch(nivel){
                case 0:
                    FusionCharts.ready(function () {

                        var caption = tablero.op == 1 ? 'Resumen General Mensual' : 'Resumen General Semanal';

                        var revenueChart = new FusionCharts({
                            type: 'mscombi2d',
                            renderAt: 'chart-container',
                            width: '940',
                            height: '390',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": {
                                    "caption": caption,
                                    "yAxisName": "Cantidad G.E.",
                                    "rotateValues": "1",
                                    "formatNumberScale": "0",
                                    "exportEnabled": "1",
                                    "legendBgColor": "#CCCCCC",
                                    "legendBgAlpha": "20",
                                    "legendBorderColor": "#666666",
                                    "legendBorderThickness": "1",
                                    "legendBorderAlpha": "40",
                                    "legendShadow": "1",
                                    "theme": "fint"
                                },
                                "categories": [
                                    {
                                        "category": tablero.category
                                    }
                                ],
                                "dataset": tablero.dataset
                            }
                        });

                        revenueChart.render();
                    });
                break;
                case 1: case 2: case 3: case 4:
                    FusionCharts.ready(function () {

                        var p = tablero.p;
                        var vp_id_grafic = p.vp_id_grafic;
                        var vp_periodo = tablero.op == 1 ? 'M' : 'S';
                        var vp_anio = p.vp_anio;
                        var vp_mes = p.vp_mes;
                        var vp_nro_meses = p.vp_nro_meses;
                        var vp_id_semana = p.vp_id_semana;
                        var vp_ship_abc = p.vp_ship_abc;
                        var vp_id_shipp = p.vp_id_shipp;
                        var vp_id_orden = p.vp_id_orden;

                        var caption = tablero.op == 1 ? 'Resumen General Mensual' : 'Resumen General Semanal';

                        var revenueChart = new FusionCharts({
                            type: tablero.tipo_grafico,
                            renderAt: 'chart-container',
                            width: '940',
                            height: '390',
                            dataFormat: "jsonurl",
                            dataSource: tablero.url + "getDynamicCharts/?vp_id_grafic="+vp_id_grafic+'&vp_anio='+vp_anio+'&vp_mes='+vp_mes+'&vp_nro_meses='+vp_nro_meses+'&vp_id_semana='+vp_id_semana+'&vp_ship_abc='+vp_ship_abc+'&vp_id_shipp='+vp_id_shipp+'&vp_periodo='+vp_periodo+'&vp_id_orden='+vp_id_orden
                        });

                        revenueChart.render();
                    });
                break;
            }
        },
        toggleButton: function(vi){
            var obj = ['-btn-1', '-btn-2', '-btn-3', '-btn-4', '-btn-5'];
            Ext.Object.each(obj, function(index, value){
                if (index == vi){
                    Ext.getCmp(tablero.id + value).toggle(true);
                }else
                    Ext.getCmp(tablero.id + value).toggle(false);
            });
        },
        expandNodes: function(vi){
            var grid = Ext.getCmp(tablero.id + '-grid').getRootNode();

            Ext.getCmp(tablero.id+'-tab').el.mask('Procesando...', 'x-mask-loading');
            Ext.Object.each(grid.childNodes, function(index, value){
                if (vi > 1)
                    value.expand();
                else
                    value.collapse();
                Ext.Object.each(value.childNodes, function(index01, value01){
                    if (vi > 2)
                        value01.expand();
                    else
                        value01.collapse();
                    Ext.Object.each(value01.childNodes, function(index02, value02){
                        if (vi > 3)
                            value02.expand();
                        else
                            value02.collapse();
                        Ext.Object.each(value02.childNodes, function(index03, value03){
                            if (vi > 4)
                                value03.expand();
                            else
                                value03.collapse();
                        });
                    });
                });
            });
            Ext.getCmp(tablero.id+'-tab').el.unmask();
        },
        showContextMenu:function(){
            /* var rec = Ext.getCmp(tablero.id + '-grid').getSelectionModel().getSelection()[0];
             console.log(rec);
            var menu=[];
            menu.push(
                {
                    text: 'Analizar Indicador',
                    icon: '/images/icon/analize.png',
                    listeners:{
                        click: function(obj, e){
                            var rec = Ext.getCmp(tablero.id + '-grid').getSelectionModel().getSelection()[0];
                            // console.log(rec.get('id_indicador'));
                            var vp_id_indicador = rec.get('id_indicador');
                            var vp_anio = Ext.getCmp(tablero.id+'-anio').getValue();
                            var vp_mes = Ext.getCmp(tablero.id+'-meses').getValue();
                            var vp_nro_meses = Ext.getCmp(tablero.id+'-meses-ver').getValue();
                            var vp_id_semana = Ext.getCmp(tablero.id+'-chk-semana').getValue() ? Ext.getCmp(tablero.id+'-semanas').getValue() : '0';
                            var vp_ship_abc = Ext.getCmp(tablero.id+'-cat-shipper').getValue();
                            var vp_id_shipp = Ext.JSON.encode(Ext.getCmp(tablero.id+'-shipper').getValue());
                            var vp_id_orden = Ext.JSON.encode(Ext.getCmp(tablero.id+'-servicio').getValue());
                            win.show({vurl: tablero.url + 'form_analizar/?vp_id_indicador='+vp_id_indicador+'&vp_anio='+vp_anio+'&vp_mes='+vp_mes+'&vp_nro_meses='+vp_nro_meses+'&vp_id_semana='+vp_id_semana+'&vp_ship_abc='+vp_ship_abc+'&vp_id_shipp='+vp_id_shipp+'&indicador='+rec.get('indicador')+'&vp_id_orden='+vp_id_orden});
                        },
                        beforerender: function(obj, opts){
                            global.permisos({
                                id_serv: 62,
                                id_btn: obj.getId(),
                                id_menu: tablero.id_menu,
                                fn: ['tablero.consultar']
                            });
                        }
                    }
                }
            );
            if(parseInt(rec.get('id_indicador'))==1){
                    menu.push(
                    {
                        text: 'Filtro por Guía',
                        icon: '/images/icon/document-list-16.png',
                        listeners:{
                            click: function(obj, e){
                                if(tablero.p.vp_tipo_filtro!=1){
                                    tablero.p.vp_tipo_filtro=1;
                                    tablero.getConsulta();
                                }
                            },
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 63,
                                    id_btn: obj.getId(),
                                    id_menu: tablero.id_menu,
                                    fn: ['tablero.consultar']
                                });
                            }
                        }
                    },
                    {
                        text: 'Filtro por Peso',
                        icon: '/images/icon/weight.png',
                        listeners:{
                            click: function(obj, e){
                                if(tablero.p.vp_tipo_filtro!=2){
                                    tablero.p.vp_tipo_filtro=2;
                                    tablero.getConsulta();
                                }
                            },
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 64,
                                    id_btn: obj.getId(), 
                                    id_menu: tablero.id_menu,
                                    fn: ['tablero.consultar']
                                });
                            }
                        }
                    },
                    {
                        text: 'Filtro por Venta',
                        icon: '/images/icon/barra.png',
                        listeners:{
                            click: function(obj, e){
                                if(tablero.p.vp_tipo_filtro!=3){
                                    tablero.p.vp_tipo_filtro=3;
                                    tablero.getConsulta();
                                }
                            },
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 65, 
                                    id_btn: obj.getId(), 
                                    id_menu: tablero.id_menu,
                                    fn: ['tablero.consultar']
                                });
                            }
                        }
                    });
            }
            console.log(Ext.JSON.decode(menu));*/
            return Ext.create('Ext.menu.Menu', {
                items: [
                    {
                        text: 'Analizar Indicador',
                        icon: '/images/icon/analize.png',
                        listeners:{
                            click: function(obj, e){
                                var rec = Ext.getCmp(tablero.id + '-grid').getSelectionModel().getSelection()[0];
                                // console.log(rec.get('id_indicador'));
                                var vp_id_indicador = rec.get('id_indicador');
                                var vp_anio = Ext.getCmp(tablero.id+'-anio').getValue();
                                var vp_mes = Ext.getCmp(tablero.id+'-meses').getValue();
                                var vp_nro_meses = Ext.getCmp(tablero.id+'-meses-ver').getValue();
                                var vp_id_semana = Ext.getCmp(tablero.id+'-chk-semana').getValue() ? Ext.getCmp(tablero.id+'-semanas').getValue() : '0';
                                var vp_ship_abc = Ext.getCmp(tablero.id+'-cat-shipper').getValue();
                                var vp_id_shipp = Ext.JSON.encode(Ext.getCmp(tablero.id+'-shipper').getValue());
                                var vp_id_orden = Ext.JSON.encode(Ext.getCmp(tablero.id+'-servicio').getValue());
                                win.show({vurl: tablero.url + 'form_analizar/?vp_id_indicador='+vp_id_indicador+'&vp_anio='+vp_anio+'&vp_mes='+vp_mes+'&vp_nro_meses='+vp_nro_meses+'&vp_id_semana='+vp_id_semana+'&vp_ship_abc='+vp_ship_abc+'&vp_id_shipp='+vp_id_shipp+'&indicador='+rec.get('indicador')+'&vp_id_orden='+vp_id_orden});
                            },
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 62,
                                    id_btn: obj.getId(),
                                    id_menu: tablero.id_menu,
                                    fn: ['tablero.consultar']
                                });
                            }
                        }
                    },
                    {
                        text: 'Ayuda',
                        icon: '/images/icon/icon-help.png',
                        listeners:{
                            click: function(obj, e){
                                var rec = Ext.getCmp(tablero.id + '-grid').getSelectionModel().getSelection()[0];
                                 console.log(rec);
                                tablero.help(rec);
                                
                            },
                            beforerender: function(obj, opts){
                                
                            }
                        }
                    }
                ]
            });
        },
        help:function(rec){
            Ext.Ajax.request({
                url: tablero.url + 'get_scm_bsc_tablero_panel_help/',
                params:{
                    vp_id_indicador: rec.get('id_indicador')
                },
                success: function(response, options){
                    var res= Ext.JSON.decode(response.responseText);
                     console.log(res);
                     Ext.create('Ext.window.Window',{
                        id: tablero.id + '-win-help',
                        height: 350,
                        width: 300,
                        layout: 'border',
                        modal: true,
                        resizable: false,
                        closable: true,
                        header: false,
                        items: [
                            {
                                region:'center',
                                xtype: 'uePanel',
                                title: 'Formulario de Ayuda',
                                layout:'fit',
                                logo: 'file',
                                legend: rec.get('indicador'),
                                bg: '#991919',
                                bclose: true,
                                sectionStyle: 'margin: 0; padding-bottom: 2px;',
                                border:false,
                                items:[
                                    {
                                        xtype: 'fieldset',
                                        title: 'Objetivo',margin:5,
                                        items:[
                                            {
                                                html:'<p style="margin:4px;">'+res.data[0].ind_objetivo+'</p>'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Fórmula',margin:5,
                                        items:[
                                            {
                                                html:'<p style="margin:4px;">'+res.data[0].ind_formula+'</p>'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Medida',margin:5,
                                        items:[
                                            {
                                                html:'<p style="margin:4px;">'+res.data[0].ind_medida+'</p>'
                                            }
                                        ]
                                    },
                                    {
                                        height:200
                                    }
                                ],
                                evt_close: function(){
                                    Ext.getCmp(tablero.id + '-win-help').close();
                                }
                            }
                        ]
                    }).show().center();
                }
            });
            
        }
    }
    Ext.onReady(tablero.init, tablero);
}else{
    tab.setActiveTab(tablero.id+'-tab');
}
</script>