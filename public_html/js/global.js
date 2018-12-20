
/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

win = {
    request:[],
    modules:[],
    loaded:false,
    getModule:function(v){
        var ms = this.modules;
        for(var i = 0, len = ms.length; i < len; i++){
            if(ms[i].id == v ){
                return ms[i];
            }
        }
        return null;
    },
    loadModuleComplete:function(success, vid){
        if(success === true && vid){
            this.request.push({
                id:vid
            });
        }
    },
    requestModule:function(id){
        var ms = this.request;
        for(var i = 0, len = ms.length; i < len; i++){
            if(id==ms[i].id) return true;
        }
        return false;
    },
    /**
     * Función para la carga de objetos Extjs (tabs, ventanas, etc)
     * ------------------------------------------------------------
     * Para la carga optimizada modificar parámetros de configuración
     * en el archivo config.ini( app.development = false )
     * El menu de opciones se encarga de cargarlos dinámicamente
     * según el nombre del objeto javascript
     */
    show:function(param){
        this.p = param;

        this.p.vurl = this.p.vurl == undefined ? '' : this.p.vurl;
        this.p.id_menu = this.p.id_menu == undefined ? 0 : this.p.id_menu;
        this.p.options = this.p.options == undefined?'':this.p.options;
        
        if (Ext.util.Format.trim(this.p.vurl) != ''){
            if (inicio.development == 1 || Ext.util.Format.trim(this.p.class) == ''){

                if (this.p.vurl.split('?').length > 1){
                    params = Ext.Object.fromQueryString(this.p.vurl.split('?')[1]+'&id_menu='+this.p.id_menu);
                }else{
                    params = {
                        id_menu: this.p.id_menu
                    }
                }

                Ext.getCmp(inicio.id + '-contenedor').mask('Please wait...');
                Ext.get('index_web_carga').load({
                    url: this.p.vurl,
                    scripts: true,
                    mask: true,
                    method: 'POST',
                    params: params,
                    callback:function(){
                        Ext.getCmp(inicio.id + '-contenedor').unmask();
                    }
                });
            }else{
                var op = this.p.options;
                var vid = this.p.class;
                this.modules.push({
                    id:vid
                });
                var m = this.getModule(vid);
                if(m){
                    if(this.requestModule(vid)){
                        var javascript = eval(vid);
                        javascript.init(op);
                    }else{
                        Ext.getCmp(inicio.id + '-contenedor').mask('Por favor espera...');
                        Ext.get('index_web_carga').load({
                            url: this.p.vurl,
                            scripts: true,
                            mask: true,
                            method: 'POST',
                            params:{
                                id_menu: this.p.id_menu
                            },
                            callback:function(){
                                Ext.getCmp(inicio.id + '-contenedor').unmask();
                                win.loadModuleComplete(true,vid);
                            }
                        });
                    }
                }
            }
        }else{
            global.Msg({
                msg: 'No tiene url asignada!',
                icon: 2,
                buttons: 1,
                fn: function(btn){

                }
            });
        }
    },
    getGalery:function(params){
        Ext.get(params.container).update('');
        switch(params.params.forma){
            case 'L':
                Ext.Ajax.request({
                    url:params.url,
                    params:params.params,
                    success:function(response,options){
                        var res = Ext.decode(response.responseText);
                        console.log(res);
                        var carouselLinks = [],
                        linksContainer = $('#'+params.container),
                        baseUrl;
                        for(var j=0;j<res.data.length;j++){
                            //baseUrl = 'fotos_iridio/';
                            $('<a/>')
                                .append($('<img>').prop('src', res.data[j].img_thumbs))
                                .prop('href', res.data[j].img_path)
                                .prop('title', res.data[j].time+"")
                                .attr('data-gallery', '')
                                .appendTo(linksContainer);
                        }
                    }
                });
            break;
            case 'F':
                linksContainer = $('#'+params.container),
                $('<a/>')
                    .append($('<img>').prop('src', params.params.img_path).prop('width', params.width)
                    .prop('height', params.height))
                    .prop('href', params.params.img_path)
                    .attr('data-gallery', '')
                    .appendTo(linksContainer);
            break;
        }
    }
}

