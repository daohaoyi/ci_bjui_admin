本框架基于CI开发：
手册地址：http://codeigniter.org.cn/user_guide/index.html

开发时注意事项：
1,控制层编写时继承MY_Controller(core下),方便日后扩展
2,模型层编写时继承MY_Model(core下),已经封装了对db的增删改查方法及一些更加便捷的查询数据的方法
3,外网能访问的公共文件放到根目录的public下,eg:css,images,js
4,view层view文件最好按功能文件夹进行归集
5,view的公共部分放到view/include下，eg:head,banner,footer,这样不用每个模板页面都带一大堆头脚等不长改的代码
6,公共方法放到application/helper的common_function_helper.php中，eg： function write_log()
7,log目录默认是application/logs
8,根目录下的user_guide开发前/后可删掉
9,新的config文件定义以config_开头
10,db配置分主从库，配置时见文件内说明
11,db表结构在db文件夹内

PS:代码编写遵循codeigniter编写规范:http://codeigniter.org.cn/user_guide/general/styleguide.html