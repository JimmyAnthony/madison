/*@qhqpaq*/
(function(){
	var par = {
		domain:"http://api.urbano.com.pe:8180/",
		jsId:'urb-j1m'
	},js;
	window.URB ={
		fn:{
			find: function(cname, node){
				var name = cname.substring(1);
				if (cname.substring(0,1)=="#") return document.getElementById(name)
				else if (document.getElementsByClassName) return node.getElementsByClassName(name)
				else if (document.querySelectorAll) return node.querySelectorAll('.' + name)
				else {
					var a = [];
					var els = node.getElementsByTagName('*');
					for ( var i = 0, j = els.length ; i < j ; i++ ) {
						if (els.item(i).className.indexOf(name) != -1)
							a.push(els.item(i));
					}
					return a;
				}
			},
			loadJs: function (url,id){
				var js = document.createElement("script");
				js.type = "text/javascript";
				js.id = id;
				js.src = url + "?noc="+Math.ceil(Math.random()*100000);
				document.getElementsByTagName("head")[0].appendChild(js);
				return this;
			},
			getResponse:function(callBack){
				var iframe = document.id('fragment').contentWindow;
				new m322463r(iframe,{onReceive: function(message,source,origin){callBack(message);callBack=function(){};}}).send('{"switchs":"response"}',par.domain);
			},
			getTrust:function(callBack){
				var iframe = document.id('fragment').contentWindow;
				new m322463r(iframe,{onReceive: function(message,source,origin){callBack(message);callBack=function(){};}}).send('{"switchs":"trust"}',par.domain);
			},
			setClear:function(){
				this.setSendEvent('{"switchs":"clear"}');
			},
			setDisabled:function(boolean){
				this.setSendEvent('{"switchs":"disabled","boolean":'+boolean.toString()+'}');
			},
			setAddress:function(id_geo,dir_id){
				this.setSendEvent('{"switchs":"setAddress","id_geo":'+id_geo+',dir_id:'+dir_id+'}');
			},
			setSendEvent:function(string){
				var iframe = document.id('fragment').contentWindow;
				new m322463r(iframe).send(string,par.domain);
			}
		},
		Widget:function(sz){
			var div = document.createElement('div');
			div.style.width = sz.width;
			div.style.height = sz.height;
			js.parentNode.insertBefore(div, js.nextSibling);
			return {
				draw: function(name){
					div.innerHTML = '<iframe name="fragment" id="fragment" src="'+par.domain+'plugin/finder/addreess/?user='+sz.user+'&key='+sz.key+'" width="'+sz.width+'" height="'+sz.height+'"></iframe>';
					URB.fn.loadJs(par.domain + "js/MooTools-Core-1.5.1.js",'MooTools');
					return this;
				},
				data: function(js){
					return this;
				}
			}
		}
	};
	js = URB.fn.find('#'+par.jsId);
	URB.Widget.data = URB.Widget({width:js.getAttribute('data-width'),height:js.getAttribute('data-height'),user:js.getAttribute('data-user'),key:js.getAttribute('data-key')}).draw(js.getAttribute('data-draw')).data();
})();

