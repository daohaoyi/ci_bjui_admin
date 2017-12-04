<SCRIPT type="text/javascript">
	var setting = {
		async: {
			enable: true,
			url:"<?= site_url('menu/get_menu'); ?>",
			autoParam:["id"],
			dataFilter: filter
		},
		view: {
			expandSpeed:"normal",
			addHoverDom: addHoverDom,
			removeHoverDom: removeHoverDom,
			selectedMulti: false
		},
		edit: {
			enable: true,
			showRenameBtn: false,
			showRemoveBtn: false,
			drag: {
				prev:false,
				next:false,
				inner: dropInner,
			}
		},
		data: {
			simpleData: {
				enable: true
			}
		},
		callback: {
			onDrop: zTreeOnDrop
		}
	};

	function filter(treeId, parentNode, childNodes) {
		if (!childNodes) return null;
		for (var i=0, l=childNodes.length; i<l; i++) {
			childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
		}
		return childNodes;
	}
	
	//内部拖拽
	function dropInner(treeId, nodes, targetNode) {
		if (targetNode && targetNode.dropInner === false) {
			return false;
		}
		return true;
	}
	
	//鼠标移上
	function addHoverDom(treeId, treeNode) {
		//查看按钮
		var sObj = $("#" + treeNode.tId + "_span");
		if ($("#showBtn_"+treeNode.tId).length>0) return;
		var showStr = "<span class='showbtn' id='showBtn_" + treeNode.tId
		+ "' title='查看' onfocus='this.blur();'></span>";
		sObj.after(showStr);
		//查看事件
		var btn3 = $("#showBtn_"+treeNode.tId);
		if (btn3) btn3.bind("click", function(){
			$(this).dialog({"id":"dialog-menu-show", "title":"查看菜单", "width":"800", "height":"400", "url":"<?= site_url('menu/menu_show');?>", "data":"id=" + treeNode.id});
		});
		//添加按钮
		var showObj = $("#showBtn_" + treeNode.tId);
		if ($("#addBtn_"+treeNode.tId).length>0) return;
		var addStr = "<span class='tree_add' id='addBtn_" + treeNode.tId
			+ "' title='添加' onfocus='this.blur();'></span>";
		showObj.after(addStr);
		//添加事件
		var btn = $("#addBtn_"+treeNode.tId);
		if (btn) btn.bind("click", function(){
			$(this).dialog({"id":"dialog-menu-add", "title":"添加菜单", "width":"800", "height":"400", "url":"<?= site_url('menu/menu_add');?>", "data":"id=" + treeNode.id, "onClose":"freshParent"});
		});
		//编辑按钮
		var addObj = $("#addBtn_" + treeNode.tId);
		if ($("#editBtn_"+treeNode.tId).length>0) return;
		var editStr = "<span class='button edit' id='editBtn_" + treeNode.tId
		+ "' title='编辑' onfocus='this.blur();'></span>";
		addObj.after(editStr);
		//编辑事件
		var btn2 = $("#editBtn_"+treeNode.tId);
		if (btn2) btn2.bind("click", function(){
			$(this).dialog({"id":"dialog-menu-edit", "title":"编辑菜单", "width":"800", "height":"400", "url":"<?= site_url('menu/menu_edit');?>", "data":"id=" + treeNode.id, "onClose":"freshNode"});
		});
		//删除按钮
		var editObj = $("#editBtn_" + treeNode.tId);
		if ($("#removeBtn_"+treeNode.tId).length>0) return;
		var removeStr = "<span class='button remove' id='removeBtn_" + treeNode.tId
		+ "' title='删除' onfocus='this.blur();'></span>";
		editObj.after(removeStr);
		//删除事件
		var btn3 = $("#removeBtn_"+treeNode.tId);
		if (btn3) btn3.bind("click", function(){
			$(this).bjuiajax('doAjax', {url:"<?= site_url('menu/menu_delete');?>",type:"post",data:{id:treeNode.id},confirmMsg:"确认删除 "+treeNode.name+" ?",callback:function(json){
				if(json.code == '0000'){
					$(this).alertmsg('ok', json.msg, {displayMode:'slide', displayPosition:'topcenter'});
					//页面删除该菜单
					deleteNode();
				}else if(json.code == '9999'){
					$(this).alertmsg('error', json.msg, {displayMode:'slide', displayPosition:'topcenter'});
				}
			}});
		});
	};
	
	//鼠标移出
	function removeHoverDom(treeId, treeNode) {
		$("#showBtn_"+treeNode.tId).unbind().remove();
		$("#addBtn_"+treeNode.tId).unbind().remove();
		$("#editBtn_"+treeNode.tId).unbind().remove();
		$("#removeBtn_"+treeNode.tId).unbind().remove();
	};

	$(document).ready(function(){
		$.fn.zTree.init($("#ztree_menu"), setting);
	});
	
	//添加完成重新请求子项
	function freshParent() {
		var treeObj = $.fn.zTree.getZTreeObj("ztree_menu");
		//获取选中的对象
		var nodes = treeObj.getSelectedNodes();
		if (nodes.length>0) {
			//添加展开效果
			if(nodes[0].isParent === false){
				nodes[0].isParent = true;
				treeObj.updateNode(nodes[0]);
			}
			//重新加载子项
			treeObj.reAsyncChildNodes(nodes[0], "refresh");
		}
	}
	
	//编辑完成重新请求子项
	function freshNode(){
		var treeObj = $.fn.zTree.getZTreeObj("ztree_menu");
		//获取选中的对象
		var nodes = treeObj.getSelectedNodes();
		if (nodes.length > 0) {
			var node = nodes[0].getParentNode();
			//如果有父节点，则重新加载该父节点的子项
			if(node !== null){
				treeObj.reAsyncChildNodes(node, "refresh");
			}
		}
	}

	//删除完成页面删除子项
	function deleteNode(){
		var treeObj = $.fn.zTree.getZTreeObj("ztree_menu");
		//获取选中的对象
		var nodes = treeObj.getSelectedNodes();
		if (nodes.length > 0) {
			treeObj.removeNode(nodes[0]);
		}
	}
	
	//拖拽结束事件
	function zTreeOnDrop(event, treeId, treeNodes, targetNode, moveType) {
		if(treeNodes && targetNode){
			$.ajax({
				   type: "POST",
				   url: "<?= site_url('menu/menu_drag');?>",
				   data: {"id":treeNodes[0].id,"pid":targetNode.id},
				});
		}
	};
	
