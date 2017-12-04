-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-03-10 18:29:43
-- 服务器版本： 5.6.26
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci_ll_dev`
--

-- --------------------------------------------------------

--
-- 表的结构 `xk_admin`
--

CREATE TABLE `xk_admin` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '管理员id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '登录名称',
  `realname` varchar(16) NOT NULL DEFAULT '' COMMENT '真实姓名/商户名称',
  `password` char(64) NOT NULL DEFAULT '' COMMENT '密码',
  `lastip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `dateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `lasttime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后登录时间',
  `lastchargetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后加款时间',
  `usertype` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '用户类别 1管理员  2会员商户',
  `secretkey` char(20) NOT NULL DEFAULT '' COMMENT '秘钥',
  `callbackurl` varchar(128) NOT NULL DEFAULT '' COMMENT 'callback地址',
  `bindip` varchar(255) NOT NULL DEFAULT '' COMMENT '商户绑定IP',
  `ver` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '扣钱版本数',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '以分为单位',
  `roleid` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色id',
  `state` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `xk_admin`
--

INSERT INTO `xk_admin` (`id`, `name`, `realname`, `password`, `lastip`, `dateline`, `lasttime`, `lastchargetime`, `usertype`, `secretkey`, `callbackurl`, `bindip`, `ver`, `balance`, `roleid`, `state`) VALUES
(1, 'admin', 'aaaaa', 'f0975e07ccfae10adc3949ba59abbe56e057f20f883e06e4', '172.16.21.226', '2016-01-17 16:00:00', '2016-03-10 10:18:30', '0000-00-00 00:00:00', 1, '', '', '', 0, '0.00', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `xk_adm_menu`
--

CREATE TABLE `xk_adm_menu` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'id',
  `parentid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级id',
  `model` varchar(32) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(32) NOT NULL DEFAULT '' COMMENT '操作名称',
  `data` varchar(50) NOT NULL DEFAULT '' COMMENT '额外参数',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '菜单类型 1：权限认证+菜单；0：只作为菜单',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，1显示，0不显示，-1删除',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `remark` varchar(128) NOT NULL COMMENT '备注',
  `listorder` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单表';

--
-- 转存表中的数据 `xk_adm_menu`
--

INSERT INTO `xk_adm_menu` (`id`, `parentid`, `model`, `action`, `data`, `type`, `status`, `name`, `icon`, `remark`, `listorder`) VALUES
(1, 0, 'system', 'default', '', 0, 1, '系统设置', 'cogs', '系统设置', 100),
(2, 1, 'menu', 'default', '', 0, 1, '菜单管理', 'list', '菜单管理', 100),
(3, 0, 'authority', 'default', '', 0, 1, '权限管理', 'key', '权限管理', 99),
(4, 3, 'user', 'user_list', '', 1, 1, '管理员列表', '', '管理员列表', 0),
(5, 3, 'roles', 'roles_list', '', 1, 1, '角色管理', '', '角色管理', 0),
(6, 2, 'menu', 'get_menu', '', 1, 0, 'ajax获取下级菜单', '', 'ajax获取下级菜单', 0),
(7, 2, 'menu', 'menu_list', '', 1, 1, '菜单列表', '', '菜单列表', 100),
(8, 2, 'menu', 'menu_add', '', 0, 0, '添加菜单', '', '添加菜单', 0),
(9, 8, 'menu', 'menu_add_post', '', 1, 0, '执行添加菜单', '', '提交添加菜单', 0),
(10, 2, 'menu', 'menu_edit', '', 0, 0, '编辑菜单', '', '编辑菜单', 0),
(11, 10, 'menu', 'menu_edit_post', '', 1, 0, '执行编辑菜单', '', '执行编辑菜单', 0),
(12, 2, 'menu', 'menu_delete', '', 1, 0, '删除菜单', '', '删除菜单', 0),
(13, 2, 'menu', 'menu_drag', '', 1, 0, '拖拽菜单修改父级', '', '拖拽菜单修改父级', 0),
(14, 2, 'menu', 'menu_show', '', 1, 0, '查看菜单', '', '查看菜单', 0),
(15, 8, 'menu', 'check_menu_unique', '', 1, 0, '验证model+action唯一', '', '添加菜单时验证model+action是否唯一', 0),
(16, 4, 'user', 'user_add', '', 0, 0, '添加管理员', '', '添加管理员', 0),
(17, 16, 'user', 'check_name_unique', '', 1, 0, '验证用户名唯一', '', '添加管理员验证验证用户名是否唯一', 0),
(18, 16, 'user', 'user_add_post', '', 1, 0, '执行添加管理员', '', '执行添加用户', 0),
(19, 4, 'user', 'user_edit', '', 0, 0, '编辑管理员', '', '编辑管理员', 0),
(20, 19, 'user', 'user_edit_post', '', 1, 0, '执行编辑管理员', '', '执行编辑管理员', 0),
(21, 4, 'user', 'enable_disable', '', 1, 0, '启用/禁用管理员', '', '启用/禁用管理员', 0),
(46,5,'roles','set_authority','',1,0,'权限设置','','角色权限设置',0),
(47,46,'roles','set_authority_post','',1,0,'执行角色权限设置','','ajax执行角色权限设置',0),
(48,5,'roles','roles_add','',1,0,'添加角色','','添加角色',0),
(49,48,'roles','roles_add_post','',1,0,'执行添加角色','','ajax执行添加角色',0),
(50,5,'roles','roles_edit','',1,0,'编辑角色','','编辑角色',0),
(51,50,'roles','roles_edit_post','',1,0,'执行编辑角色','','ajax执行编辑角色',0),
(53,5,'roles','roles_delete','',1,0,'删除角色','','删除角色',0),
(55,4,'user','set_authority','',1,0,'权限设置','','权限设置',0),
(56,55,'user','set_authority_post','',1,0,'权限设置','','ajax执行权限设置执行',0);

