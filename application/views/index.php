<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>B-JUI 客户端框架</title>
		<meta name="Keywords" content="B-JUI,Bootstrap,DWZ,jquery,ui,前端,框架,开源,OSC,开源框架,knaan"/>
		<meta name="Description" content="B-JUI, Bootstrap for DWZ富客户端框架，基于DWZ富客户端框架修改。主要针对皮肤，编辑器，表单验证等方面进行了大量修改，引入了Bootstrap，Font Awesome，KindEditor，jquery.validationEngine，iCheck等众多开源项目。交流QQ群：232781006。"/> 
		<!-- intohead.html -->
		<?=$intohead; ?>
	</head>
	<body>
	    <div id="bjui-window">
		    <!-- header.html -->
		    <?=$header; ?>
		    <!-- 框架中部 -->
		    <div id="bjui-container" class="clearfix">
				<!-- leftmenu.html -->
				<?=$leftmenu; ?>
		        <!-- 工作区 -->
		        <div id="bjui-navtab" class="tabsPage">
		        	<!-- 工作区头部导航 -->
		            <div class="tabsPageHeader">
		                <div class="tabsPageHeaderContent">
		                    <ul class="navtab-tab nav nav-tabs">
		                        <li data-url="<?= site_url().'/index/main' ?>" data-faicon="home"><a href="javascript:;"><span><i class="fa fa-home"></i> #maintab#</span></a></li>
		                    </ul>
		                </div>
		                <div class="tabsLeft"><i class="fa fa-angle-double-left"></i></div>
		                <div class="tabsRight"><i class="fa fa-angle-double-right"></i></div>
		                <div class="tabsMore"><i class="fa fa-angle-double-down"></i></div>
		            </div>
		            <!-- 工作区头部导航栏最右侧下拉显示更多选项 -->
		            <ul class="tabsMoreList">
		                <li><a href="javascript:;">#maintab#</a></li>
		            </ul>
		            <!-- 工作区内容部分 -->
		            <div class="navtab-panel tabsPageContent">
		                <div class="navtabPage unitBox">
		                    <div class="bjui-pageContent" style="background:#FFF;">
		                        Loading...
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <!-- footer.html -->
		    <?=$footer;?>
	    </div>
	</body>
</html>