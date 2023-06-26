-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 11, 2011 at 09:16 AM
-- Server version: 5.1.58
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `smswebtool`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `ci_sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `token` varchar(256) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `token`, `date_created`, `status`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', NULL, '2009-07-02 23:19:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `time`) VALUES
(1, '10.7.4.155', '2011-10-04 01:09:02'),
(2, '10.7.4.155', '2011-10-04 01:09:40'),
(3, '10.7.4.155', '2011-10-05 08:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `data` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `role_id`, `data`) VALUES
(3, 1, 0x613a313a7b733a333a22757269223b613a343a7b693a303b733a31303a222f626f6f6b6d61726b2f223b693a313b733a31343a222f6d6573736167652f736f72742f223b693a323b733a31363a222f6d6573736167652f66696c7465722f223b693a333b733a31343a222f6d6573736167652f726561642f223b7d7d),
(4, 4, 0x613a313a7b733a333a22757269223b613a353a7b693a303b733a31343a222f6d6573736167652f736f72742f223b693a313b733a31363a222f6d6573736167652f66696c7465722f223b693a323b733a31343a222f6d6573736167652f726561642f223b693a333b733a32323a222f616c6961735f736572766963652f7365617263682f223b693a343b733a32303a222f616c6961735f736572766963652f726561642f223b7d7d);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `parent_id`, `name`) VALUES
(1, 0, 'User'),
(2, 0, 'Admin'),
(4, 0, 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `username` varchar(25) COLLATE utf8_bin NOT NULL,
  `password` varchar(34) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `newpass` varchar(34) COLLATE utf8_bin DEFAULT NULL,
  `newpass_key` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `newpass_time` datetime DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `username`, `password`, `email`, `banned`, `ban_reason`, `newpass`, `newpass_key`, `newpass_time`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 2, 'admin', '$1$mF/Y3CBd$mefaA0Q4oUBNyf1XMAnSP1', 'admin@localhost.com', 0, NULL, NULL, NULL, NULL, '10.7.4.74', '2011-10-10 05:36:22', '2008-11-30 04:56:32', '2011-10-10 05:36:22'),
(2, 1, 'user', '$1$.cB2yUyc$DqSa7H2rrKBPydkZSo6qJ0', 'user@localhost.com', 0, NULL, NULL, NULL, NULL, '192.168.0.25', '2009-07-15 16:57:05', '2008-12-01 14:01:53', '2009-07-15 05:57:05'),
(4, 4, 'testing', '$1$eqK2/czx$3hqYpYVwHL0wTMSkwnefm.', 'testing@localhost.com', 0, NULL, NULL, NULL, NULL, '192.168.0.25', '2009-07-15 16:14:27', '2009-07-14 17:24:18', '2009-07-15 05:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE IF NOT EXISTS `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_autologin`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE IF NOT EXISTS `user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `user_id`, `country`, `website`) VALUES
(1, 1, NULL, NULL),
(2, 3, NULL, NULL),
(3, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_temp`
--

CREATE TABLE IF NOT EXISTS `user_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(34) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activation_key` varchar(50) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_temp`
--


-- --------------------------------------------------------

--
-- Table structure for table `wp_group`
--

CREATE TABLE IF NOT EXISTS `wp_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) DEFAULT NULL,
  `group_desc` varchar(30) DEFAULT NULL,
  `group_menu` varchar(100) DEFAULT NULL,
  `status` enum('1','0') DEFAULT '1' COMMENT '1->active; 0->inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `wp_group`
--

INSERT INTO `wp_group` (`id`, `group_name`, `group_desc`, `group_menu`, `status`) VALUES
(1, 'Super Admin', 'Super Administrator', '1,15,16,3,13,14,17', '1'),
(2, 'Admin', 'Administrator', '1,15,16,3,13,17', '1'),
(3, 'Content', 'Content', '1,15,16', '1'),
(4, 'Tester', 'Tester', '1,2,9', '1');

-- --------------------------------------------------------

--
-- Table structure for table `wp_menu`
--

CREATE TABLE IF NOT EXISTS `wp_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(30) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1->active; 0->inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `wp_menu`
--

INSERT INTO `wp_menu` (`id`, `menu`, `parent`, `link`, `sort`, `status`) VALUES
(1, 'Wap Creator', 0, '#', 1, '1'),
(3, 'Tools', 0, '#', 3, '1'),
(13, 'Manage Group', 3, 'acl/group', 1, '1'),
(14, 'Manage User', 3, 'acl/user', 2, '1'),
(15, 'Wap Service', 1, 'wap/service', 1, '1'),
(16, 'Wap Content', 1, 'wap/content', 2, '1'),
(17, 'Wap Subscription', 1, 'wap/subscription', 3, '1');

-- --------------------------------------------------------

--
-- Table structure for table `wp_users`
--

CREATE TABLE IF NOT EXISTS `wp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `u_group` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('1','0') DEFAULT '1' COMMENT '1->active; 0->inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `wp_users`
--

INSERT INTO `wp_users` (`id`, `username`, `password`, `u_group`, `created`, `modified`, `status`) VALUES
(1, 'admin', '5d41402abc4b2a76b9719d911017c592', 1, '2010-07-21 22:45:07', '2011-09-29 01:32:33', '1'),
(2, 'dhanyalvian', '85954445e1ff2dfb2c1b91ec055c438a', 2, '2011-07-20 18:34:08', '2011-09-30 16:05:22', '1'),
(3, 'gleen', '5d41402abc4b2a76b9719d911017c592', 2, '2011-07-20 18:34:46', '2011-07-22 04:53:42', '1'),
(4, 'letitia', '5d41402abc4b2a76b9719d911017c592', 3, '2011-07-20 18:35:10', '2011-07-26 03:39:55', '1'),
(5, 'indra', '37ddcc8396001f769e8ec3f20ca90f9c', 2, '2011-07-22 00:00:00', '2011-07-22 06:30:23', '1'),
(6, 'Testu', 'ef20c44fb65029e42b45891f1877bd78', 3, '0000-00-00 00:00:00', '2011-09-29 01:47:16', '0'),
(8, 'Testa', '827ccb0eea8a706c4c34a16891f84e7b', 3, '0000-00-00 00:00:00', '2011-07-29 00:07:58', '0'),
(9, 'Testerr', '76d80224611fc919a5d54f0ff9fba446', 4, '2011-08-01 01:17:21', '2011-09-29 01:46:32', '0'),
(10, 'keroro', '202cb962ac59075b964b07152d234b70', 2, '2011-08-01 05:41:54', '2011-09-29 01:46:28', '0'),
(11, 'gunsou', '962012d09b8170d912f0669f6d7d9d07', 3, '2011-08-01 23:54:04', '2011-09-29 01:46:25', '0'),
(12, 'Testar', '912ec803b2ce49e4a541068d495ab570', 4, '2011-08-01 23:54:24', '2011-09-29 01:46:22', '0'),
(13, 'f117', '912ec803b2ce49e4a541068d495ab570', 3, '2011-08-01 23:54:51', '2011-09-29 01:47:22', '0'),
(14, 'cek', '912ec803b2ce49e4a541068d495ab570', 4, '2011-08-01 23:55:09', '2011-09-29 00:44:40', '0'),
(15, 'qwer', '30e8f073f388469e0193300623691a36', 3, '2011-08-01 23:55:32', '2011-09-29 00:44:37', '0'),
(16, 'asdf', '912ec803b2ce49e4a541068d495ab570', 4, '2011-08-01 23:55:45', '2011-09-29 00:44:43', '0'),
(17, 'kururu', '30932975105ea3b4fa5d4eeb35acbbaa', 4, '2011-08-05 04:12:22', '2011-09-29 01:46:16', '0'),
(18, 'test1', '5a105e8b9d40e1329780d62ea2265d8a', 4, '2011-09-29 01:45:04', '2011-09-29 01:45:04', '1'),
(19, 'content1', '3bfd2e7a3ae9864cf157a241022bc43b', 3, '2011-09-29 01:47:09', '2011-09-29 22:50:11', '1');

