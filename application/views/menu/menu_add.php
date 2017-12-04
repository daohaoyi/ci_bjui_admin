<div class="bjui-pageContent">
    <form action="<?= site_url('menu/menu_add_post') ?>" method="post"  id="menu_add_form" class="pageForm" data-toggle="validate">
    	<input type="hidden" name="verify_string" value="<?= $verify_string; ?>" />
    	<input type="hidden" name="parentid" value="<?= $parentid; ?>">
        <table class="table table-condensed table-hover">
            <tbody>
                <tr>
                    <td colspan="2" align="center"><h3>基本信息</h3></td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_name" class="control-label x90">名称：</label>
                        <input type="text" name="name" value="" data-rule="required" size="20">
                    </td>
                    <td>
                        <label for="j_dialog_icon" class="control-label x90">图标：</label>
                        <input type="text" name="icon" value="" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_model" class="control-label x90">模块：</label>
                        <input type="text" name="model" value="" data-rule="required;model" data-rule-model="[/[a-zA-Z0-9_]/, '由字母、数字、下划线组成']" size="20">
                    </td>
                     <td>
                        <label for="j_dialog_action" class="control-label x90">方法：</label>
                        <input type="text" name="action" value="" data-rule="required;action;remote(<?= site_url('menu/check_menu_unique'); ?>, model:model)" data-rule-action="[/[a-zA-Z0-9_]/, '由字母、数字、下划线组成']" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_remark" class="control-label x90">备注：</label>
                        <input type="text" name="remark" value="" size="20">
                    </td>
                     <td>
                        <label for="j_dialog_listorder" class="control-label x90">排序：</label>
                        <input type="text" name="listorder" value="" data-rule="number" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_type" class="control-label x90">类型：</label>
                        <select name="type" data-toggle="selectpicker" data-rule="required">
                            <option value="">请选择</option>
                            <?php foreach($this->config->item('menu_type') as $k=>$v): ?>
                			<option value="<?= $k;?>"><?= $v;?></option>
               				<?php endforeach;?>
                        </select>
                    </td>
                    <td>
                        <label for="j_dialog_status" class="control-label x90">状态：</label>
                        <select name="status" data-toggle="selectpicker" data-rule="required">
                            <option value="">请选择</option>
                            <?php foreach($this->config->item('menu_status') as $k=>$v): ?>
                			<option value="<?= $k;?>"><?= $v;?></option>
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