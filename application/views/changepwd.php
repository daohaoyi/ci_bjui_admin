<div class="bjui-pageContent">
    <form action="<?= site_url('login/changepwd_post'); ?>" method="post"  id="user_edit_form" class="pageForm" data-toggle="validate">
        <table class="table table-condensed table-hover">
            <tbody>
                <tr>
                    <td>
                        <label for="j_dialog_oldpwd" class="control-label x90">旧密码：</label>
                        <input type="password" name="oldpwd" value="" data-rule="required;remote(<?= site_url('login/check_oldpwd'); ?>)" size="20">
                    </td>
                </tr>
                <tr>
                 	<td>
                        <label for="j_dialog_newpwd" class="control-label x90">新密码：</label>
                        <input type="password" name="newpwd" value="" data-rule="required;length[6~]" data-msg-length="{0}至少6位" size="20">
                    </td>
                </tr>
                <tr>
                 	<td>
                        <label for="j_dialog_againpwd" class="control-label x90">确认密码：</label>
                        <input type="password" name="againpwd" value="" data-rule="required;match(newpwd);" data-msg-match="{0}重复错误" size="20">
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