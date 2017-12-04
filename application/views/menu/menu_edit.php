<div class="bjui-pageContent">
    <form action="<?= site_url('menu/menu_edit_post') ?>" method="post"  id="menu_edit_form" class="pageForm" data-toggle="validate">
    	<input type="hidden" name="id" value="<?= $info['id'];?>">
    	<input type="hidden" name="parentid" value="<?= $info['parentid'];?>">
        <table class="table table-condensed table-hover"  style="margin-top: 50px;">
            <tbody>
                <tr>
                    <td>
                        <label for="j_dialog_name" class="control-label x90">名称：</label>
                        <input type="text" name="name" value="<?= $info['name'];?>" data-rule="required" size="20">
                    </td>
                    <td>
                        <label for="j_dialog_icon" class="control-label x90">图标：</label>
                        <input type="text" name="icon" value="<?= $info['icon'];?>" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_model" class="control-label x90">模块：</label>
                        <input type="text" name="model" value="<?= $info['model'];?>" data-rule="required;model" data-rule-model="[/[a-zA-Z0-9_]/, '由字母、数字、下划线组成']" size="20">
                    </td>
                     <td>
                        <label for="j_dialog_action" class="control-label x90">方法：</label>
                        <input type="text" name="action" value="<?= $info['action'];?>" data-rule="required;" data-rule-action="[/[a-zA-Z0-9_]/, '由字母、数字、下划线组成']" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_remark" class="control-label x90">备注：</label>
                        <input type="text" name="remark" value="<?= $info['remark'];?>" size="20">
                    </td>
                     <td>
                        <label for="j_dialog_listorder" class="control-label x90">排序：</label>
                        <input type="text" name="listorder" value="<?= $info['listorder'];?>" data-rule="number" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_type" class="control-label x90">类型：</label>
                        <select name="type" data-toggle="selectpicker" data-rule="required">
                            <option value="">请选择</option>
                            <?php foreach($this->config->item('menu_type') as $k=>$v): ?>
                			<option value="<?= $k;?>" <?php if ($info['type'] == $k): echo "selected = selected"; endif;?>><?= $v;?></option>
               				<?php endforeach;?>
                        </select>
                    </td>
                    <td>
                        <label for="j_dialog_status" class="control-label x90">状态：</label>
                        <select name="status" data-toggle="selectpicker" data-rule="required">
                            <option value="">请选择</option>
                            <?php foreach($this->config->item('menu_status') as $k=>$v): ?>
                			<option value="<?= $k;?>" <?php if ($info['status'] == $k): echo "selected = selected"; endif;?>><?= $v;?></option>
               				<?php endforeach;?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close">关闭</button></li>
        <li><button type="submit" class="btn-default">保存</button></li>
    </ul>
</div>