<div class="bjui-pageContent">
    <form action="<?= site_url('user/user_edit_post') ?>" method="post"  id="user_edit_form" class="pageForm" data-toggle="validate">
        <input type="hidden" name="id" value="<?= $info['id'];?>">
        <table class="table table-condensed table-hover"  style="margin-top: 50px;">
            <tbody>
                <tr>
                    <td>
                        <label for="j_dialog_name" class="control-label x90">用户名：</label>
                        <input type="text" name="name" value="<?= $info['name'];?>" data-rule="required" size="20">
                    </td>
                </tr>
                <tr>
                 	<td>
                        <label for="j_dialog_realname" class="control-label x90">真实姓名：</label>
                        <input type="text" name="realname" value="<?= $info['realname'];?>" data-rule="required" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_roleid" class="control-label x90">角色：</label>
                        <select name="roleid" data-toggle="selectpicker" data-rule="required">
                            <option value="">请选择</option>
                            <?php foreach($roletype as $k=>$v): ?>
                			<option value="<?= $k;?>" <?php if ($info['roleid'] == $k): echo "selected = selected"; endif;?>><?= $v;?></option>
               				<?php endforeach;?>
                        </select>
                    </td>
                </tr>
                <tr>
                	<td>
                        <label for="j_dialog_usertype" class="control-label x90">用户类型：</label>
                        <select name="usertype" data-toggle="selectpicker" data-rule="required">
                            <option value="">请选择</option>
                            <?php foreach($this->config->item('user_type') as $k=>$v): ?>
                			<option value="<?= $k;?>" <?php if ($info['usertype'] == $k): echo "selected = selected"; endif;?>><?= $v;?></option>
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