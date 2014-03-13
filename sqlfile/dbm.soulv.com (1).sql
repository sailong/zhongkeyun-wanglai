-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- 主机: dbm.soulv.com
-- 生成日期: 2014 年 03 月 10 日 17:26
-- 服务器版本: 5.1.36-log
-- PHP 版本: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hzwcity`
--
CREATE DATABASE `hzwcity` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `hzwcity`;

-- --------------------------------------------------------

--
-- 表的结构 `activity`
--

DROP TABLE IF EXISTS `activity`;
CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_mid` int(10) unsigned NOT NULL COMMENT '活动创办人',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `subject` varchar(200) NOT NULL COMMENT '主题',
  `type` varchar(10) NOT NULL COMMENT '活动类型',
  `province` tinyint(2) NOT NULL COMMENT '省/市',
  `area` smallint(3) NOT NULL COMMENT '市/区',
  `district` varchar(50) NOT NULL COMMENT '详细地址',
  `begin_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `max` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动人数限制',
  `sponer` varchar(50) NOT NULL COMMENT '举办方',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态1审核中2通过3拒绝0默认',
  `detail` varchar(1000) NOT NULL COMMENT '活动详情',
  `verify` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '活动报名是否需要审核1不审核2审核',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `shares` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享次数',
  PRIMARY KEY (`id`),
  KEY `create_mid` (`create_mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- 表的结构 `activity_member_rel`
--

