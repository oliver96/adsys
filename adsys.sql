-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 12, 2013 at 04:02 PM
-- Server version: 5.5.32-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `adsys`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisers`
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
-- Dumping data for table `advertisers`
--

INSERT INTO `advertisers` (`id`, `name`, `indus_id`, `credit`, `contact`, `email`, `detail`, `created`) VALUES
(1, '宝马汽车有限公司', 1, 5, '', '', '', '2013-08-01 02:44:02');

-- --------------------------------------------------------

--
-- Table structure for table `advertisings`
--

DROP TABLE IF EXISTS `advertisings`;
CREATE TABLE IF NOT EXISTS `advertisings` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '广告名称',
  `adv_id` int(11) NOT NULL,
  `tpl_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `optimize` int(11) NOT NULL COMMENT '是否优化素材显示',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `industries`
--

DROP TABLE IF EXISTS `industries`;
CREATE TABLE IF NOT EXISTS `industries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `industries`
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
-- Table structure for table `materials`
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
-- Table structure for table `micros`
--

DROP TABLE IF EXISTS `micros`;
CREATE TABLE IF NOT EXISTS `micros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT '宏变量',
  `name` varchar(50) NOT NULL COMMENT '宏‘变量名称',
  `value_type` enum('input','radio','checkbox','select') NOT NULL COMMENT '变量类型',
  `values` tinytext NOT NULL COMMENT '变量值范围',
  `validate` enum('URL','EMAIL','DIGIT','NONE') NOT NULL,
  `memo` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `micros`
--

INSERT INTO `micros` (`id`, `code`, `name`, `value_type`, `values`, `validate`, `memo`) VALUES
(1, 'IMAGE_URL', '图片URL', '', '', 'URL', '');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `type`, `width`, `height`, `memo`, `created`) VALUES
(1, '250x250', 'system', 250, 250, '', '2013-08-01 04:07:26'),
(2, '468x60', 'system', 468, 60, '', '2013-08-01 04:19:53');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '创意模板名称',
  `mat_types` varchar(50) NOT NULL COMMENT '允许的素材类型',
  `code` tinytext NOT NULL COMMENT '创意模板代码',
  `extjs` varchar(200) NOT NULL COMMENT '第三方js扩展库',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `name`, `mat_types`, `code`, `extjs`) VALUES
(1, '文字链', 'text,image', 'var linkTitle = "[LINK_TITLE]";\nvar linkSubTitle = "[LINK_SUB_TITLE]";\nvar linkText = "[LINK_TEXT]";', ''),
(2, '对联', 'image,video', 'var image1 = "";var image2 = "";', ''),
(3, '浮层', 'image,video', 'var float = "";', 'www.adsys.com/float.js');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
