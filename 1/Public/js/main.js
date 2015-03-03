 $(function(){
	 tabClose();//关闭tab
	 tabCloseEven();//tab事件
	 $('#dg').datagrid('reload');
	 
    $('#tt').tabs({
        onLoad: function(panel){
            var plugin = panel.panel('options').title;
            panel.find('textarea[name="code-' + plugin + '"]').each(function(){
                var data = $(this).val();
                data = data.replace(/(\r\n|\r|\n)/g, '\n');
                if (data.indexOf('\t') == 0) {
                    data = data.replace(/^\t/, '');
                    data = data.replace(/\n\t/g, '\n');
                }
                data = data.replace(/\t/g, '    ');
                var pre = $('<pre name="code" class="prettyprint linenums"></pre>').insertAfter(this);
                pre.text(data);
                $(this).remove();
            });
           // prettyPrint();
        }
    });
});
 
 function gridTableEdit(openType){
	 switch(openType){
		case 'edit':
			var ids = [];
			var rows = $('#dg').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				ids.push(rows[i].id);
			}
			open1('edit.php?id='+ids,'php编辑');
		break;
		case 'copy':
			var ids = [];
			var rows = $('#dg').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				ids.push(rows[i].id);
			}
			open1('edit.php?id='+ids,'php编辑');
		break;
		default:
			return open1('form.html','222编辑');
		break;
	}
}
 var formater={
		 image:function(val,row){
				image='<img src="'+row.pic+'" width="70px" height="60px"/>';
				return image;
			}
		}
 

function open1(plugin,titles){
    if ($('#tt').tabs('exists', titles)) {
        $('#tt').tabs('select', titles);
    } 
    else {
        $('#tt').tabs('add', {
            title: titles,
            href: plugin,
            closable: true,
            extractor: function(data){
                data = $.fn.panel.defaults.extractor(data);
                return data;
            }
        });
    }
}
 function tabClose(){
     /*双击关闭TAB选项卡*/
     $(".tabs-inner").dblclick(function(){
         var subtitle = $(this).children("span").text();
         $('#tt').tabs('close',subtitle);
     })
 
    $(".tabs-inner").bind('contextmenu',function(e){
         $('#mm').menu('show', {
             left: e.pageX,
             top: e.pageY,
         });
         
         var subtitle =$(this).children("span").text();
         $('#mm').data("currtab",subtitle);
         return false;
     });
 }
 //绑定右键菜单事件
 function tabCloseEven()
 {
     //关闭当前
     $('#mm-tabclose').click(function(){
         var currtab_title = $('#mm').data("currtab");
         $('#tt').tabs('close',currtab_title);
     })
     //刷新
     $('#mm-reload').click(function(){
         var currtab_title = $('#mm').data("currtab");
		var tab = $('#tt').tabs('getSelected');  // get selected panel
		tab.panel('refresh', '');
     })
     //全部关闭
     $('#mm-tabcloseall').click(function(){
         $('.tabs-inner span').each(function(i,n){
             var t = $(n).text();
             $('#tt').tabs('close',t);
         });    
     });
     //关闭除当前之外的TAB
     $('#mm-tabcloseother').click(function(){
         var currtab_title = $('#mm').data("currtab");
         $('.tabs-inner span').each(function(i,n){
             var t = $(n).text();
             if(t!=currtab_title)
                 $('#tt').tabs('close',t);
         });    
     });
 }
