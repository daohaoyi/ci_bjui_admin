<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="<?= site_url('user/user_list'); ?>" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <input type="hidden" name="orderField" value="${param.orderField}">
        <input type="hidden" name="orderDirection" value="${param.orderDirection}">
        <div class="bjui-searchBar">
            <label>用户名：</label><input type="text" value="<?= $username;?>" name="username" class="form-control" size="10">&nbsp;
            <label>用户类型:</label>
            <select name="roleid" data-toggle="selectpicker">
                <option value="">全部</option>
                <?php foreach($roletype as $k=>$v): ?>
                	<option value="<?= $k;?>" <?php if ($roleid == $k): echo "selected = selected"; endif;?> ><?= $v;?></option>
                <?php endforeach;?>
            </select>&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reloadForm', true);" data-icon="undo">清空查询</a>
            <a href="<?= site_url('user/user_add');?>" class="btn btn-green" data-toggle="dialog" data-width="800" data-height="400" data-id="dialog-user-add">添加用户</a>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th data-order-field="id">ID</th>
                <th>用户名</th>
                <th>真实姓名</th>
                <th data-order-field="lasttime">最后登录时间</th>
                <th>最后登录IP</th>
<!--                <th data-order-field="balance">用户余额</th>-->
                <th>角色</th>
                <th>状态</th>
                <th width="175" align='center'>操作</th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($userdata as $k=>$v): ?>
        	<tr data-id="<?= $v['id'];?>">
        		<td><?= $v['id']; ?></td>
        		<td><?= $v['name']; ?></td>
        		<td><?= $v['realname']; ?></td>
        		<td><?= $v['lasttime']; ?></td>
        		<td><?= $v['lastip']; ?></td>
<!--        		<td style="color:red">--><?//= f2y($v['balance']); ?><!--</td>-->
        		<td><?= $roletype[$v['roleid']]; ?></td>
        		<td><?= $this->config->item('user_state')[$v['state']]; ?></td>
        		<td>
        		<a href="<?= site_url('user/set_authority/'.$v['id']);?>" class="btn btn-blue" data-toggle="dialog" data-width="800" data-height="400" data-id="dialog-user-edit">权限设置</a>
        		<a href="<?= site_url('user/user_edit/' . $v['id']);?>" class="btn btn-green" data-toggle="dialog" data-width="800" data-height="400" data-id="dialog-user-edit">编辑</a>
        		<?php if($v['state'] == 1):?>
        		<a type="button" class="btn btn-red" href="<?= site_url('user/enable_disable/' . $v['id'] . '/0');?>" data-toggle="doajax" data-reload="false" data-confirm-msg="确定要禁用吗？">禁用</a>
        		<?php else:?>
        		<a type="button" class="btn btn-green" href="<?= site_url('user/enable_disable/' . $v['id'] . '/1');?>" data-toggle="doajax" data-reload="false" data-confirm-msg="确定要启用吗？">启用</a>
        		<?php endif;?>
        		</td>
        	 </tr>
        	<?php endforeach;?>
           
        </tbody>
    </table>
</div>
<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
                <option value="150">150</option>
            </select>
        </div>
        <span>&nbsp;条，共 <?= $pageTotal; ?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?= $pageTotal; ?>" data-page-size="<?= $pageSize; ?>" data-page-current="<?= $pageCurrent; ?>">
    </div>
</div>