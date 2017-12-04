<div class="bjui-pageContent">
    <form action="<?= site_url('user/user_add_post') ?>" method="post"  id="user_add_form" class="pageForm" data-toggle="validate">
    	<input type="hidden" name="verify_string" value="<?= $verify_string; ?>" />
        <table class="table table-condensed table-hover">
            <tbody>
                <tr>
                    <td colspan="2" align="center"><h3>基本信息</h3></td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_name" class="control-label x90">用户名：</label>
                        <input type="text" name="name" value="" data-rule="required;remote(<?= site_url('user/check_name_unique'); ?>)" size="20">
                    </td>
                    <td>
                        <label for="j_dialog_realname" class="control-label x90">真实姓名：</label>
                        <input type="text" name="realname" value="" data-rule="required" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_pwd" class="control-label x90">密码：</label>
                        <input type="password" name="pwd" value="" data-rule="required;length[6~]" data-msg-length="{0}至少6位" size="20">
                    </td>
                     <td>
                        <label for="j_dialog_againPwd" class="control-label x90">确认密码：</label>
                        <input type="password" name="againPwd" value="" data-rule="required;match(pwd);" data-msg-match="{0}重复错误" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_roleid" class="control-label x90">角色：</label>
                        <select name="roleid" data-toggle="selectpicker" data-rule="required">
                            <option value="">请选择</option>
                            <?php foreach($roletype as $k=>$v): ?>
                			<option value="<?= $k;?>"><?= $v;?></option>
               				<?php endforeach;?>
                        </select>
                    </td>
                    <td>
                        <label for="j_dialog_usertype" class="control-label x90">用户类型：</label>
                        <select name="usertype" data-toggle="selectpicker" data-rule="required">
                            <option value="">请选择</option>
                            <?php foreach($this->config->item('user_type') as $k=>$v): ?>
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