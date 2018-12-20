<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('cImpresiones-tab')){
    var cImpresiones = {
        id: 'cImpresiones',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/preclamos/',
        vp_prov: parseInt('<?php echo $p["vp_prov"];?>'),
        vp_fecini: '<?php echo $p["vp_fecini"];?>',
        vp_linea: '<?php echo $p["id_linea"];?>',
        vp_id_tipo: '<?php echo $p["tipo_rec_id"];?>',
        init:function(){

            var panel = Ext.create('Ext.form.Panel',{
                id: cImpresiones.id + '-form',
                layout: 'fit',
                border: false,
                tbar:[
                    'Fecha de impresión:',
                    {
                        xtype: 'textfield',
                        width: 70,
                        readOnly: true,
                        value: cImpresiones.vp_fecini
                    },
                    'Total sin imprimir:',
                    {
                        xtype: 'textfield',
                        id: cImpresiones.id + '-totsimprimir',
                        width: 70,
                        readOnly: true,
                        listeners:{
                            afterrender: function(obj){
                                Ext.Ajax.request({
                                    url: cImpresiones.url + 'get_scm_reclamo_impresion/',
                                    params:{
                                        vp_prov: cImpresiones.vp_prov,
                                        vp_linea: cImpresiones.vp_linea, 
                                        vp_id_imp: 0,
                                        vp_fecini: cImpresiones.vp_fecini,
                                        vp_id_tipo: cImpresiones.vp_id_tipo,
                                        vp_nivel: 'R'
                                    },
                                    success: function(response, options){
                                        var res = Ext.JSON.decode(response.responseText);
                                        // console.log(res);
                                        Ext.getCmp(cImpresiones.id + '-totsimprimir').setValue(res.reclamo);
                                        if (parseInt(res.reclamo) > 0){
                                            Ext.getCmp(cImpresiones.id + '-btn_impresion').enable();
                                            Ext.getCmp(cImpresiones.id+'-btn-etiquetas').enable();
                                        }                                        
                                        else{
                                            Ext.getCmp(cImpresiones.id + '-btn_impresion').disable();
                                            Ext.getCmp(cImpresiones.id+'-btn-etiquetas').disable();
                                        }
                                            
                                    }
                                });
                            }
                        }
                    },
                    '-',
                    {
                        text: '',
                        id: cImpresiones.id + '-btn_impresion',
                        icon: '/images/icon/pdf.png',
                        disabled: true,
                        tooltip:'Imprimir Formato de Auditoria',
                        listeners:{
                            click: function(obj, e){
                                var vp_prov = cImpresiones.vp_prov;
                                var vp_linea = cImpresiones.vp_linea; 
                                var vp_id_imp = 0;
                                var vp_fecini = cImpresiones.vp_fecini;
                                var vp_id_tipo = cImpresiones.vp_id_tipo;
                                var vp_nivel = 'D';
                                window.open(cImpresiones.url + 'get_rpt_auditoria/?vp_prov='+vp_prov+'&vp_id_imp='+vp_id_imp+'&vp_fecini='+vp_fecini+'&vp_id_tipo='+vp_id_tipo+'&vp_linea='+vp_linea+'&vp_nivel='+vp_nivel, '_blank');
                            }
                        }
                    },
                    {
                        text: '',
                        icon: '/images/icon/txt.png',
                        tooltip:'Exportar a texto (txt)',
                        listeners:{
                            click: function(obj, e){
                                var vp_prov = cImpresiones.vp_prov;
                                var vp_linea = cImpresiones.vp_linea; 
                                var vp_id_imp = 0;
                                var vp_fecini = cImpresiones.vp_fecini;
                                var vp_id_tipo = cImpresiones.vp_id_tipo;
                                var vp_nivel = 'D';
                                window.open(cImpresiones.url + 'getRptTxt/?vp_prov='+vp_prov+'&vp_id_imp='+vp_id_imp+'&vp_fecini='+vp_fecini+'&vp_id_tipo='+vp_id_tipo+'&vp_linea='+vp_linea+'&vp_nivel='+vp_nivel, '_blank');
                            }
                        }
                    },
                    {
                        text:'',
                        id:cImpresiones.id+'-btn-etiquetas',
                        icon:'/images/icon/print.png',
                        disabled:true,
                        tooltip:'Imprimir Etiquetas',
                        listeners:{
                            click:function(obj,e){
                                var vp_prov = cImpresiones.vp_prov;
                                var vp_linea = cImpresiones.vp_linea; 
                                var vp_id_imp = id_imp;
                                var vp_fecini = cImpresiones.vp_fecini;
                                var vp_id_tipo = cImpresiones.vp_id_tipo;
                                var vp_nivel = 'D';
                                window.open(cImpresiones.url + 'get_rpt_etiqueta/?vp_prov='+vp_prov+'&vp_id_imp='+vp_id_imp+'&vp_fecini='+vp_fecini+'&vp_id_tipo='+vp_id_tipo+'&vp_linea='+vp_linea+'&vp_nivel='+vp_nivel, '_blank');

                            }
                        }
                    }
                    
                ],
                items:[
                    {
                        xtype: 'grid',
                        id: cImpresiones.id + '-grid',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'id_imp', type: 'int'},
                                {name: 'fecha_impresion', type: 'string'},
                                {name: 'fecha_reclamo', type: 'string'},
                                {name: 'shipper', type: 'string'},
                                {name: 'total_imp', type: 'int'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: cImpresiones.url + 'get_scm_reclamo_lista_impresiones/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        columnLines: true,
                        // layout: 'hbox',
                        height: 100,
                        border: false,
                        columns:{
                            items:[
                                {
                                    text: 'Id. Grupo',
                                    dataIndex: 'id_imp',
                                    width: 60
                                },
                                {
                                    text: 'Fecha',
                                    dataIndex: 'fecha_impresion',
                                    width: 130,
                                    align: 'center'
                                },
                                {
                                    text: 'Fec. Reclamo',
                                    dataIndex: 'fecha_reclamo',
                                    width: 80,
                                    align: 'center'
                                },
                                {
                                    text: 'Shipper',
                                    dataIndex: 'shipper',
                                    flex: 1
                                },
                                {
                                    text: 'Tot. Impreso',
                                    dataIndex: 'total_imp',
                                    width: 75,
                                    align: 'right'
                                },
                                {
                                    text: 'Opciones',
                                    dataIndex: '',
                                    width: 60,
                                    align: 'center',
                                    renderer:function(val, metaData, record, rowIndex, colIndex, store, view){
                                        var vhtml = '<div class="gk-column-icon">'
                                            vhtml+= '<img class="link" src="/images/icon/document-print.png" onclick="cImpresiones.reimprimir('+record.get('id_imp')+');" style="cursor: pointer;" data-qtip="Imprimir Formato de Auditoria.">';
                                            vhtml+= '<img class="link" src="/images/icon/txt.png" data-qtip="Click para descargar txt." onclick="cImpresiones.reimprimir_txt('+record.get('id_imp')+');"/>';
                                            vhtml+= '<img class="link" src="/images/icon/print.png" onclick="cImpresiones.imprimire('+record.get('id_imp')+');" style="cursor: pointer;" data-qtip="Imprimir Etiquetas">';
                                        vhtml+= '</div>';
                                        return vhtml;
                                    }
                                }
                            ],
                            defaults:{
                                menuDisabled: true,
                                sortable: false
                            }
                        },
                        listeners:{
                            afterrender: function(obj){
                                obj.getStore().load({
                                    params:{
                                        vp_prov: cImpresiones.vp_prov,
                                        vp_fecini: cImpresiones.vp_fecini
                                    },
                                    callback: function(){

                                    }
                                });
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
                        title: 'Control de impresiones.',
                        logo: 'print',
                        legend: 'Seleccione opciones de impresión.',
                        bg: '#991919',
                        sectionStyle: 'margin: 0; padding-bottom: 2px;',
                        items: [
                            panel
                        ]
                    }
                ]
            });

            Ext.create('Ext.window.Window',{
                id: cImpresiones.id + '-win',
                // title: 'Control Impresiones',
                height: 220,
                width: 530,
                layout: 'fit',
                modal: true,
                resizable: false,
                closable: false,
                header: false,
                items: panelX,
                buttonAlign: 'center',
                buttons:[
                    {
                        text: 'Cerrar',
                        icon: '/images/icon/close.png',
                        listeners:{
                            click: function(obj, e){
                                Ext.getCmp(cImpresiones.id + '-win').close();
                            }
                        }
                    }
                ]
            }).show().center();
        },
        reimprimir: function(id_imp){
            var vp_prov = cImpresiones.vp_prov;
            var vp_linea = cImpresiones.vp_linea; 
            var vp_id_imp = id_imp;
            var vp_fecini = cImpresiones.vp_fecini;
            var vp_id_tipo = cImpresiones.vp_id_tipo;
            var vp_nivel = 'D';
            window.open(cImpresiones.url + 'get_rpt_auditoria/?vp_prov='+vp_prov+'&vp_id_imp='+vp_id_imp+'&vp_fecini='+vp_fecini+'&vp_id_tipo='+vp_id_tipo+'&vp_linea='+vp_linea+'&vp_nivel='+vp_nivel, '_blank');
        },
        reimprimir_txt: function(id_imp){
            var vp_prov = cImpresiones.vp_prov;
            var vp_linea = cImpresiones.vp_linea; 
            var vp_id_imp = id_imp;
            var vp_fecini = cImpresiones.vp_fecini;
            var vp_id_tipo = cImpresiones.vp_id_tipo;
            var vp_nivel = 'D';
            window.open(cImpresiones.url + 'getRptTxt/?vp_prov='+vp_prov+'&vp_id_imp='+vp_id_imp+'&vp_fecini='+vp_fecini+'&vp_id_tipo='+vp_id_tipo+'&vp_linea='+vp_linea+'&vp_nivel='+vp_nivel, '_blank');
        },
        imprimire: function(id_imp){
            var vp_prov = cImpresiones.vp_prov;
            var vp_linea = cImpresiones.vp_linea; 
            var vp_id_imp = id_imp;
            var vp_fecini = cImpresiones.vp_fecini;
            var vp_id_tipo = cImpresiones.vp_id_tipo;
            var vp_nivel = 'D';
            window.open(cImpresiones.url + 'get_rpt_etiqueta/?vp_prov='+vp_prov+'&vp_id_imp='+vp_id_imp+'&vp_fecini='+vp_fecini+'&vp_id_tipo='+vp_id_tipo+'&vp_linea='+vp_linea+'&vp_nivel='+vp_nivel, '_blank');
        }   
    }
    Ext.onReady(cImpresiones.init, cImpresiones);
}else{
    tab.setActiveTab(cImpresiones.id+'-tab');
}
</script>