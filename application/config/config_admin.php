<?php

/**
 * 自定义用户相关配置文件
 */

/*
 * 超级管理员权限用户
 */
$config['root'] = array(
		'admin'=>1,
		'gaomin'=>1, 
		'fenghf'=>1
);

/*
 * 用户角色类型
 */
define('SUPER_ADMIN', 1);         //超级管理员
define('SYSTEM_ADMIN', 2);        //系统管理员
define('STARNET_USER', 3);        //星空运营团队
define('MERCHANT_MEMBER', 4);     //会员商户
define('SERVICE_USER', 5);        //客服
$config['role_type'] = array(
		SUPER_ADMIN              =>'超级管理员',
		SYSTEM_ADMIN             =>'系统管理员',
		STARNET_USER             =>'星空运营团队',
		MERCHANT_MEMBER          =>'会员商户',
		SERVICE_USER             =>'客服'
);

/*
 * 用户类型
 */
define('ADMIN', 1);              //管理员
define('MEMBER', 2);             //会员商户
$config['user_type'] = array(
		ADMIN                    =>'管理员',
		MEMBER                   =>'会员商户'
);

/*
 * 用户状态
 */
define('DISABLE_STATE', 0);      //禁用
define('ENABLE_STATE', 1);       //启用
$config['user_state'] = array(
		DISABLE_STATE            =>'禁用',
		ENABLE_STATE             =>'启用'
);

/*
 * 菜单类型
*/
define('DEFAULT_MENU', 0);          //只作为菜单
define('AUTH_MENU', 1);             //菜单+权限验证有url路径
$config['menu_type'] = array(
		DEFAULT_MENU             =>'只作为菜单',
		AUTH_MENU                =>'URL菜单'
);

/*
 * 菜单状态
*/
define('HIDE_MENU', 0);          //不显示
define('SHOW_MENU', 1);          //显示
$config['menu_status'] = array(
		SHOW_MENU                =>'显示',
		HIDE_MENU                =>'不显示'
);

?>
