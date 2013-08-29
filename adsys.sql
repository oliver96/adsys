-- phpMyAdmin SQL Dump
-- version 4.0.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 08 月 29 日 23:26
-- 服务器版本: 5.5.15
-- PHP 版本: 5.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `adsys`
--

-- --------------------------------------------------------

--
-- 表的结构 `advertisers`
--

DROP TABLE IF EXISTS `advertisers`;
CREATE TABLE IF NOT EXISTS `advertisers` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(50) NOT NULL COMMENT '广告主名称',
  `indus_id` int(11) NOT NULL COMMENT '行业ID',
  `credit` int(11) NOT NULL COMMENT '公司信誉度评级',
  `contact` varchar(20) NOT NULL COMMENT '联系人',
  `email` varchar(100) NOT NULL COMMENT '联系人邮件地件',
  `detail` tinytext NOT NULL COMMENT '联系人详细信息',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `advertisers`
--

INSERT INTO `advertisers` (`id`, `name`, `indus_id`, `credit`, `contact`, `email`, `detail`, `created`) VALUES
(1, '宝马汽车有限公司', 1, 5, '', '', '', '2013-08-01 02:44:02');

-- --------------------------------------------------------

--
-- 表的结构 `advertisings`
--

DROP TABLE IF EXISTS `advertisings`;
CREATE TABLE IF NOT EXISTS `advertisings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '广告名称',
  `adv_id` int(11) NOT NULL,
  `tpl_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `optimize` int(11) NOT NULL COMMENT '是否优化素材显示',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `advertisings`
--

INSERT INTO `advertisings` (`id`, `name`, `adv_id`, `tpl_id`, `size_id`, `optimize`, `created`) VALUES
(1, 'TEST01', 1, 4, 3, 1, '2013-08-29 02:58:50'),
(2, 'TEST02', 1, 3, 2, 1, '2013-08-29 13:40:56');

-- --------------------------------------------------------

--
-- 表的结构 `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `tpl_id` int(11) DEFAULT NULL,
  `schdl_id` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `industries`
--

DROP TABLE IF EXISTS `industries`;
CREATE TABLE IF NOT EXISTS `industries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `industries`
--

INSERT INTO `industries` (`id`, `name`) VALUES
(1, '汽车'),
(2, '生活消费品'),
(3, '娱乐和媒体'),
(4, '金融服务'),
(5, '政府/非政府利润'),
(6, '零售'),
(7, '服务'),
(8, '无线电通讯'),
(9, '旅行/医疗'),
(10, '其他工业/电子产业');

-- --------------------------------------------------------

--
-- 表的结构 `materials`
--

DROP TABLE IF EXISTS `materials`;
CREATE TABLE IF NOT EXISTS `materials` (
  `id` int(11) NOT NULL COMMENT '素材编号',
  `name` varchar(50) NOT NULL COMMENT '素材名称',
  `adv_id` int(11) NOT NULL COMMENT '关联广告主ID',
  `type` enum('image','video') NOT NULL COMMENT '素材类型：image, video',
  `url` varchar(200) NOT NULL COMMENT '素材URL地址',
  `size` int(11) NOT NULL COMMENT '素材大小',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `micros`
--

DROP TABLE IF EXISTS `micros`;
CREATE TABLE IF NOT EXISTS `micros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT '宏变量',
  `name` varchar(50) NOT NULL COMMENT '宏‘变量名称',
  `value_type` enum('input','radio','checkbox','select') NOT NULL COMMENT '变量类型',
  `values` tinytext NOT NULL COMMENT '变量值范围',
  `validate` enum('string','url','email','digit','alpha') NOT NULL,
  `memo` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `micros`
--

INSERT INTO `micros` (`id`, `code`, `name`, `value_type`, `values`, `validate`, `memo`) VALUES
(1, 'IMAGE_URL', '图片URL', 'input', '', 'string', '');

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `adv_id` varchar(45) DEFAULT NULL,
  `item_num` varchar(45) DEFAULT NULL,
  `start` varchar(45) DEFAULT NULL,
  `end` varchar(45) DEFAULT NULL,
  `type` enum('cpd','cpm','cpc','cpa','cps') DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `ads_id` int(11) DEFAULT NULL,
  `start` varchar(45) DEFAULT NULL,
  `end` varchar(45) DEFAULT NULL,
  `type` enum('cpd','cpm','cpc','cpa','cps') DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `sizes`
--

DROP TABLE IF EXISTS `sizes`;
CREATE TABLE IF NOT EXISTS `sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '尺寸编号',
  `name` varchar(50) NOT NULL COMMENT '尺寸名称',
  `type` enum('system','custom') NOT NULL COMMENT '尺寸类型：系统基本尺寸与用户自定义尺寸',
  `width` int(11) NOT NULL COMMENT '宽度',
  `height` int(11) NOT NULL COMMENT '高度',
  `memo` tinytext NOT NULL COMMENT '说明',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `type`, `width`, `height`, `memo`, `created`) VALUES
(1, '首页横幅', 'system', 728, 90, '', '2013-08-29 02:00:41'),
(2, '横幅', 'system', 468, 60, '', '2013-08-29 02:00:41'),
(3, '半横幅', 'system', 234, 60, '', '2013-08-29 02:02:45'),
(4, '摩天大楼', 'system', 120, 600, '', '2013-08-29 02:02:45'),
(5, '宽幅摩天大楼', 'system', 160, 600, '', '2013-08-29 02:04:27'),
(6, '正方形', 'system', 200, 200, '', '2013-08-29 02:04:27'),
(7, '大正方形', 'system', 250, 250, '', '2013-08-29 02:05:41'),
(8, '矩形', 'system', 180, 150, '', '2013-08-29 02:05:41'),
(9, '大矩形', 'system', 300, 250, '', '2013-08-29 02:07:25'),
(10, '特大矩形', 'system', 336, 280, '', '2013-08-29 02:07:25'),
(11, '竖幅', 'system', 120, 240, '', '2013-08-29 02:08:04');

-- --------------------------------------------------------

--
-- 表的结构 `slots`
--

DROP TABLE IF EXISTS `slots`;
CREATE TABLE IF NOT EXISTS `slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `is_deployed` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '创意模板名称',
  `type` enum('system','custom') NOT NULL COMMENT '模板类型',
  `mat_types` varchar(50) NOT NULL COMMENT '允许的素材类型',
  `code` tinytext NOT NULL COMMENT '创意模板代码',
  `extjs` varchar(200) NOT NULL COMMENT '第三方js扩展库',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `templates`
--

INSERT INTO `templates` (`id`, `name`, `type`, `mat_types`, `code`, `extjs`) VALUES
(1, '文字链', 'system', 'text,image', 'var linkTitle = "[LINK_TITLE]";\nvar linkSubTitle = "[LINK_SUB_TITLE]";\nvar linkText = "[LINK_TEXT]";', ''),
(2, '对联', 'system', 'image,video', 'var image1 = "";var image2 = "";', ''),
(3, '浮层', 'system', 'image,video', 'var float = "[FLOAT]";\r\nvar image = "[IMAGE_URL]";', 'www.adsys.com/float.js'),
(4, '视频', 'system', 'image,video', 'var vedio = '''';\nconsole.log(vedio);', 'www.test.com/a.js');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