var LarSyrExt = function(){
    this.Msg = function(p){
        var icons = [Ext.Msg.ERROR, Ext.Msg.INFO, Ext.Msg.WARNING, Ext.Msg.QUESTION];
        var button = [Ext.Msg.CANCEL, Ext.Msg.OK, Ext.Msg.OKCANCEL, Ext.Msg.YESNO, Ext.Msg.YESNOCANCEL];
        p.title = p.title==undefined?'.:MADISON:.':p.title;
        p.msg = p.title==undefined?'':p.msg;
        p.buttons = p.buttons==undefined?1:p.buttons;
        p.icon = p.icon==undefined?1:p.icon;
        p.fn = p.fn==undefined?false:p.fn;
        Ext.Msg.show({
            title: p.title,
            msg: p.msg,
            buttons: button[p.buttons],
            icon: icons[p.icon],
            fn:p.fn
        });
    };
    this.notification = function(p){
        this.p = p;
        this.p.vtitle = this.p.vtitle == undefined?'Notificacion':this.p.vtitle;
        this.p.vhtml = this.p.vhtml == undefined?'M&oacute;dulos Cargados':this.p.vhtml;
        this.p.vtime = this.p.vtime == undefined?5000:parseInt(this.p.vtime);
        new Ext.ux.Notification({
            title : this.p.vtitle,
            html : this.p.vhtml,
            autoDestroy : true,
            hideDelay : this.p.vtime,
            shadow : false,
            padding : 5
        }).show(Ext.getBody());
    };
    this.permisos = function(p){
        var type = p.type == undefined ? 'btn' : p.type;
        var a = [];
        var view = Ext.getCmp(inicio.id+'-menu-view');
        var record = view.getStore().getAt(p.id_menu);
        //return;
        Ext.Object.each(Ext.JSON.decode(Ext.JSON.encode(record.data.permisos)), function(index, value){
            a.push(parseInt(value.serv_id));
        });
        if (type == 'btn'){
            var index = a.indexOf(parseInt(p.id_serv));
            if (index >= 0){
                Ext.getCmp(p.id_btn).enable();
                Ext.getCmp(p.id_btn).resumeEvents();
            }else{
                Ext.getCmp(p.id_btn).suspendEvents();
                Ext.getCmp(p.id_btn).disable();
                if (p.fn.length > 0){
                    for(var i = 0; i < p.fn.length; ++i)
                        eval("if (" + p.fn[i] + ") delete " + p.fn[i]);
                }
            }
        }else if(type == 'link'){
            var html = '<div class="gk-column-icon">';
            extraCss = p.extraCss != undefined && p.extraCss != '' ? p.extraCss : '';
            Ext.Object.each(p.icons, function(index, value){
                var index = a.indexOf(parseInt(value.id_serv));
                var clsDisabled = 'disable_link';
                if (index >= 0){
                    clsDisabled = '';
                    value.js = value.js != undefined && value.js != '' ? value.js : '';
                    value.anchor = value.anchor != undefined && value.anchor != '' ? value.anchor:'#';
                }else{
                    value.js = '';
                    value.qtip = '';
                    value.anchor = '#';
                }
                if (value.img != undefined && value.img != '')
                    html+='<img src="/images/icon/' + value.img + '" class="link ' + clsDisabled + ' '+extraCss+'" data-qtip="' + value.qtip + '" onclick="' + value.js + '"/>';
                else{
                    var valor = isNaN(value.value);
                    if (!valor)
                        clsDisabled = parseFloat(value.value) == 0 ? 'disable_link' : '';
                    html+='<a href="'+value.anchor+'" class="link ' + clsDisabled + ' '+extraCss+'" data-qtip="' + value.qtip + '" onclick="' + value.js + '">'+value.value+'</a>';
                }
            });
            html+='</div>';
            return html;
        }
    };
    this.permisos_old = function(p){
        var type = p.type == undefined ? 'btn' : p.type;
        var a = [];
        Ext.Object.each(Ext.JSON.decode(Ext.getCmp('menu-' + p.id_menu).permisos), function(index, value){
            a.push(parseInt(value.serv_id));
        });
        if (type == 'btn'){
            var index = a.indexOf(parseInt(p.id_serv));
            if (index >= 0){
                Ext.getCmp(p.id_btn).enable();
                Ext.getCmp(p.id_btn).resumeEvents();
            }else{
                Ext.getCmp(p.id_btn).suspendEvents();
                Ext.getCmp(p.id_btn).disable();
                if (p.fn.length > 0){
                    for(var i = 0; i < p.fn.length; ++i)
                        eval("if (" + p.fn[i] + ") delete " + p.fn[i]);
                }
            }
        }else if(type == 'link'){
            var html = '<div class="gk-column-icon">';
            extraCss = p.extraCss != undefined && p.extraCss != '' ? p.extraCss : '';
            Ext.Object.each(p.icons, function(index, value){
                var index = a.indexOf(parseInt(value.id_serv));
                var clsDisabled = 'disable_link';
                if (index >= 0){
                    clsDisabled = '';
                    value.js = value.js != undefined && value.js != '' ? value.js : '';
                }else{
                    value.js = '';
                    value.qtip = '';
                }
                if (value.img != undefined && value.img != '')
                    html+='<img src="/images/icon/' + value.img + '" class="link ' + clsDisabled + ' '+extraCss+'" data-qtip="' + value.qtip + '" onclick="' + value.js + '"/>';
                else{
                    var valor = isNaN(value.value);
                    if (!valor)
                        clsDisabled = parseFloat(value.value) == 0 ? 'disable_link' : '';
                    html+='<a href="#" class="link ' + clsDisabled + ' '+extraCss+'" data-qtip="' + value.qtip + '" onclick="' + value.js + '">'+value.value+'</a>';
                }
            });
            html+='</div>';
            return html;
        }
    };
    this.state_item_menu = function(id_menu, bool){
        //Ext.getCmp('menu-' + id_menu).setDisabled(bool);
        //var view=Ext.getCmp(inicio.id+'-menu-view');
    };
    /**
     * PHP tiene una función sleep (), pero JavaScript no.
     * Realiza una parada en la ejecucion del JavaScript al mismo estilo de PHP.
     */
    this.sleep = function(milliseconds){
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
            if ((new Date().getTime() - start) > milliseconds){
              break;
            }
        }
    };
    this.isEmptyJSON = function(obj) {
      for(var i in obj) { return false; }
      return true;
    };
    this.chek_header = function(obj){
        Ext.getCmp(obj).setautoChekedOrd();
    };
    this.state_item_menu_config = function(obj,id_menu){
        var view = Ext.getCmp(inicio.id+'-menu-view');
        var record = view.getStore().getAt(id_menu);
        obj.setConfig({
            title: record.data.nombre,
            icon: record.data.icono
        });
    };
    /**
     * This function it's necessary for creating sub-level
     */
    this.subtable = function(p){
        var data = {
            columns: p.columns,
            data: p.data
        };

        var html = '<table id="' + p.id + '" class="lr-table">';
            html+= '<tr class="lr-table-tr lr-table-head">';
            Ext.Object.each(data.columns, function(index, v){
                html+= '<td class="lr-table-td lr-table-td-head" style="width:' + v.width + '; text-align: ' + (!v.align ? 'left' : v.align) + ';">' + v.text + '</td>';
            });
            html+= '</tr>';
        var valor = '';
            html+= '<tbody>';
            Ext.Object.each(data.data, function(index, v){
                html+='<tr class="lr-table-tr">';
                Ext.Object.each(data.columns, function(index01, a){
                    valor = v[a.dataIndex];
                    valor = valor == undefined ? '' : valor;
                    if (Ext.isFunction(a.renderer))
                        valor = a.renderer.call(p, valor, v);
                    html+='<td class="lr-table-td" style="text-align: ' + (!a.align ? 'left' : a.align) + ';" data=\'' + Ext.JSON.encode(v) + '\'>' + valor + '</td>';
                });
                html+='</tr>';
            });
            html+='</tbody>';
        html+= '</table>';

        var tpl = new Ext.XTemplate(
            '<div class="lr-div-table">',
                html,
            '</div>'
        );

        tpl.overwrite(p.renderTo, data);
    };
}

var global = new LarSyrExt();