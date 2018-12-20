Ext.define('Ext.global.checkcolumnheader',{
    extend: 'Ext.grid.column.Check',
    alias: 'widget.checkcolumnheader',
    padre:'',
    menuDisabled: true,
	sortable: false,
	draggable: false,
	hideable: false,
    initComponent: function(){
    	var me = this;

    	/*me.setText('<input type="checkbox" id="'+me.id+'-header" onClick="'+me.fn+'();">'+
    		'<script type="text/javascript">
    			function velue(){
    				Ext.getCmp('+me.id+').setautoChekedOrd();
    			}
    		</script>'
    		);*/
		var meid = "'"+me.id+"'";
    	me.setText('<input type="checkbox" id="'+me.id+'-header" onClick="global.chek_header('+meid+');">');
    	//me.setText('<input type="checkbox" id="'+me.id+'-header" ">');
    	

    	this.listeners ={
    		checkchange:function( obj, rowIndex, checked, eOpts ){
    			var me =this;
    			me.autochekedAll();
    		},
    		render:function( obj, eOpts ){
    			//console.log('render');
    			//var header = me.id+'-header';
    			//document.getElementById(header).addEventListener('click',me.autochekedAll,false);
    		},
    		afterrender:function(obj,eOpts){
    			//me.autochekedAll();
    			var me =this;
    			me.padre  = this.findParentByType('grid').id;
    			//console.log(me.padre);
    			var grid = Ext.getCmp(me.padre);
    			
    			/*var store = grid.getStore().load({
					params:{},
					callback:function(){

					}
				});*/
				//console.log(store);

    			//store.addListener('load',me.autochekedAll);
    		}


    	};
     	this.callParent();
     	/***************************************************

     	Para ejecutar este plugin debes crear una funcion en tu hoja

		Ejemplo: Dentro de un grid
     	{
			xtype:'checkcolumnheader',
			dataIndex:'estado',
			id:sys_permiss.id+'chk-header',
			//fn:'sys_permiss.setheader',//funcion que ejecuta
			menuDisabled: true,
			sortable: false,
			draggable: false,
			hideable: false,
		}
 		//setheader:function(){
		//	Ext.getCmp(sys_permiss.id+'chk-header').setautoChekedOrd();
		//},

		Nota: El dataIndex debe ser type (boolean)
     	****************************************************/
    },
    autoChekedOrd:function(chk){
    	var me = this;
		var grid =Ext.getCmp(me.padre);
		var store = grid.getStore();
		store.each(function(record,idx){
			record.set(me.dataIndex,chk);
			record.commit();
		});
		grid.getView().refresh();
	},
    setautoChekedOrd:function(){
    	var me = this;
    	var header = me.id+'-header';
    	me.padre = this.findParentByType('grid').id;
		var data = document.getElementById(header).checked;
		var grid =Ext.getCmp(me.padre);
		var store = grid.getStore();
		if (data){
			//console.log('true');
			me.autoChekedOrd('true');
		}else{
			//console.log('false');
			me.autoChekedOrd('false');
		}
	},
	autochekedAll:function(){
		var me = this;
		var header = me.id+'-header';
		//console.log(me.padre);
		//var padre = this.findParentByType('grid').id;
		var grid =Ext.getCmp(me.padre);
		//console.log(me.padre);

		var store = grid.getStore();
		var cnt_grid = store.getCount();
		var cnt_stado = 0;
		if (store.getCount() > 0 ){
			for(var i =0; i < store.getCount();++i){
				var rec = store.getAt(i);
				var estado = rec.get(me.dataIndex) == true ? 1:0;
				if (estado > 0){
					cnt_stado = cnt_stado+1;
				}
			}
		}
		//console.log(cnt_grid);
		if (cnt_stado == cnt_grid && cnt_stado != 0){
			document.getElementById(header).checked = true;
		}else{
			document.getElementById(header).checked = false;
		}
	}

});