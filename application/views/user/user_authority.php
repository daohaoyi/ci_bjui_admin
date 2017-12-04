<SCRIPT type="text/javascript">
	var setting = {
		check: {
			enable: true,
			autoCheckTrigger: true
		},
		data: {
			simpleData: {
				enable: true
			}
		}
	};

	var zNodes = <?= $menus?>;
	
	$(document).ready(function(){
		$.fn.zTree.init($("#authTree"), setting, zNodes);
	});

	//保存事件
	function savechange(){
		//获取并过滤勾选的节点数据
		var treeObj = $.fn.zTree.getZTreeObj("authTree");
		var nodes = treeObj.getCheckedNodes(true);
		var info = new Array();
		$.each($(nodes),function(index,data){
			var node = new Object(); 
			node.user_id = <?= $roleid?>;
			node.m = data.m;
			node.a = data.a;
			info.push(node);			 
		});
		$.ajax({
			   type: "POST",
			   url: "<?= site_url('user/set_authority_post');?>",
			   data: {"nodes":info,"userid":<?= $roleid?>},
			   dataType: "json",
			   success:function(json){
				   $(this).dialog('closeCurrent');
				   $(this).bjuiajax('ajaxDone', json)       // 信息提示
				}
			});
	}
</SCRIPT>
<div class="bjui-pageContent">
	<div class="content_wrap">
		<div class="zTreeDemoBackground left">
			<ul id="authTree" class="ztree"></ul>
		</div>
	</div>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close">关闭</button></li>
        <li><button class="btn-default" onclick="savechange()">保存</button></li>
    </ul>
</div>