</SCRIPT>
<div class="bjui-pageHeader">
	<div style="margin-left:20px;">
		<a href="<?= site_url('menu/menu_add');?>" class="btn btn-green" data-toggle="dialog" data-id="dialog-menu-add" data-title="添加菜单" data-width="800" data-height="400" data-data="id=0">添加一级菜单</a>
	</div>
</div>
<div class="bjui-pageContent">
	<div style="padding:5px;">
		<div class="clearfix">
			<div style="float:left; width:90%; height:100%; margin-left:5%; overflow:auto;">
				<ul id="ztree_menu" class="ztree"></ul>
			</div>
		</div>
	</div>
	<!-- tips -->
	<div style="margin-top:350px; margin-left:5%;">
		<table data-toggle="tablefixed" data-width="30%">
			<tbody>
				<tr>
					<td colspan="2">tips</td>
				</tr>
				<tr>
					<td style="width:5%;">模块/方法</td><td>即开发时的控制层的类及方法名，一律小写，任何开发时的新增方法都要定义在这里，可根据业务逻辑的顺序逐级添加</td>
				</tr>
				<tr>
					<td>类型</td><td>除一级菜单强制选“只作为菜单”外，其他级菜单都默认先选“url菜单”，如果菜单下还有子菜单，那再改为“只作为菜单”(只有只作为菜单时它下面的子孙菜单才会有可能展示)</td>
				</tr>
				<tr>
					<td>状态</td><td>显示/不显示表示菜单是否在左侧展示出来</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>