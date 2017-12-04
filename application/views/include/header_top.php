<!-- 框架页头 -->
<header id="bjui-header">
    <div class="bjui-navbar-header">
        <button type="button" class="bjui-navbar-toggle btn-default" data-toggle="collapse" data-target="#bjui-navbar-collapse">
            <i class="fa fa-bars"></i>
        </button>
        <a class="bjui-navbar-logo" href="#"><img src="<?=base_url().'public/images/logo.png';?>"></a>
    </div>
    <nav id="bjui-navbar-collapse">
        <ul class="bjui-navbar-right">
            <li class="datetime"><div><span id="bjui-date"></span> <span id="bjui-clock"></span></div></li>
            <!--  <li><a href="#">消息 <span class="badge">4</span></a></li>-->
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $this->session->userdata('name');?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?=site_url('login/changepwd');?>" data-toggle="dialog" data-id="changepwd_page" data-mask="true" data-width="450" data-height="260">&nbsp;<span class="glyphicon glyphicon-lock"></span> 修改密码&nbsp;</a></li>
                    <li class="divider"></li>
                    <li><a href="<?=site_url('login/logout');?>" class="red">&nbsp;<span class="glyphicon glyphicon-off"></span> 注销登陆</a></li>
                </ul>
            </li>
            <li class="dropdown"><a href="#" class="dropdown-toggle theme blue" data-toggle="dropdown" title="切换皮肤"><i class="fa fa-tree"></i></a>
                <ul class="dropdown-menu" role="menu" id="bjui-themes">
                    <li><a href="javascript:;" class="theme_default" data-toggle="theme" data-theme="default">&nbsp;<i class="fa fa-tree"></i> 黑白分明&nbsp;&nbsp;</a></li>
                    <li><a href="javascript:;" class="theme_orange" data-toggle="theme" data-theme="orange">&nbsp;<i class="fa fa-tree"></i> 橘子红了</a></li>
                    <li><a href="javascript:;" class="theme_purple" data-toggle="theme" data-theme="purple">&nbsp;<i class="fa fa-tree"></i> 紫罗兰</a></li>
                    <li class="active"><a href="javascript:;" class="theme_blue" data-toggle="theme" data-theme="blue">&nbsp;<i class="fa fa-tree"></i> 天空蓝</a></li>
                    <li><a href="javascript:;" class="theme_green" data-toggle="theme" data-theme="green">&nbsp;<i class="fa fa-tree"></i> 绿草如茵</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="bjui-hnav">
        <button type="button" class="btn-default bjui-hnav-more-left" title="导航菜单左移"><i class="fa fa-angle-double-left"></i></button>
        <div id="bjui-hnav-navbar-box">
            <ul id="bjui-hnav-navbar">
            	<!-- 横向菜单     active自动加载左侧导航栏     data-toggle=slidebar点击加载到左侧导航栏      fa fa-check-square-o图标-->
            	<?php foreach ($menus as $v): ?>
            	<li>
            		<a href="javascript:;" data-toggle="slidebar"><i class="fa fa-<?=$v['icon']; ?>"></i> <?=$v['name']; ?></a>
                    <div class="items hide" data-noinit="true">
                    	<!-- 如果一级菜单下级是带url的子项 -->
                    	<?php if(!empty($v['default'])):?>
                    	<ul class="menu-items" data-faicon="caret-right" data-tit="<?=$v['name']; ?>">
                    		<!-- 遍历子项 -->
                    		<?php foreach ($v['default'] as $default):?>
                    		<li><a href="<?=$default['url']?>" data-options="{id:'<?=$default['url']?>', faicon:'caret-right'}"><?=$default['name']?></a></li>
                    		<?php endforeach;?>
                    	</ul>
                    	<?php endif;?>
                    		
	       	           	<!-- 如果一级菜单下级不是带url的菜单 -->
	       	           	<?php if(!empty($v['items'])):?>
	       	           		<!-- 遍历二级菜单 -->
	            	        <?php foreach ($v['items'] as $items):?>
      						<ul class="menu-items" data-tit="<?=$items['name']?>" data-faicon="caret-right">
	       	           			<!-- 如果二级菜单下是带url的菜单 -->
	         	                <?php if(!empty($items['default'])):?>
	         	                	<!-- 遍历三级菜单 -->
	         	                	<?php foreach ($items['default'] as $v2):?>
	         	                	<li><a href="<?=$v2['url']?>" data-options="{id:'<?=$v2['url']?>', faicon:'caret-right'}"><?=$v2['name']?></a></li>
	         	                	<?php endforeach;?>
	         	                <?php endif;?>
	         	                
	         	                <!-- 如果二级菜单下不是带url的菜单 -->
	         	                <?php if(!empty($items['items'])):?>
	         	                	<!-- 遍历三级菜单 -->
	         	                	<?php foreach ($items['items'] as $v3):?>
	         	                	    <li>
	         	                	        <a style="cursor:pointer" data-options="{id:'<?=$v3['url']; ?>', faicon:'caret-right'}"><?=$v3['name']; ?></a><b><i class="fa fa-angle-down"></i></b>
	         	                	        <?php if(!empty($v3['default'])):?>
	         	                	        <ul class="menu-items-children">
                                                 <!-- 遍历四级，最后一级带url的菜单 -->
    	       	                    		     <?php foreach ($v3['default'] as $v4):?>
    	       	                    		         <li><a href="<?=$v4['url']; ?>" data-options="{id:'<?=$v4['url']; ?>', faicon:'caret-right', fresh:'true'}"><?=$v4['name']; ?></a></li>
    		       	           				     <?php endforeach;?>
                                            </ul>
                                            <?php endif;?>
	         	                	    </li>
	         	                	<?php endforeach;?>
		       	           		<?php endif;?>
	       	           		</ul>
	       	           		<?php endforeach;?>
	       	           	<?php endif;?>
       	         	</div>
              	</li>
              	<?php endforeach;?>
            </ul>
        </div>
        <button type="button" class="btn-default bjui-hnav-more-right" title="导航菜单右移"><i class="fa fa-angle-double-right"></i></button>
    </div>
</header>