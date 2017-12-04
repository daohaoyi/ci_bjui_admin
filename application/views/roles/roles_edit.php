<div class="bjui-pageContent">
    <form action="<?= site_url('roles/roles_edit_post') ?>" method="post"  id="roles_edit_form" class="pageForm" data-toggle="validate">
        <input type="hidden" name="id" value="<?= $info['id'];?>">
        <table class="table table-condensed table-hover"  style="margin-top: 50px;">
            <tbody>
                <tr>
                    <td>
                        <label for="j_dialog_name" class="control-label x90">角色名称：</label>
                        <input type="text" name="name" value="<?= $info['name'];?>" data-rule="required" size="20">
                    </td>
                    <td>
                        <label for="j_dialog_remark" class="control-label x90">备注：</label>
                        <input type="text" name="remark" value="<?= $info['remark'];?>" size="20">
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