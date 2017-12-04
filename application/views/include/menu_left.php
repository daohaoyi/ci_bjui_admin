<!-- 左侧导航栏 -->
<div id="bjui-leftside">
    <div id="bjui-sidebar-s">
        <div class="collapse"></div>
    </div>
    <div id="bjui-sidebar">
        <div class="toggleCollapse"><h2><i class="fa fa-bars"></i> 导航栏 <i class="fa fa-bars"></i></h2><a href="javascript:;" class="lock"><i class="fa fa-lock"></i></a></div>
        <!-- 加载左侧导航栏 -->
        <div class="panel-group panel-main" data-toggle="accordion" id="bjui-accordionmenu">
		<?php foreach($menus as $k=>$v):?>
			<div class="panel panel-default">
				<div class="panel-heading panelContent">
					<h4 class="panel-title"><a data-toggle="collapse" data-parent="#bjui-accordionmenu" href="#bjui-collapse<?= $v['id'];?>" ><i class="fa fa-<?=$v['icon']; ?>"></i>&nbsp;<?= $v['name'];?></a></h4>
				</div>
				<div id="bjui-collapse<?= $v['id'];?>" class="panel-collapse panelContent collapse">
					<div class="panel-body" >
						<ul id="bjui-tree<?=$v['id']; ?>" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true">
							<?php if(!empty($v['items'])):?>
								<?php foreach ($v['items'] as $v2):?>
									<li data-id="<?= $v2['id']; ?>" data-pid="<?= $v2['parent']; ?>" <?php if($v2['type'] == 1):?> data-url="<?=$v2['url']; ?>" <?php endif;?> data-tabid="<?=$v2['url']; ?>" data-fresh="true"><?=$v2['name']; ?></li>
									<?php if(!empty($v2['items'])):?>
										<?php foreach ($v2['items'] as $v3):?>
											<li data-id="<?= $v3['id']; ?>" data-pid="<?= $v3['parent']; ?>" <?php if($v3['type'] == 1):?> data-url="<?=$v3['url']; ?>" <?php endif;?> data-tabid="<?=$v3['url']; ?>" data-fresh="true"><?=$v3['name']; ?></li>
										<?php endforeach;?>
									<?php endif;?>
								<?php endforeach;?>
							<?php endif;?>
						</ul>
					</div>
				</div>
			</div>
		<?php endforeach;?>
    	</div>
	</div>
</div>