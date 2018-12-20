<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('tAnalizar-tab')){
    var tAnalizar = {
        id: 'tAnalizar',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/tablero/',
        vp_id_indicador: '<?php echo $p["vp_id_indicador"];?>',
        vp_anio: '<?php echo $p["vp_anio"];?>',
        vp_mes: '<?php echo $p["vp_mes"];?>',
        vp_nro_meses: '<?php echo $p["vp_nro_meses"];?>',
        vp_id_semana: '<?php echo $p["vp_id_semana"];?>',
        vp_ship_abc: '<?php echo $p["vp_ship_abc"];?>',
        vp_id_orden: '<?php echo $p["vp_id_orden"];?>',
        vp_id_shipp: '<?php echo $p["vp_id_shipp"];?>',
        vp_ambito: 'D',
        indicador: '<?php echo $p["indicador"];?>',
        init:function(){

            Ext.define('TanalizarM',{
                extend: 'Ext.data.Model',
                fields: [
                    { name: 'ciu_id', type: 'int' },
                    { name: 'ciudad', type: 'string' }
                ]
            });

            var store = Ext.create('Ext.data.Store',{
                model: 'TanalizarM'
            });

            var panel = Ext.create('Ext.form.Panel',{
                id: tAnalizar.id + '-form',
                layout: 'fit',
                border: false,
                tbar:[
                    {
                        xtype: 'radiogroup',
                        id: tAnalizar.id + '-rbtn-group',
                        columns: 4,
                        vertical: true,
                        fieldLabel: 'Analizar por',
                        labelWidth: 120,
                        items:[
                            {boxLabel: 'Departamento', name: tAnalizar.id + '-rbtn', inputValue: 'D', width: 100},
                            {boxLabel: 'Provincia', name: tAnalizar.id + '-rbtn', inputValue: 'P', width: 100},
                            {boxLabel: 'Distrito', name: tAnalizar.id + '-rbtn', inputValue: 'L', width: 100},
                            {boxLabel: 'Agencia', name: tAnalizar.id + '-rbtn', inputValue: 'A', width: 100}
                        ],
                        listeners:{
                            change: function(obj, newValue, oldValue, eOpts){
                                tAnalizar.vp_ambito = newValue[tAnalizar.id+'-rbtn'];
                                tAnalizar.loadGrid();
                            }
                        }
                    }
                ],
                items:[
                    {
                        xtype: 'panel',
                        id: tAnalizar.id + '-cont-grid',
                        border: false,
                        listeners:{
                            afterrender: function(obj, e){
                                var div = Ext.getCmp(tAnalizar.id + '-cont-grid');
                                var grid = Ext.create('Ext.grid.Panel',{
                                    id: tAnalizar.id + '-grid',
                                    store: store,
                                    columnLines: true,
                                    height: 410,
                                    border: false,
                                    columns:{
                                        items:[
                                            {
                                                text: 'Distrito',
                                                dataIndex: 'ciudad',
                                                flex: 1
                                            }
                                        ],
                                        defaults:{
                                            menuDisabled: true
                                        }
                                    }
                                });
                                div.add(grid).doLayout();
                                Ext.getCmp(tAnalizar.id + '-rbtn-group').setValue({'tAnalizar-rbtn': 'D'});
                            }
                        }
                    }
                ]
            });

            var panelX = Ext.create('Ext.form.Panel',{
                layout: 'fit',
                border: false,
                bodyStyle: 'background: white;',
                items:[
                    {
                        xtype: 'uePanel',
                        title: 'Drill down indicador: ' + tAnalizar.indicador,
                        logo: 'file',
                        legend: 'Analizar información.',
                        bg: '#991919',
                        bclose: true,
                        sectionStyle: 'margin: 0; padding-bottom: 2px;',
                        items: [
                            panel
                        ],
                        evt_close: function(){
                            Ext.getCmp(tAnalizar.id + '-win').close();
                        }
                    }
                ]
            });

            Ext.create('Ext.window.Window',{
                id: tAnalizar.id + '-win',
                height: 500,
                width: 850,
                layout: 'fit',
                modal: true,
                resizable: false,
                closable: false,
                header: false,
                items: panelX/*,
                buttonAlign: 'center',
                buttons:[
                    {
                        text: 'Cerrar',
                        icon: '/images/icon/close.png',
                        listeners:{
                            click: function(obj, e){
                                Ext.getCmp(tAnalizar.id + '-win').close();
                            }
                        }
                    }
                ]*/
            }).show().center();
        },
        loadGrid: function(){
            var vp_id_indicador = tAnalizar.vp_id_indicador;
            var vp_anio = tAnalizar.vp_anio;
            var vp_mes = tAnalizar.vp_mes;
            var vp_nro_meses = tAnalizar.vp_nro_meses;
            var vp_id_semana = tAnalizar.vp_id_semana;
            var vp_ship_abc = tAnalizar.vp_ship_abc;
            var vp_id_orden = tAnalizar.vp_id_orden;
            var vp_id_shipp = tAnalizar.vp_id_shipp;
            var vp_ambito = tAnalizar.vp_ambito;

            Ext.getCmp(tAnalizar.id + '-win').el.mask('Cargando...', 'x-mask-loading');
            Ext.Ajax.request({
                url: tAnalizar.url + 'get_bsc_tablero_panel_drill/',
                params:{
                    vp_id_indicador: vp_id_indicador,
                    vp_anio: vp_anio,
                    vp_mes: vp_mes,
                    vp_nro_meses: vp_nro_meses,
                    vp_id_semana: vp_id_semana,
                    vp_ship_abc: vp_ship_abc,
                    vp_id_orden: vp_id_orden,
                    vp_id_shipp: vp_id_shipp,
                    vp_ambito: vp_ambito
                },
                success: function(response, options){
                    var res = Ext.JSON.decode(response.responseText);

                    Ext.getCmp(tAnalizar.id + '-win').el.unmask();

                    var columns = [];
                    var models = [];
                    var aFields = [];

                    var grid = Ext.getCmp(tAnalizar.id + '-grid');
                    var store = Ext.create('Ext.data.Store',{
                        fields: res.fields,
                        data: Ext.data
                    });
                    store.loadData(res.data);

                    Ext.Object.each(res.campos, function(index, xvalue){
                        if (xvalue.items != undefined){
                            columns.push({text: xvalue.text, columns:[]});
                            Ext.Object.each(xvalue.items, function(index01, value01){
                                columns[columns.length - 1].columns.push({text: value01.text, width: value01.width, align: value01.align, dataIndex: value01.dataIndex,
                                    renderer: function(value, metaData, record, rowIndex, colIndex){
                                        return Ext.util.Format.number(value, value01.format);
                                    }
                                });
                            });
                        }else{
                            columns.push({text: xvalue.text, flex: 1, align: xvalue.align, dataIndex: xvalue.dataIndex, minWidth: 200});
                        }
                    });

                    var div = Ext.getCmp(tAnalizar.id + '-cont-grid');
                    var grid = Ext.create('Ext.grid.Panel',{
                        id: tAnalizar.id + '-grid',
                        store: store,
                        columnLines: true,
                        height: 410,
                        border: false,
                        columns:{
                            items:columns,
                            defaults:{
                                menuDisabled: true
                            }
                        }
                    });
                    div.removeAll();
                    div.add(grid).doLayout();
                }
            });
        }
    }
    Ext.onReady(tAnalizar.init, tAnalizar);
}else{
    tab.setActiveTab(tAnalizar.id+'-tab');
}
</script>