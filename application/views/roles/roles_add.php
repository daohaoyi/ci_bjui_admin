<div class="bjui-pageContent">
    <form action="<?= site_url('roles/roles_add_post') ?>" method="post"  id="roles_add_form" class="pageForm" data-toggle="validate">
    	<input type="hidden" name="verify_string" value="<?= $verify_string; ?>" />
        <table class="table table-condensed table-hover">
            <tbody>
                <tr>
                    <td colspan="2" align="center"><h3>基本信息</h3></td>
                </tr>
                <tr>
                    <td>
                        <label for="j_dialog_name" class="control-label x90">角色名称：</label>
                        <input type="text" name="name" value="" data-rule="required;" size="20">
                    </td>
                    <td>
                        <label for="j_dialog_remark" class="control-label x90">备注：</label>
                        <input type="text" name="remark" value="" size="20">
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