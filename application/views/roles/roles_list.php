<div class="bjui-pageHeader">
	<a href="<?= site_url('roles/roles_add');?>" class="btn btn-green" data-toggle="dialog" data-width="800" data-height="400" data-id="dialog-roles-add">添加角色</a>
</div>
<div class="bjui-pageContent tableContent">
    <table data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th>ID</th>
                <th>角色名称</th>
                <th>创建时间</th>
                <th>备注</th>
                <th>状态</th>
                <th width="170" align='center'>操作</th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($rolesdata as $k=>$v): ?>
        	<tr data-id="<?= $v['id'];?>">
        		<td><?= $v['id']; ?></td>
        		<td><?= $v['name']; ?></td>
        		<td><?= $v['create_time']; ?></td>
        		<td><?= $v['remark']; ?></td>
        		<td><?= $this->config->item('user_state')[$v['status']]; ?></td>
        		<td>
        		<?php if($v['id'] !== '1'):?>
        		<a href="<?= site_url('roles/set_authority/' . $v['id']);?>" class="btn btn-green" data-toggle="dialog" data-width="500" data-height="400" data-id="dialog-set-authority">权限设置</a>
        		<a href="<?= site_url('roles/roles_edit/' . $v['id']);?>" class="btn btn-green" data-toggle="dialog" data-width="800" data-height="400" data-id="dialog-roles-edit">编辑</a>
        		<a type="button" class="btn btn-red" href="<?= site_url('roles/roles_delete/' . $v['id']);?>" data-toggle="doajax" data-reload="false" data-confirm-msg="确定要删除吗？">删除</a>
        		<?php endif;?>
        		</td>
        	 </tr>
        	<?php endforeach;?>
           
        </tbody>
    </table>
</div>