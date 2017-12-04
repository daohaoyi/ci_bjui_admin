	<style type="text/css">
		table tbody tr{
			height:30px;
			line-height:30px;
		}
	</style>
	<div class="bjui-pageContent">
        <table class="table table-condensed table-hover" style="margin-top: 50px;">
            <tbody>
            	<tr>
                    <td>
                        <label class="control-label x90" >id：</label>
                        <?= $info['id'];?>
                    </td>
                    <td>
                        <label class="control-label x90" >parentid：</label>
                        <?= $info['parentid'];?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="control-label x90" >名称：</label>
                        <?= $info['name'];?>
                    </td>
                    <td>
                        <label class="control-label x90" >图标：</label>
                        <?= $info['icon'];?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="control-label x90" >模块：</label>
                        <?= $info['model'];?>
                    </td>
                     <td>
                        <label class="control-label x90" >方法：</label>
                        <?= $info['action'];?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="control-label x90" >备注：</label>
                        <?= $info['remark'];?>
                    </td>
                     <td>
                        <label class="control-label x90" >排序：</label>
                        <?= $info['listorder'];?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="control-label x90" >类型：</label>
						<?= $this->config->item('menu_type')[$info['type']];?>
                    </td>
                    <td>
                        <label class="control-label x90" >状态：</label>
						<?= $this->config->item('menu_status')[$info['status']];?>
                    </td>
                </tr>
            </tbody>
        </table>
	</div>