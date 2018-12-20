/**
 * @author Luis Remicio
 */
Ext.define('Ext.global.ueCarousel',{
    extend: 'Ext.Component',
    alias: 'widget.ueCarousel',
    rs: [],
    initComponent: function(){
        var me = this;

        this.renderTpl = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="ue-carousel-gestor">',
                    '<div id="'+me.id+'-carousel-captions" class="carousel slide" data-ride="carousel">',
                    '</div>',
                '</div>',
            '</tpl>'
        );

        this.listeners = {
            scope: this,
            afterrender: function(){
                var me = this;
            },
            resize: function(obj, width, height, oldWidth, oldHeight, eOpts){
                /**
                 * Implements this method for run.
                 * -------------------------------
                 * me.reconfigure(width);
                 */
            }
        };

        this.callParent();
    },
    setDataSlide: function(data){
        var me = this;
        me.dataSlide = data;
    },
    reconfigure: function(width){
        /**
         * Make carousel by data
         */
        var me = this;
        width = width == undefined ? me.getWidth() : width;
        
        me.configData(width);

        // console.log(this.id + '-carousel-captions');
        // console.log(me.rs.length);

        var div = Ext.get(this.id + '-carousel-captions');
        div.update('');
        if (me.rs.length > 0){
            var indicators = '';
            var vhtml = '';
            vhtml+= '<div class="carousel-inner">';
            Ext.Object.each(me.rs, function(index, value){

                indicators+= '<li data-target="'+'#' + me.id + '-carousel-captions'+'" data-slide-to="'+index+'" class="'+(index == 0 ? 'active' : '')+'"></li>';

                vhtml+= '<div class="item '+(index == 0 ? 'active' : '')+'">';
                    vhtml+= '<div class="files">';
                    Ext.Object.each(value, function(index01, value01){
                        vhtml+= '<a class="contenedor_file" href="#" data-qtip="'+value01.nombre_file+'">';
                            vhtml+= '<div class="icon">';
                                vhtml+= '<img src="/images/extensions/'+me.getIconFile(value01.nombre_file)+'" alt="">';
                            vhtml+= '</div>';
                            vhtml+= '<div class="opciones">';
                                vhtml+= '<div class="caption">';
                                    vhtml+= '<span>'+value01.tipo+'</span>';
                                    vhtml+= '<span>'+value01.nombre_file+'</span>';
                                vhtml+= '</div>';
                                vhtml+= '<div class="btn">';
                                    vhtml+= '<span>';
                                        vhtml+= '<img src="/images/icon/download.png" alt="">';
                                    vhtml+= '</span>';
                                    vhtml+= '<span>';
                                        vhtml+= '<img src="/images/icon/remove.png" alt="">';
                                    vhtml+= '</span>';
                                vhtml+= '</div>';
                            vhtml+= '</div>';
                        vhtml+= '</a>';
                    });
                    vhtml+= '</div>';
                vhtml+= '</div>';
            });
            vhtml+= '</div>';

            indicators = '<ol class="carousel-indicators">' + indicators + '</ol>';

            vhtml+= '<a class="left carousel-control" href="'+'#' + this.id + '-carousel-captions'+'" role="button" data-slide="prev">';
                vhtml+= '<span class="glyphicon glyphicon-chevron-left"></span>';
            vhtml+= '</a>';
            vhtml+= '<a class="right carousel-control" href="'+'#' + this.id + '-carousel-captions'+'" role="button" data-slide="next">';
                vhtml+= '<span class="glyphicon glyphicon-chevron-right"></span>';
            vhtml+= '</a>';

            div.update(indicators + vhtml);
            $('#' + this.id + '-carousel-captions').carousel({
                interval: false,
                wrap: false
            });
        }
    },
    configData: function(xwidth){
        /**
         * Configure dynamic data
         */
        var me = this;
        var data = this.dataSlide;
        var max_width = (xwidth - 100);
        var width_item = 125;
        var nItem = parseInt(max_width / width_item);
        // if ( max_width % width_item != )
        me.rs = [];
        var datax = [];
        var vi = 0;
        Ext.Object.each(data.items, function(index, value){
            datax.push(value);
            ++vi;
            if (vi == nItem){
                vi = 0;
                me.rs.push(datax);
                datax = [];
            }
        });
        if (vi != 0){
            me.rs.push(datax);
        }
    },
    getExtension: function(file){
        /**
         * Getting file extension
         */
        return file.split('.')[1];
    },
    getIconFile: function(file){
        var ex = Ext.util.Format.lowercase(file.split('.')[1]);
        return ex + '.png';
    }
});