DROP TABLE IF EXISTS `activity_member_rel`;
CREATE TABLE IF NOT EXISTS `activity_member_rel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` int(10) unsigned NOT NULL COMMENT '活动id',
  `member_id` int(10) unsigned NOT NULL COMMENT '报名用户id',
  `canceled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否取消1报名2已取消',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '取消时间',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '审核状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '报名时间',
  `verify_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核时间',
  PRIMARY KEY (`id`),
  KEY `activity_id` (`activity_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- 表的结构 `card`
--

DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_mid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送人,已登录用户id',
  `from_user` varchar(50) NOT NULL COMMENT '发送人',
  `to_user` varchar(50) NOT NULL COMMENT '接收人',
  `content` varchar(500) NOT NULL COMMENT '贺卡内容',
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '贺卡分类id',
  `sub_cid` int(10) unsigned NOT NULL DEFAULT '0',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间',
  `share_counts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享次数',
  `click_counts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击创建次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='贺卡发送日志' AUTO_INCREMENT=10141 ;

-- --------------------------------------------------------

--
-- 表的结构 `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `description` varchar(100) NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `privacy` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '隐私设置',
  `create_mid` int(10) unsigned NOT NULL COMMENT '创建人',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '编辑时间',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除',
  `share_counts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享次数',
  `uv_counts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被不同的用户查看次数',
  `pv_counts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击过多少次',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是系统默认的群',
  PRIMARY KEY (`id`),
  KEY `ind_create_mid` (`create_mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- 表的结构 `contacts_member_rel`
--

DROP TABLE IF EXISTS `contacts_member_rel`;
CREATE TABLE IF NOT EXISTS `contacts_member_rel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `contacts_id` int(10) unsigned NOT NULL COMMENT '通讯录id',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '申请状态',
  `apply_time` int(10) unsigned NOT NULL COMMENT '申请时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审状态变更间',
  PRIMARY KEY (`id`),
  KEY `ind_member_id` (`member_id`),
  KEY `ind_contacts_id` (`contacts_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=318 ;

-- --------------------------------------------------------

--
-- 表的结构 `district`
--

DROP TABLE IF EXISTS `district`;
CREATE TABLE IF NOT EXISTS `district` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(765) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  `usetype` tinyint(1) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `displayorder` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `extra`
--

DROP TABLE IF EXISTS `extra`;
CREATE TABLE IF NOT EXISTS `extra` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型,活动或者群',
  `object_id` int(10) unsigned NOT NULL COMMENT '对象id,活动id或群id',
  `email` varchar(100) NOT NULL COMMENT '多个邮箱',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL COMMENT '反馈人id',
  `member_name` varchar(20) NOT NULL COMMENT '反馈人名字',
  `content` varchar(500) NOT NULL COMMENT '反馈内容',
  `create_time` int(10) unsigned NOT NULL COMMENT '反馈时间',
  `resolved` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否解决',
  `operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id',
  `operator_name` varchar(20) NOT NULL COMMENT '操作人用户名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `follow`
--

DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `follow_mid` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 表示 取消关注',
  `follow_at` int(11) NOT NULL COMMENT '关注时间',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是最新的关注0不是1是',
  PRIMARY KEY (`id`),
  KEY `mfid` (`mid`,`follow_mid`),
  KEY `follow_mid` (`follow_mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=127 ;

-- --------------------------------------------------------

--
-- 表的结构 `grant_number_log`
--

DROP TABLE IF EXISTS `grant_number_log`;
CREATE TABLE IF NOT EXISTS `grant_number_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '号码发放记录表',
  `member_id` int(11) NOT NULL,
  `number` char(6) NOT NULL,
  `grant_way` tinyint(1) NOT NULL DEFAULT '1' COMMENT '发放形式 1 系统自动 2 手动',
  `grant_admin_id` mediumint(9) NOT NULL DEFAULT '0' COMMENT '发放人id',
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- 表的结构 `info`
--

DROP TABLE IF EXISTS `info`;
CREATE TABLE IF NOT EXISTS `info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  `uv` int(10) unsigned NOT NULL DEFAULT '0',
  `new` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `invite_stat`
--

DROP TABLE IF EXISTS `invite_stat`;
CREATE TABLE IF NOT EXISTS `invite_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '邀请统计表（号码发放新增）',
  `member_id` int(11) NOT NULL COMMENT '用户id',
  `all_counts` mediumint(9) NOT NULL DEFAULT '1' COMMENT '邀请的总人数',
  `left_counts` mediumint(9) NOT NULL DEFAULT '0' COMMENT '发号后的剩余数量',
  `activity_id` tinyint(4) NOT NULL DEFAULT '1' COMMENT '活动id',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_confirm_number` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mid` (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_openid` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `mobile` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL,
  `initial` char(1) NOT NULL COMMENT '名字首字母',
  `position` varchar(100) NOT NULL DEFAULT '' COMMENT '职位',
  `company` varchar(100) NOT NULL DEFAULT '' COMMENT '公司',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '地址',
  `company_url` varchar(100) NOT NULL DEFAULT '' COMMENT '公司微站',
  `main_business` varchar(300) NOT NULL DEFAULT '' COMMENT '主营业务',
  `supply` varchar(300) NOT NULL DEFAULT '' COMMENT '供给',
  `demand` varchar(300) NOT NULL DEFAULT '' COMMENT '需求',
  `views` int(11) NOT NULL DEFAULT '0' COMMENT '浏览数',
  `show_item` varchar(300) NOT NULL DEFAULT '',
  `weixin` varchar(100) DEFAULT NULL,
  `yixin` varchar(100) DEFAULT NULL,
  `laiwang` varchar(100) DEFAULT NULL,
  `qq` varchar(100) DEFAULT NULL,
  `hobby` varchar(500) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `from` tinyint(1) DEFAULT '1',
  `share_counts` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(100) DEFAULT '',
  `profile` varchar(500) DEFAULT '',
  `social_position` varchar(300) DEFAULT '',
  `is_vip` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL DEFAULT '0',
  `subscribe` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否关注，1已关注，2已取消关注',
  `wanglai_number` varchar(20) NOT NULL DEFAULT '',
  `wanglai_number_grade` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生日',
  `is_qiye` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `weixin_openid` (`weixin_openid`),
  KEY `views` (`views`),
  KEY `mobile` (`mobile`),
  KEY `email` (`email`),
  KEY `wanglai_number` (`wanglai_number`),
  KEY `initial` (`initial`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=174376 ;

-- --------------------------------------------------------

--
-- 表的结构 `member_extend`
--

DROP TABLE IF EXISTS `member_extend`;
CREATE TABLE IF NOT EXISTS `member_extend` (
  `member_id` int(10) unsigned NOT NULL COMMENT '用户id,来自member表',
  `weixin_openid` varchar(50) NOT NULL COMMENT '微信openid',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称(微信)',
  `headimgurl` varchar(100) NOT NULL COMMENT '用户头像(微信)',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户性别(微信)',
  `province` varchar(10) NOT NULL COMMENT '省(微信)',
  `city` varchar(10) NOT NULL COMMENT '市(微信)',
  `country` varchar(10) NOT NULL COMMENT '国家(微信)',
  `add_time` int(10) NOT NULL COMMENT '增加时间',
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `message_log`
--

DROP TABLE IF EXISTS `message_log`;
CREATE TABLE IF NOT EXISTS `message_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '短信发送日志表',
  `member_id` int(11) NOT NULL DEFAULT '0',
  `mobile` char(11) NOT NULL,
  `action` tinyint(1) NOT NULL COMMENT '1 注册 2 修改密码',
  `content` char(6) NOT NULL DEFAULT '' COMMENT '内容',
  `created_at` int(11) NOT NULL,
  `send_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mobile` (`mobile`,`action`,`send_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

-- --------------------------------------------------------

--
-- 表的结构 `number`
--

DROP TABLE IF EXISTS `number`;
CREATE TABLE IF NOT EXISTS `number` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '号码表',
  `number` char(6) NOT NULL COMMENT '往来号',
  `length` tinyint(1) NOT NULL COMMENT '号码长度',
  `number_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0 可发放   1 已经发放 2 锁定',
  `is_keep` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是保留号 1是 0 否',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1110001 ;

-- --------------------------------------------------------

--
-- 表的结构 `partner`
--

DROP TABLE IF EXISTS `partner`;
CREATE TABLE IF NOT EXISTS `partner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `new_uid` int(11) NOT NULL,
  `from_uid` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是最新的0不是1是',
  PRIMARY KEY (`id`),
  KEY `from_uid` (`from_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3592 ;

-- --------------------------------------------------------

--
-- 表的结构 `sign_activity`
--

DROP TABLE IF EXISTS `sign_activity`;
CREATE TABLE IF NOT EXISTS `sign_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `share_counts` int(10) unsigned NOT NULL,
  `pv_counts` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(10) NOT NULL COMMENT '标题',
  `content` varchar(2000) NOT NULL COMMENT '内容',
  `img` varchar(50) NOT NULL COMMENT '分享的图片链接',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `signature`
--

DROP TABLE IF EXISTS `signature`;
CREATE TABLE IF NOT EXISTS `signature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `member_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `create_time` int(10) unsigned NOT NULL COMMENT '签名时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10029 ;

-- --------------------------------------------------------

--
-- 表的结构 `stat`
--

DROP TABLE IF EXISTS `stat`;
CREATE TABLE IF NOT EXISTS `stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '名片统计表',
  `member_id` int(11) unsigned NOT NULL,
  `pv_counts` int(11) NOT NULL DEFAULT '0',
  `favorite_counts` int(11) NOT NULL DEFAULT '0' COMMENT '收藏数',
  `last_login_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_follow_at` int(11) DEFAULT '0',
  `last_partner_at` int(11) DEFAULT '0',
  `last_update_at` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`),
  KEY `last_login_at` (`last_login_at`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24270 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_profiles`
--

DROP TABLE IF EXISTS `tbl_profiles`;
CREATE TABLE IF NOT EXISTS `tbl_profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_profiles_fields`
--

DROP TABLE IF EXISTS `tbl_profiles_fields`;
CREATE TABLE IF NOT EXISTS `tbl_profiles_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `field_type` varchar(50) NOT NULL DEFAULT '',
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` text,
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` text,
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联的名片用户id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  KEY `user_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `tmp_research`
--

DROP TABLE IF EXISTS `tmp_research`;
CREATE TABLE IF NOT EXISTS `tmp_research` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `company` varchar(50) NOT NULL COMMENT '企业名称',
  `position` varchar(50) NOT NULL COMMENT '姓名职位',
  `type` varchar(100) NOT NULL COMMENT '行业类型',
  `products` varchar(50) NOT NULL COMMENT '产品服务',
  `employee` int(10) NOT NULL DEFAULT '0' COMMENT '员工数',
  `stage` varchar(100) NOT NULL COMMENT '发展阶段',
  `income` varchar(50) NOT NULL COMMENT '销售收入',
  `profile_ratio` varchar(50) NOT NULL COMMENT '毛利率',
  `growth_ratio` varchar(50) NOT NULL COMMENT '增长率',
  `capacity` varchar(50) NOT NULL COMMENT '市场容量',
  `cost` varchar(50) NOT NULL COMMENT '成本组成',
  `cost_ratio` varchar(100) NOT NULL COMMENT '成本比例',
  `information` varchar(200) NOT NULL COMMENT '采用的信息系统',
  `web` varchar(5) NOT NULL COMMENT '是否有网站',
  `function` varchar(50) NOT NULL COMMENT '网站主要功能',
  `sale_channel` varchar(40) NOT NULL COMMENT '销售渠道',
  `sale_channel_ratio` varchar(100) NOT NULL COMMENT '销售渠道各比例',
  `promotion_channel` varchar(50) NOT NULL COMMENT '推广渠道',
  `promotion_channel_ratio` varchar(100) NOT NULL COMMENT '推广渠道比例',
  `internet` varchar(100) NOT NULL COMMENT '互联网思维',
  `impact` varchar(100) NOT NULL COMMENT '影响',
  `change` varchar(100) NOT NULL COMMENT '转型升级',
  `advantage` varchar(100) NOT NULL COMMENT '优势',
  `disadvantage` varchar(100) NOT NULL COMMENT '劣势',
  `question` varchar(200) NOT NULL COMMENT '问题',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户表',
  `nickname` char(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` char(32) NOT NULL COMMENT '密码',
  `register_time` datetime NOT NULL COMMENT '注册时间',
  `register_ip` char(32) DEFAULT NULL COMMENT '注册ip',
  `lastlogin_time` datetime NOT NULL COMMENT '最后登录时间',
  `lastlogin_ip` char(32) DEFAULT NULL COMMENT '最后登录ip',
  `account_status` int(1) NOT NULL DEFAULT '1' COMMENT '账号状态 0 屏蔽或删除  1 正常',
  `role_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '角色 0 普通用户 ',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL DEFAULT '0',
  `purview` varchar(500) NOT NULL DEFAULT '' COMMENT '权限',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `nickname` (`nickname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `view_log`
--

DROP TABLE IF EXISTS `view_log`;
CREATE TABLE IF NOT EXISTS `view_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '查看人的id',
  `viewd_member_id` int(11) NOT NULL COMMENT '被查看人的id',
  `created_at` int(11) NOT NULL,
  `last_viewd_at` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`,`viewd_member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4062 ;

--
-- 限制导出的表
--

--
-- 限制表 `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  ADD CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