-- --------------------------------------------------------

--
-- 表的结构 `xk_adm_role`
--

CREATE TABLE `xk_adm_role` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '角色id',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '角色名称',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态 1:正常',
  `remark` varchar(128) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

--
-- 转存表中的数据 `xk_adm_role`
--

INSERT INTO `xk_adm_role` (`id`, `name`, `status`, `remark`, `create_time`, `update_time`) VALUES
(1, '超级管理员', 1, '超级管理员', '2016-01-18 00:00:00', '2016-01-18 01:30:16'),
(2, '系统管理员', 1, '系统管理员-系统管理员-系统管理员-重要的事情说三遍', '2016-01-18 00:00:00', '2016-02-23 01:25:53');

-- --------------------------------------------------------

--
-- 表的结构 `xk_role_access`
--

CREATE TABLE `xk_role_access` (
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色id',
  `m` varchar(32) NOT NULL DEFAULT '' COMMENT '模块',
  `a` varchar(32) NOT NULL DEFAULT '' COMMENT '方法'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限表';

--
-- 转存表中的数据 `xk_role_access`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_user_access`
--

CREATE TABLE `xk_user_access` (
  `user_id` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `m` varchar(32) NOT NULL DEFAULT '' COMMENT '模块',
  `a` varchar(32) NOT NULL DEFAULT '' COMMENT '方法'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户权限表';

--
-- 转存表中的数据 `xk_user_access`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `xk_admin`
--
ALTER TABLE `xk_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `roleid` (`roleid`),
  ADD KEY `state` (`state`);

--
-- Indexes for table `xk_adm_menu`
--
ALTER TABLE `xk_adm_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `model_action` (`model`,`action`);

--
-- Indexes for table `xk_adm_role`
--
ALTER TABLE `xk_adm_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `xk_role_access`
--
ALTER TABLE `xk_role_access`
  ADD UNIQUE KEY `roleid_m_a` (`role_id`,`m`,`a`) USING BTREE;

--
-- Indexes for table `xk_user_access`
--
ALTER TABLE `xk_user_access`
  ADD UNIQUE KEY `userid_m_a` (`user_id`,`m`,`a`) USING BTREE;

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `xk_admin`
--
ALTER TABLE `xk_admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员id', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `xk_adm_menu`
--
ALTER TABLE `xk_adm_menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=57;
--
-- 使用表AUTO_INCREMENT `xk_adm_role`
--
ALTER TABLE `xk_adm_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色id', AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
