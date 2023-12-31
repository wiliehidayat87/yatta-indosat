-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 17, 2012 at 12:28 PM
-- Server version: 5.1.61
-- PHP Version: 5.3.5-1ubuntu7.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xmp_tools_base`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('af84ef5f160546b194561f05f5f6e7d8', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:11.0) G', 1334639242, 'a:11:{s:3:"uid";s:1:"1";s:8:"userName";s:5:"admin";s:7:"groupId";s:1:"1";s:9:"groupName";s:11:"Super Admin";s:9:"groupMenu";s:83:"23,24,30,31,25,32,33,34,35,36,50,51,37,39,40,26,41,42,43,44,45,52,27,46,47,48,28,29";s:10:"methodList";a:123:{i:0;a:2:{i:0;s:2:"48";i:1;s:5:"index";}i:1;a:2:{i:0;s:2:"48";i:1;s:21:"ajaxGetControllerList";}i:2;a:2:{i:0;s:2:"48";i:1;s:13:"getParentList";}i:3;a:2:{i:0;s:2:"48";i:1;s:9:"getStatus";}i:4;a:2:{i:0;s:2:"48";i:1;s:18:"ajaxSaveController";}i:5;a:2:{i:0;s:2:"48";i:1;s:18:"ajaxEditController";}i:6;a:2:{i:0;s:2:"48";i:1;s:20:"ajaxUpdateController";}i:7;a:2:{i:0;s:2:"48";i:1;s:20:"ajaxDeleteController";}i:8;a:2:{i:0;s:2:"46";i:1;s:5:"index";}i:9;a:2:{i:0;s:2:"46";i:1;s:16:"ajaxGetGroupList";}i:10;a:2:{i:0;s:2:"46";i:1;s:12:"getCheckMenu";}i:11;a:2:{i:0;s:2:"46";i:1;s:13:"ajaxSaveGroup";}i:12;a:2:{i:0;s:2:"46";i:1;s:13:"ajaxEditGroup";}i:13;a:2:{i:0;s:2:"46";i:1;s:15:"ajaxUpdateGroup";}i:14;a:2:{i:0;s:2:"46";i:1;s:15:"ajaxDeleteGroup";}i:15;a:2:{i:0;s:2:"49";i:1;s:5:"index";}i:16;a:2:{i:0;s:2:"49";i:1;s:22:"ajaxGetMethodGroupList";}i:17;a:2:{i:0;s:2:"49";i:1;s:17:"getControllerList";}i:18;a:2:{i:0;s:2:"49";i:1;s:19:"ajaxScanMethodGroup";}i:19;a:2:{i:0;s:2:"49";i:1;s:17:"getControllerLink";}i:20;a:2:{i:0;s:2:"49";i:1;s:11:"getFilePath";}i:21;a:2:{i:0;s:2:"49";i:1;s:19:"getFileClassMethods";}i:22;a:2:{i:0;s:2:"49";i:1;s:21:"ajaxActiveMethodGroup";}i:23;a:2:{i:0;s:2:"49";i:1;s:23:"ajaxInactiveMethodGroup";}i:24;a:2:{i:0;s:2:"49";i:1;s:14:"scanController";}i:25;a:2:{i:0;s:2:"47";i:1;s:5:"index";}i:26;a:2:{i:0;s:2:"47";i:1;s:10:"changepass";}i:27;a:2:{i:0;s:2:"47";i:1;s:9:"cpsuccess";}i:28;a:2:{i:0;s:2:"47";i:1;s:13:"changeprofile";}i:29;a:2:{i:0;s:2:"47";i:1;s:15:"ajaxGetUserList";}i:30;a:2:{i:0;s:2:"47";i:1;s:12:"getGroupList";}i:31;a:2:{i:0;s:2:"47";i:1;s:14:"ajaxAddNewUser";}i:32;a:2:{i:0;s:2:"47";i:1;s:12:"ajaxEditUser";}i:33;a:2:{i:0;s:2:"47";i:1;s:14:"ajaxUpdateUser";}i:34;a:2:{i:0;s:2:"47";i:1;s:14:"ajaxDeleteUser";}i:35;a:2:{i:0;s:2:"33";i:1;s:5:"index";}i:36;a:2:{i:0;s:2:"33";i:1;s:14:"ajaxGetAdnList";}i:37;a:2:{i:0;s:2:"33";i:1;s:10:"ajaxAddAdn";}i:38;a:2:{i:0;s:2:"33";i:1;s:13:"ajaxUpdateAdn";}i:39;a:2:{i:0;s:2:"33";i:1;s:11:"ajaxEditAdn";}i:40;a:2:{i:0;s:2:"33";i:1;s:13:"ajaxDeleteAdn";}i:41;a:2:{i:0;s:2:"34";i:1;s:5:"index";}i:42;a:2:{i:0;s:2:"34";i:1;s:19:"ajaxGetChargingList";}i:43;a:2:{i:0;s:2:"34";i:1;s:15:"getOperatorList";}i:44;a:2:{i:0;s:2:"34";i:1;s:10:"getAdnList";}i:45;a:2:{i:0;s:2:"34";i:1;s:16:"ajaxSaveCharging";}i:46;a:2:{i:0;s:2:"34";i:1;s:16:"ajaxEditCharging";}i:47;a:2:{i:0;s:2:"34";i:1;s:18:"ajaxUpdateCharging";}i:48;a:2:{i:0;s:2:"34";i:1;s:18:"ajaxDeleteCharging";}i:49;a:2:{i:0;s:2:"35";i:1;s:5:"index";}i:50;a:2:{i:0;s:2:"35";i:1;s:19:"ajaxGetOperatorList";}i:51;a:2:{i:0;s:2:"35";i:1;s:15:"ajaxAddOperator";}i:52;a:2:{i:0;s:2:"35";i:1;s:18:"ajaxUpdateOperator";}i:53;a:2:{i:0;s:2:"35";i:1;s:16:"ajaxEditOperator";}i:54;a:2:{i:0;s:2:"35";i:1;s:18:"ajaxDeleteOperator";}i:55;a:2:{i:0;s:2:"37";i:1;s:5:"index";}i:56;a:2:{i:0;s:2:"37";i:1;s:18:"ajaxGetCreatorList";}i:57;a:2:{i:0;s:2:"37";i:1;s:15:"getOperatorList";}i:58;a:2:{i:0;s:2:"37";i:1;s:18:"ajaxGetServiceList";}i:59;a:2:{i:0;s:2:"37";i:1;s:13:"ajaxGetParams";}i:60;a:2:{i:0;s:2:"37";i:1;s:13:"createKeyword";}i:61;a:2:{i:0;s:2:"37";i:1;s:14:"getOperatorTab";}i:62;a:2:{i:0;s:2:"50";i:1;s:5:"index";}i:63;a:2:{i:0;s:2:"50";i:1;s:24:"ajaxGetCustomHandlerList";}i:64;a:2:{i:0;s:2:"50";i:1;s:20:"ajaxAddCustomHandler";}i:65;a:2:{i:0;s:2:"50";i:1;s:23:"ajaxUpdateCustomHandler";}i:66;a:2:{i:0;s:2:"50";i:1;s:21:"ajaxEditCustomHandler";}i:67;a:2:{i:0;s:2:"50";i:1;s:23:"ajaxDeleteCustomHandler";}i:68;a:2:{i:0;s:2:"51";i:1;s:5:"index";}i:69;a:2:{i:0;s:2:"51";i:1;s:17:"ajaxGetModuleList";}i:70;a:2:{i:0;s:2:"51";i:1;s:13:"ajaxAddModule";}i:71;a:2:{i:0;s:2:"51";i:1;s:16:"ajaxUpdateModule";}i:72;a:2:{i:0;s:2:"51";i:1;s:14:"ajaxEditModule";}i:73;a:2:{i:0;s:2:"51";i:1;s:16:"ajaxDeleteModule";}i:74;a:2:{i:0;s:2:"36";i:1;s:5:"index";}i:75;a:2:{i:0;s:2:"36";i:1;s:18:"ajaxGetServiceList";}i:76;a:2:{i:0;s:2:"36";i:1;s:17:"ajaxAddNewService";}i:77;a:2:{i:0;s:2:"36";i:1;s:17:"ajaxUpdateService";}i:78;a:2:{i:0;s:2:"36";i:1;s:15:"ajaxEditService";}i:79;a:2:{i:0;s:2:"36";i:1;s:6:"getAdn";}i:80;a:2:{i:0;s:2:"37";i:1;s:13:"getModuleList";}i:81;a:2:{i:0;s:2:"37";i:1;s:12:"createNewTab";}i:82;a:2:{i:0;s:2:"37";i:1;s:7:"formTab";}i:83;a:2:{i:0;s:2:"37";i:1;s:15:"getChargingList";}i:84;a:2:{i:0;s:2:"37";i:1;s:14:"ajaxAddKeyword";}i:85;a:2:{i:0;s:2:"37";i:1;s:15:"ajaxEditCreator";}i:86;a:2:{i:0;s:2:"37";i:1;s:17:"ajaxAddNewCreator";}i:87;a:2:{i:0;s:2:"37";i:1;s:17:"ajaxUpdateCreator";}i:88;a:2:{i:0;s:2:"37";i:1;s:14:"getServiceList";}i:89;a:2:{i:0;s:2:"23";i:1;s:5:"index";}i:90;a:2:{i:0;s:2:"23";i:1;s:20:"ajaxGetMOTrafficList";}i:91;a:2:{i:0;s:2:"23";i:1;s:12:"dateToString";}i:92;a:2:{i:0;s:2:"23";i:1;s:15:"getOperatorList";}i:93;a:2:{i:0;s:2:"23";i:1;s:10:"getAdnList";}i:94;a:2:{i:0;s:2:"23";i:1;s:11:"getTypeList";}i:95;a:2:{i:0;s:2:"23";i:1;s:18:"ajaxGetServiceList";}i:96;a:2:{i:0;s:2:"23";i:1;s:14:"getTodayMOList";}i:97;a:2:{i:0;s:2:"23";i:1;s:14:"getTotalMOList";}i:98;a:2:{i:0;s:2:"23";i:1;s:12:"getChartData";}i:99;a:2:{i:0;s:2:"30";i:1;s:5:"index";}i:100;a:2:{i:0;s:2:"30";i:1;s:15:"getHistoryTable";}i:101;a:2:{i:0;s:2:"30";i:1;s:10:"pagination";}i:102;a:2:{i:0;s:2:"31";i:1;s:5:"index";}i:103;a:2:{i:0;s:2:"31";i:1;s:24:"getUserSubscriptionTable";}i:104;a:2:{i:0;s:2:"31";i:1;s:10:"pagination";}i:105;a:2:{i:0;s:2:"31";i:1;s:13:"inactiveCheck";}i:106;a:2:{i:0;s:2:"52";i:1;s:5:"index";}i:107;a:2:{i:0;s:2:"52";i:1;s:19:"ajaxGetChargingList";}i:108;a:2:{i:0;s:2:"52";i:1;s:15:"getOperatorList";}i:109;a:2:{i:0;s:2:"52";i:1;s:10:"getAdnList";}i:110;a:2:{i:0;s:2:"52";i:1;s:16:"ajaxSaveCharging";}i:111;a:2:{i:0;s:2:"52";i:1;s:16:"ajaxEditCharging";}i:112;a:2:{i:0;s:2:"52";i:1;s:18:"ajaxUpdateCharging";}i:113;a:2:{i:0;s:2:"52";i:1;s:18:"ajaxDeleteCharging";}i:114;a:2:{i:0;s:2:"46";i:1;s:12:"has_children";}i:115;a:2:{i:0;s:2:"46";i:1;s:10:"build_menu";}i:116;a:2:{i:0;s:2:"52";i:1;s:18:"ajaxGetControlList";}i:117;a:2:{i:0;s:2:"52";i:1;s:15:"ajaxSaveControl";}i:118;a:2:{i:0;s:2:"52";i:1;s:15:"ajaxEditControl";}i:119;a:2:{i:0;s:2:"52";i:1;s:17:"ajaxUpdateControl";}i:120;a:2:{i:0;s:2:"52";i:1;s:17:"ajaxDeleteControl";}i:121;a:2:{i:0;s:2:"52";i:1;s:7:"getHour";}i:122;a:2:{i:0;s:2:"52";i:1;s:9:"getMinute";}}s:7:"pattern";b:0;s:8:"operator";a:1:{i:0;s:3:"mcp";}s:10:"service_id";s:2:"16";s:12:"service_name";s:4:"FUNN";s:3:"adn";s:4:"9877";}');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) DEFAULT NULL,
  `group_desc` varchar(30) DEFAULT NULL,
  `group_menu` varchar(100) DEFAULT NULL,
  `status` enum('1','0') DEFAULT '1' COMMENT '1->active; 0->inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `group_name`, `group_desc`, `group_menu`, `status`) VALUES
(1, 'Super Admin', 'Super Administrator', '23,24,30,31,25,32,33,34,35,36,50,51,37,39,40,26,41,42,43,44,45,52,27,46,47,48,28,29', '1'),
(2, 'Admin', 'Administrator', '23,28,29', '1'),
(3, 'Content', 'Content', '52,28,29', '1'),
(4, 'Tester', 'Tester', '23,29', '1');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(30) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1->active; 0->inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `menu`, `parent`, `link`, `sort`, `status`) VALUES
(23, 'MO Traffic', 0, 'traffic/mo_traffic', 1, '1'),
(24, 'Customer Service', 0, '#', 2, '1'),
(25, 'Service', 0, '#', 3, '1'),
(26, 'Reports', 0, '#', 4, '1'),
(27, 'Administration', 0, '#', 6, '1'),
(28, 'Change Password', 0, 'acl/changepass', 7, '1'),
(29, 'Logout', 0, 'logout', 8, '1'),
(30, 'History', 24, 'cs/history', 1, '1'),
(31, 'Subscription', 24, 'cs/subscription', 2, '1'),
(32, 'Master Data', 25, '#', 1, '1'),
(33, 'ADN', 32, 'masterdata/adn', 1, '1'),
(34, 'Charging', 32, 'masterdata/charging', 2, '1'),
(35, 'Operator', 32, 'masterdata/operator', 3, '1'),
(36, 'Service', 32, 'masterdata/service', 4, '1'),
(37, 'Creator', 25, 'service/creator', 2, '1'),
(38, 'Custom Handler', 25, '#', 3, '0'),
(39, 'Broadcast', 25, '#', 4, '1'),
(40, 'Quiz', 25, '#', 5, '1'),
(41, 'Internal', 26, '#', 1, '1'),
(42, 'Operator', 41, '#', 1, '1'),
(43, 'Service', 41, '#', 2, '1'),
(44, 'Close Reason', 41, '#', 3, '1'),
(45, 'Partner', 26, '#', 2, '1'),
(46, 'Manage Group', 27, 'acl/group', 1, '1'),
(47, 'Manage User', 27, 'acl/user', 2, '1'),
(48, 'Manage Controller', 27, 'acl/controller', 3, '1'),
(49, 'Manage Method', 27, 'acl/method_group', 4, '0'),
(50, 'Custom Handler', 32, 'masterdata/custom_handler', 5, '1'),
(51, 'Module', 32, 'masterdata/module', 6, '1'),
(52, 'Control PIN', 0, 'pin/control', 5, '1');

-- --------------------------------------------------------

--
-- Table structure for table `methods`
--

CREATE TABLE IF NOT EXISTS `methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_group` int(11) NOT NULL,
  `controller_link` int(11) NOT NULL,
  `method` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL COMMENT '1->active, 0->inactive',
  `permission` char(3) NOT NULL DEFAULT '000' COMMENT 'delete, write, read. 1=active or 0=inactive',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=865 ;

--
-- Dumping data for table `methods`
--

INSERT INTO `methods` (`id`, `u_group`, `controller_link`, `method`, `status`, `permission`) VALUES
(373, 1, 48, 'index', '1', '000'),
(374, 1, 48, 'ajaxGetControllerList', '1', '000'),
(375, 1, 48, 'getParentList', '1', '000'),
(376, 1, 48, 'getStatus', '1', '000'),
(377, 1, 48, 'ajaxSaveController', '1', '000'),
(378, 1, 48, 'ajaxEditController', '1', '000'),
(379, 1, 48, 'ajaxUpdateController', '1', '000'),
(380, 1, 48, 'ajaxDeleteController', '1', '000'),
(381, 1, 46, 'index', '1', '000'),
(382, 1, 46, 'ajaxGetGroupList', '1', '000'),
(383, 1, 46, 'getCheckMenu', '1', '000'),
(384, 1, 46, 'ajaxSaveGroup', '1', '000'),
(385, 1, 46, 'ajaxEditGroup', '1', '000'),
(386, 1, 46, 'ajaxUpdateGroup', '1', '000'),
(387, 1, 46, 'ajaxDeleteGroup', '1', '000'),
(388, 1, 49, 'index', '1', '000'),
(389, 1, 49, 'ajaxGetMethodGroupList', '1', '000'),
(390, 1, 49, 'getControllerList', '1', '000'),
(391, 1, 49, 'ajaxScanMethodGroup', '1', '000'),
(392, 1, 49, 'getControllerLink', '1', '000'),
(393, 1, 49, 'getFilePath', '1', '000'),
(394, 1, 49, 'getFileClassMethods', '1', '000'),
(395, 1, 49, 'ajaxActiveMethodGroup', '1', '000'),
(396, 1, 49, 'ajaxInactiveMethodGroup', '1', '000'),
(397, 1, 49, 'scanController', '1', '000'),
(398, 1, 47, 'index', '1', '000'),
(399, 1, 47, 'changepass', '1', '000'),
(400, 1, 47, 'cpsuccess', '1', '000'),
(401, 1, 47, 'changeprofile', '1', '000'),
(402, 1, 47, 'ajaxGetUserList', '1', '000'),
(403, 1, 47, 'getGroupList', '1', '000'),
(404, 1, 47, 'ajaxAddNewUser', '1', '000'),
(405, 1, 47, 'ajaxEditUser', '1', '000'),
(406, 1, 47, 'ajaxUpdateUser', '1', '000'),
(407, 1, 47, 'ajaxDeleteUser', '1', '000'),
(408, 1, 33, 'index', '1', '000'),
(409, 1, 33, 'ajaxGetAdnList', '1', '000'),
(410, 1, 33, 'ajaxAddAdn', '1', '000'),
(411, 1, 33, 'ajaxUpdateAdn', '1', '000'),
(412, 1, 33, 'ajaxEditAdn', '1', '000'),
(413, 1, 33, 'ajaxDeleteAdn', '1', '000'),
(414, 1, 34, 'index', '1', '000'),
(415, 1, 34, 'ajaxGetChargingList', '1', '000'),
(416, 1, 34, 'getOperatorList', '1', '000'),
(417, 1, 34, 'getAdnList', '1', '000'),
(418, 1, 34, 'ajaxSaveCharging', '1', '000'),
(419, 1, 34, 'ajaxEditCharging', '1', '000'),
(420, 1, 34, 'ajaxUpdateCharging', '1', '000'),
(421, 1, 34, 'ajaxDeleteCharging', '1', '000'),
(422, 1, 35, 'index', '1', '000'),
(423, 1, 35, 'ajaxGetOperatorList', '1', '000'),
(424, 1, 35, 'ajaxAddOperator', '1', '000'),
(425, 1, 35, 'ajaxUpdateOperator', '1', '000'),
(426, 1, 35, 'ajaxEditOperator', '1', '000'),
(427, 1, 35, 'ajaxDeleteOperator', '1', '000'),
(428, 1, 37, 'index', '1', '000'),
(429, 1, 37, 'ajaxGetCreatorList', '1', '000'),
(430, 1, 37, 'getOperatorList', '1', '000'),
(431, 1, 37, 'ajaxGetServiceList', '1', '000'),
(432, 1, 37, 'ajaxGetParams', '1', '000'),
(433, 1, 37, 'createKeyword', '1', '000'),
(434, 1, 37, 'getOperatorTab', '1', '000'),
(435, 2, 48, 'index', '1', '000'),
(436, 2, 48, 'ajaxGetControllerList', '1', '000'),
(437, 2, 48, 'getParentList', '1', '000'),
(438, 2, 48, 'getStatus', '1', '000'),
(439, 2, 48, 'ajaxSaveController', '1', '000'),
(440, 2, 48, 'ajaxEditController', '1', '000'),
(441, 2, 48, 'ajaxUpdateController', '1', '000'),
(442, 2, 48, 'ajaxDeleteController', '1', '000'),
(443, 3, 48, 'index', '1', '000'),
(444, 3, 48, 'ajaxGetControllerList', '1', '000'),
(445, 3, 48, 'getParentList', '1', '000'),
(446, 3, 48, 'getStatus', '1', '000'),
(447, 3, 48, 'ajaxSaveController', '1', '000'),
(448, 3, 48, 'ajaxEditController', '1', '000'),
(449, 3, 48, 'ajaxUpdateController', '1', '000'),
(450, 3, 48, 'ajaxDeleteController', '1', '000'),
(451, 4, 48, 'index', '1', '000'),
(452, 4, 48, 'ajaxGetControllerList', '1', '000'),
(453, 4, 48, 'getParentList', '1', '000'),
(454, 4, 48, 'getStatus', '1', '000'),
(455, 4, 48, 'ajaxSaveController', '1', '000'),
(456, 4, 48, 'ajaxEditController', '1', '000'),
(457, 4, 48, 'ajaxUpdateController', '1', '000'),
(458, 4, 48, 'ajaxDeleteController', '1', '000'),
(459, 2, 46, 'index', '1', '000'),
(460, 2, 46, 'ajaxGetGroupList', '1', '000'),
(461, 2, 46, 'getCheckMenu', '1', '000'),
(462, 2, 46, 'ajaxSaveGroup', '1', '000'),
(463, 2, 46, 'ajaxEditGroup', '1', '000'),
(464, 2, 46, 'ajaxUpdateGroup', '1', '000'),
(465, 2, 46, 'ajaxDeleteGroup', '1', '000'),
(466, 3, 46, 'index', '1', '000'),
(467, 3, 46, 'ajaxGetGroupList', '1', '000'),
(468, 3, 46, 'getCheckMenu', '1', '000'),
(469, 3, 46, 'ajaxSaveGroup', '1', '000'),
(470, 3, 46, 'ajaxEditGroup', '1', '000'),
(471, 3, 46, 'ajaxUpdateGroup', '1', '000'),
(472, 3, 46, 'ajaxDeleteGroup', '1', '000'),
(473, 4, 46, 'index', '1', '000'),
(474, 4, 46, 'ajaxGetGroupList', '1', '000'),
(475, 4, 46, 'getCheckMenu', '1', '000'),
(476, 4, 46, 'ajaxSaveGroup', '1', '000'),
(477, 4, 46, 'ajaxEditGroup', '1', '000'),
(478, 4, 46, 'ajaxUpdateGroup', '1', '000'),
(479, 4, 46, 'ajaxDeleteGroup', '1', '000'),
(480, 2, 49, 'index', '1', '000'),
(481, 2, 49, 'ajaxGetMethodGroupList', '1', '000'),
(482, 2, 49, 'getControllerList', '1', '000'),
(483, 2, 49, 'ajaxScanMethodGroup', '1', '000'),
(484, 2, 49, 'getControllerLink', '1', '000'),
(485, 2, 49, 'getFilePath', '1', '000'),
(486, 2, 49, 'getFileClassMethods', '1', '000'),
(487, 2, 49, 'ajaxActiveMethodGroup', '1', '000'),
(488, 2, 49, 'ajaxInactiveMethodGroup', '1', '000'),
(489, 2, 49, 'scanController', '1', '000'),
(490, 3, 49, 'index', '1', '000'),
(491, 3, 49, 'ajaxGetMethodGroupList', '1', '000'),
(492, 3, 49, 'getControllerList', '1', '000'),
(493, 3, 49, 'ajaxScanMethodGroup', '1', '000'),
(494, 3, 49, 'getControllerLink', '1', '000'),
(495, 3, 49, 'getFilePath', '1', '000'),
(496, 3, 49, 'getFileClassMethods', '1', '000'),
(497, 3, 49, 'ajaxActiveMethodGroup', '1', '000'),
(498, 3, 49, 'ajaxInactiveMethodGroup', '1', '000'),
(499, 3, 49, 'scanController', '1', '000'),
(500, 4, 49, 'index', '1', '000'),
(501, 4, 49, 'ajaxGetMethodGroupList', '1', '000'),
(502, 4, 49, 'getControllerList', '1', '000'),
(503, 4, 49, 'ajaxScanMethodGroup', '1', '000'),
(504, 4, 49, 'getControllerLink', '1', '000'),
(505, 4, 49, 'getFilePath', '1', '000'),
(506, 4, 49, 'getFileClassMethods', '1', '000'),
(507, 4, 49, 'ajaxActiveMethodGroup', '1', '000'),
(508, 4, 49, 'ajaxInactiveMethodGroup', '1', '000'),
(509, 4, 49, 'scanController', '1', '000'),
(510, 2, 47, 'index', '1', '000'),
(511, 2, 47, 'changepass', '1', '000'),
(512, 2, 47, 'cpsuccess', '1', '000'),
(513, 2, 47, 'changeprofile', '1', '000'),
(514, 2, 47, 'ajaxGetUserList', '1', '000'),
(515, 2, 47, 'getGroupList', '1', '000'),
(516, 2, 47, 'ajaxAddNewUser', '1', '000'),
(517, 2, 47, 'ajaxEditUser', '1', '000'),
(518, 2, 47, 'ajaxUpdateUser', '1', '000'),
(519, 2, 47, 'ajaxDeleteUser', '1', '000'),
(520, 3, 47, 'index', '1', '000'),
(521, 3, 47, 'changepass', '1', '000'),
(522, 3, 47, 'cpsuccess', '1', '000'),
(523, 3, 47, 'changeprofile', '1', '000'),
(524, 3, 47, 'ajaxGetUserList', '1', '000'),
(525, 3, 47, 'getGroupList', '1', '000'),
(526, 3, 47, 'ajaxAddNewUser', '1', '000'),
(527, 3, 47, 'ajaxEditUser', '1', '000'),
(528, 3, 47, 'ajaxUpdateUser', '1', '000'),
(529, 3, 47, 'ajaxDeleteUser', '1', '000'),
(530, 4, 47, 'index', '1', '000'),
(531, 4, 47, 'changepass', '1', '000'),
(532, 4, 47, 'cpsuccess', '1', '000'),
(533, 4, 47, 'changeprofile', '1', '000'),
(534, 4, 47, 'ajaxGetUserList', '1', '000'),
(535, 4, 47, 'getGroupList', '1', '000'),
(536, 4, 47, 'ajaxAddNewUser', '1', '000'),
(537, 4, 47, 'ajaxEditUser', '1', '000'),
(538, 4, 47, 'ajaxUpdateUser', '1', '000'),
(539, 4, 47, 'ajaxDeleteUser', '1', '000'),
(540, 2, 33, 'index', '1', '000'),
(541, 2, 33, 'ajaxGetAdnList', '1', '000'),
(542, 2, 33, 'ajaxAddAdn', '1', '000'),
(543, 2, 33, 'ajaxUpdateAdn', '1', '000'),
(544, 2, 33, 'ajaxEditAdn', '1', '000'),
(545, 2, 33, 'ajaxDeleteAdn', '1', '000'),
(546, 3, 33, 'index', '1', '000'),
(547, 3, 33, 'ajaxGetAdnList', '1', '000'),
(548, 3, 33, 'ajaxAddAdn', '1', '000'),
(549, 3, 33, 'ajaxUpdateAdn', '1', '000'),
(550, 3, 33, 'ajaxEditAdn', '1', '000'),
(551, 3, 33, 'ajaxDeleteAdn', '1', '000'),
(552, 4, 33, 'index', '1', '000'),
(553, 4, 33, 'ajaxGetAdnList', '1', '000'),
(554, 4, 33, 'ajaxAddAdn', '1', '000'),
(555, 4, 33, 'ajaxUpdateAdn', '1', '000'),
(556, 4, 33, 'ajaxEditAdn', '1', '000'),
(557, 4, 33, 'ajaxDeleteAdn', '1', '000'),
(558, 2, 34, 'index', '1', '000'),
(559, 2, 34, 'ajaxGetChargingList', '1', '000'),
(560, 2, 34, 'getOperatorList', '1', '000'),
(561, 2, 34, 'getAdnList', '1', '000'),
(562, 2, 34, 'ajaxSaveCharging', '1', '000'),
(563, 2, 34, 'ajaxEditCharging', '1', '000'),
(564, 2, 34, 'ajaxUpdateCharging', '1', '000'),
(565, 2, 34, 'ajaxDeleteCharging', '1', '000'),
(566, 3, 34, 'index', '1', '000'),
(567, 3, 34, 'ajaxGetChargingList', '1', '000'),
(568, 3, 34, 'getOperatorList', '1', '000'),
(569, 3, 34, 'getAdnList', '1', '000'),
(570, 3, 34, 'ajaxSaveCharging', '1', '000'),
(571, 3, 34, 'ajaxEditCharging', '1', '000'),
(572, 3, 34, 'ajaxUpdateCharging', '1', '000'),
(573, 3, 34, 'ajaxDeleteCharging', '1', '000'),
(574, 4, 34, 'index', '1', '000'),
(575, 4, 34, 'ajaxGetChargingList', '1', '000'),
(576, 4, 34, 'getOperatorList', '1', '000'),
(577, 4, 34, 'getAdnList', '1', '000'),
(578, 4, 34, 'ajaxSaveCharging', '1', '000'),
(579, 4, 34, 'ajaxEditCharging', '1', '000'),
(580, 4, 34, 'ajaxUpdateCharging', '1', '000'),
(581, 4, 34, 'ajaxDeleteCharging', '1', '000'),
(582, 1, 50, 'index', '1', '000'),
(583, 1, 50, 'ajaxGetCustomHandlerList', '1', '000'),
(584, 1, 50, 'ajaxAddCustomHandler', '1', '000'),
(585, 1, 50, 'ajaxUpdateCustomHandler', '1', '000'),
(586, 1, 50, 'ajaxEditCustomHandler', '1', '000'),
(587, 1, 50, 'ajaxDeleteCustomHandler', '1', '000'),
(588, 2, 50, 'index', '1', '000'),
(589, 2, 50, 'ajaxGetCustomHandlerList', '1', '000'),
(590, 2, 50, 'ajaxAddCustomHandler', '1', '000'),
(591, 2, 50, 'ajaxUpdateCustomHandler', '1', '000'),
(592, 2, 50, 'ajaxEditCustomHandler', '1', '000'),
(593, 2, 50, 'ajaxDeleteCustomHandler', '1', '000'),
(594, 3, 50, 'index', '1', '000'),
(595, 3, 50, 'ajaxGetCustomHandlerList', '1', '000'),
(596, 3, 50, 'ajaxAddCustomHandler', '1', '000'),
(597, 3, 50, 'ajaxUpdateCustomHandler', '1', '000'),
(598, 3, 50, 'ajaxEditCustomHandler', '1', '000'),
(599, 3, 50, 'ajaxDeleteCustomHandler', '1', '000'),
(600, 4, 50, 'index', '1', '000'),
(601, 4, 50, 'ajaxGetCustomHandlerList', '1', '000'),
(602, 4, 50, 'ajaxAddCustomHandler', '1', '000'),
(603, 4, 50, 'ajaxUpdateCustomHandler', '1', '000'),
(604, 4, 50, 'ajaxEditCustomHandler', '1', '000'),
(605, 4, 50, 'ajaxDeleteCustomHandler', '1', '000'),
(606, 1, 51, 'index', '1', '000'),
(607, 1, 51, 'ajaxGetModuleList', '1', '000'),
(608, 1, 51, 'ajaxAddModule', '1', '000'),
(609, 1, 51, 'ajaxUpdateModule', '1', '000'),
(610, 1, 51, 'ajaxEditModule', '1', '000'),
(611, 1, 51, 'ajaxDeleteModule', '1', '000'),
(612, 2, 51, 'index', '1', '000'),
(613, 2, 51, 'ajaxGetModuleList', '1', '000'),
(614, 2, 51, 'ajaxAddModule', '1', '000'),
(615, 2, 51, 'ajaxUpdateModule', '1', '000'),
(616, 2, 51, 'ajaxEditModule', '1', '000'),
(617, 2, 51, 'ajaxDeleteModule', '1', '000'),
(618, 3, 51, 'index', '1', '000'),
(619, 3, 51, 'ajaxGetModuleList', '1', '000'),
(620, 3, 51, 'ajaxAddModule', '1', '000'),
(621, 3, 51, 'ajaxUpdateModule', '1', '000'),
(622, 3, 51, 'ajaxEditModule', '1', '000'),
(623, 3, 51, 'ajaxDeleteModule', '1', '000'),
(624, 4, 51, 'index', '1', '000'),
(625, 4, 51, 'ajaxGetModuleList', '1', '000'),
(626, 4, 51, 'ajaxAddModule', '1', '000'),
(627, 4, 51, 'ajaxUpdateModule', '1', '000'),
(628, 4, 51, 'ajaxEditModule', '1', '000'),
(629, 4, 51, 'ajaxDeleteModule', '1', '000'),
(630, 2, 35, 'index', '1', '000'),
(631, 2, 35, 'ajaxGetOperatorList', '1', '000'),
(632, 2, 35, 'ajaxAddOperator', '1', '000'),
(633, 2, 35, 'ajaxUpdateOperator', '1', '000'),
(634, 2, 35, 'ajaxEditOperator', '1', '000'),
(635, 2, 35, 'ajaxDeleteOperator', '1', '000'),
(636, 3, 35, 'index', '1', '000'),
(637, 3, 35, 'ajaxGetOperatorList', '1', '000'),
(638, 3, 35, 'ajaxAddOperator', '1', '000'),
(639, 3, 35, 'ajaxUpdateOperator', '1', '000'),
(640, 3, 35, 'ajaxEditOperator', '1', '000'),
(641, 3, 35, 'ajaxDeleteOperator', '1', '000'),
(642, 4, 35, 'index', '1', '000'),
(643, 4, 35, 'ajaxGetOperatorList', '1', '000'),
(644, 4, 35, 'ajaxAddOperator', '1', '000'),
(645, 4, 35, 'ajaxUpdateOperator', '1', '000'),
(646, 4, 35, 'ajaxEditOperator', '1', '000'),
(647, 4, 35, 'ajaxDeleteOperator', '1', '000'),
(648, 1, 36, 'index', '1', '000'),
(649, 1, 36, 'ajaxGetServiceList', '1', '000'),
(650, 1, 36, 'ajaxAddNewService', '1', '000'),
(651, 1, 36, 'ajaxUpdateService', '1', '000'),
(652, 1, 36, 'ajaxEditService', '1', '000'),
(653, 1, 36, 'getAdn', '1', '000'),
(654, 2, 36, 'index', '1', '000'),
(655, 2, 36, 'ajaxGetServiceList', '1', '000'),
(656, 2, 36, 'ajaxAddNewService', '1', '000'),
(657, 2, 36, 'ajaxUpdateService', '1', '000'),
(658, 2, 36, 'ajaxEditService', '1', '000'),
(659, 2, 36, 'getAdn', '1', '000'),
(660, 3, 36, 'index', '1', '000'),
(661, 3, 36, 'ajaxGetServiceList', '1', '000'),
(662, 3, 36, 'ajaxAddNewService', '1', '000'),
(663, 3, 36, 'ajaxUpdateService', '1', '000'),
(664, 3, 36, 'ajaxEditService', '1', '000'),
(665, 3, 36, 'getAdn', '1', '000'),
(666, 4, 36, 'index', '1', '000'),
(667, 4, 36, 'ajaxGetServiceList', '1', '000'),
(668, 4, 36, 'ajaxAddNewService', '1', '000'),
(669, 4, 36, 'ajaxUpdateService', '1', '000'),
(670, 4, 36, 'ajaxEditService', '1', '000'),
(671, 4, 36, 'getAdn', '1', '000'),
(672, 1, 37, 'getModuleList', '1', '000'),
(673, 1, 37, 'createNewTab', '1', '000'),
(674, 2, 37, 'index', '1', '000'),
(675, 2, 37, 'ajaxGetCreatorList', '1', '000'),
(676, 2, 37, 'getOperatorList', '1', '000'),
(677, 2, 37, 'ajaxGetServiceList', '1', '000'),
(678, 2, 37, 'ajaxGetParams', '1', '000'),
(679, 2, 37, 'createKeyword', '1', '000'),
(680, 2, 37, 'getOperatorTab', '1', '000'),
(681, 2, 37, 'getModuleList', '1', '000'),
(682, 2, 37, 'createNewTab', '1', '000'),
(683, 3, 37, 'index', '1', '000'),
(684, 3, 37, 'ajaxGetCreatorList', '1', '000'),
(685, 3, 37, 'getOperatorList', '1', '000'),
(686, 3, 37, 'ajaxGetServiceList', '1', '000'),
(687, 3, 37, 'ajaxGetParams', '1', '000'),
(688, 3, 37, 'createKeyword', '1', '000'),
(689, 3, 37, 'getOperatorTab', '1', '000'),
(690, 3, 37, 'getModuleList', '1', '000'),
(691, 3, 37, 'createNewTab', '1', '000'),
(692, 4, 37, 'index', '1', '000'),
(693, 4, 37, 'ajaxGetCreatorList', '1', '000'),
(694, 4, 37, 'getOperatorList', '1', '000'),
(695, 4, 37, 'ajaxGetServiceList', '1', '000'),
(696, 4, 37, 'ajaxGetParams', '1', '000'),
(697, 4, 37, 'createKeyword', '1', '000'),
(698, 4, 37, 'getOperatorTab', '1', '000'),
(699, 4, 37, 'getModuleList', '1', '000'),
(700, 4, 37, 'createNewTab', '1', '000'),
(701, 1, 37, 'formTab', '1', '000'),
(702, 1, 37, 'getChargingList', '1', '000'),
(703, 1, 37, 'ajaxAddKeyword', '1', '000'),
(704, 2, 37, 'formTab', '1', '000'),
(705, 2, 37, 'getChargingList', '1', '000'),
(706, 2, 37, 'ajaxAddKeyword', '1', '000'),
(707, 3, 37, 'formTab', '1', '000'),
(708, 3, 37, 'getChargingList', '1', '000'),
(709, 3, 37, 'ajaxAddKeyword', '1', '000'),
(710, 4, 37, 'formTab', '1', '000'),
(711, 4, 37, 'getChargingList', '1', '000'),
(712, 4, 37, 'ajaxAddKeyword', '1', '000'),
(713, 1, 37, 'ajaxEditCreator', '1', '000'),
(714, 2, 37, 'ajaxEditCreator', '1', '000'),
(715, 1, 37, 'ajaxAddNewCreator', '1', '000'),
(716, 1, 37, 'ajaxUpdateCreator', '1', '000'),
(717, 2, 37, 'ajaxAddNewCreator', '1', '000'),
(718, 2, 37, 'ajaxUpdateCreator', '1', '000'),
(719, 3, 37, 'ajaxAddNewCreator', '1', '000'),
(720, 3, 37, 'ajaxUpdateCreator', '1', '000'),
(721, 3, 37, 'ajaxEditCreator', '1', '000'),
(722, 4, 37, 'ajaxAddNewCreator', '1', '000'),
(723, 4, 37, 'ajaxUpdateCreator', '1', '000'),
(724, 4, 37, 'ajaxEditCreator', '1', '000'),
(725, 1, 37, 'getServiceList', '1', '000'),
(726, 2, 37, 'getServiceList', '1', '000'),
(727, 3, 37, 'getServiceList', '1', '000'),
(728, 4, 37, 'getServiceList', '1', '000'),
(729, 1, 23, 'index', '1', '000'),
(730, 1, 23, 'ajaxGetMOTrafficList', '1', '000'),
(731, 1, 23, 'dateToString', '1', '000'),
(732, 1, 23, 'getOperatorList', '1', '000'),
(733, 1, 23, 'getAdnList', '1', '000'),
(734, 1, 23, 'getTypeList', '1', '000'),
(735, 1, 23, 'ajaxGetServiceList', '1', '000'),
(736, 1, 23, 'getTodayMOList', '1', '000'),
(737, 1, 23, 'getTotalMOList', '1', '000'),
(738, 1, 23, 'getChartData', '1', '000'),
(739, 2, 23, 'index', '1', '000'),
(740, 2, 23, 'ajaxGetMOTrafficList', '1', '000'),
(741, 2, 23, 'dateToString', '1', '000'),
(742, 2, 23, 'getOperatorList', '1', '000'),
(743, 2, 23, 'getAdnList', '1', '000'),
(744, 2, 23, 'getTypeList', '1', '000'),
(745, 2, 23, 'ajaxGetServiceList', '1', '000'),
(746, 2, 23, 'getTodayMOList', '1', '000'),
(747, 2, 23, 'getTotalMOList', '1', '000'),
(748, 2, 23, 'getChartData', '1', '000'),
(749, 3, 23, 'index', '1', '000'),
(750, 3, 23, 'ajaxGetMOTrafficList', '1', '000'),
(751, 3, 23, 'dateToString', '1', '000'),
(752, 3, 23, 'getOperatorList', '1', '000'),
(753, 3, 23, 'getAdnList', '1', '000'),
(754, 3, 23, 'getTypeList', '1', '000'),
(755, 3, 23, 'ajaxGetServiceList', '1', '000'),
(756, 3, 23, 'getTodayMOList', '1', '000'),
(757, 3, 23, 'getTotalMOList', '1', '000'),
(758, 3, 23, 'getChartData', '1', '000'),
(759, 4, 23, 'index', '1', '000'),
(760, 4, 23, 'ajaxGetMOTrafficList', '1', '000'),
(761, 4, 23, 'dateToString', '1', '000'),
(762, 4, 23, 'getOperatorList', '1', '000'),
(763, 4, 23, 'getAdnList', '1', '000'),
(764, 4, 23, 'getTypeList', '1', '000'),
(765, 4, 23, 'ajaxGetServiceList', '1', '000'),
(766, 4, 23, 'getTodayMOList', '1', '000'),
(767, 4, 23, 'getTotalMOList', '1', '000'),
(768, 4, 23, 'getChartData', '1', '000'),
(769, 1, 30, 'index', '1', '000'),
(770, 1, 30, 'getHistoryTable', '1', '000'),
(771, 1, 30, 'pagination', '1', '000'),
(772, 2, 30, 'index', '1', '000'),
(773, 2, 30, 'getHistoryTable', '1', '000'),
(774, 2, 30, 'pagination', '1', '000'),
(775, 3, 30, 'index', '1', '000'),
(776, 3, 30, 'getHistoryTable', '1', '000'),
(777, 3, 30, 'pagination', '1', '000'),
(778, 4, 30, 'index', '1', '000'),
(779, 4, 30, 'getHistoryTable', '1', '000'),
(780, 4, 30, 'pagination', '1', '000'),
(781, 1, 31, 'index', '1', '000'),
(782, 1, 31, 'getUserSubscriptionTable', '1', '000'),
(783, 1, 31, 'pagination', '1', '000'),
(784, 1, 31, 'inactiveCheck', '1', '000'),
(785, 2, 31, 'index', '1', '000'),
(786, 2, 31, 'getUserSubscriptionTable', '1', '000'),
(787, 2, 31, 'pagination', '1', '000'),
(788, 2, 31, 'inactiveCheck', '1', '000'),
(789, 3, 31, 'index', '1', '000'),
(790, 3, 31, 'getUserSubscriptionTable', '1', '000'),
(791, 3, 31, 'pagination', '1', '000'),
(792, 3, 31, 'inactiveCheck', '1', '000'),
(793, 4, 31, 'index', '1', '000'),
(794, 4, 31, 'getUserSubscriptionTable', '1', '000'),
(795, 4, 31, 'pagination', '1', '000'),
(796, 4, 31, 'inactiveCheck', '1', '000'),
(797, 1, 52, 'index', '1', '000'),
(798, 1, 52, 'ajaxGetChargingList', '1', '000'),
(799, 1, 52, 'getOperatorList', '1', '000'),
(800, 1, 52, 'getAdnList', '1', '000'),
(801, 1, 52, 'ajaxSaveCharging', '1', '000'),
(802, 1, 52, 'ajaxEditCharging', '1', '000'),
(803, 1, 52, 'ajaxUpdateCharging', '1', '000'),
(804, 1, 52, 'ajaxDeleteCharging', '1', '000'),
(805, 2, 52, 'index', '1', '000'),
(806, 2, 52, 'ajaxGetChargingList', '1', '000'),
(807, 2, 52, 'getOperatorList', '1', '000'),
(808, 2, 52, 'getAdnList', '1', '000'),
(809, 2, 52, 'ajaxSaveCharging', '1', '000'),
(810, 2, 52, 'ajaxEditCharging', '1', '000'),
(811, 2, 52, 'ajaxUpdateCharging', '1', '000'),
(812, 2, 52, 'ajaxDeleteCharging', '1', '000'),
(813, 3, 52, 'index', '1', '000'),
(814, 3, 52, 'ajaxGetChargingList', '1', '000'),
(815, 3, 52, 'getOperatorList', '1', '000'),
(816, 3, 52, 'getAdnList', '1', '000'),
(817, 3, 52, 'ajaxSaveCharging', '1', '000'),
(818, 3, 52, 'ajaxEditCharging', '1', '000'),
(819, 3, 52, 'ajaxUpdateCharging', '1', '000'),
(820, 3, 52, 'ajaxDeleteCharging', '1', '000'),
(821, 4, 52, 'index', '1', '000'),
(822, 4, 52, 'ajaxGetChargingList', '1', '000'),
(823, 4, 52, 'getOperatorList', '1', '000'),
(824, 4, 52, 'getAdnList', '1', '000'),
(825, 4, 52, 'ajaxSaveCharging', '1', '000'),
(826, 4, 52, 'ajaxEditCharging', '1', '000'),
(827, 4, 52, 'ajaxUpdateCharging', '1', '000'),
(828, 4, 52, 'ajaxDeleteCharging', '1', '000'),
(829, 1, 46, 'has_children', '1', '000'),
(830, 1, 46, 'build_menu', '1', '000'),
(831, 2, 46, 'has_children', '1', '000'),
(832, 2, 46, 'build_menu', '1', '000'),
(833, 3, 46, 'has_children', '1', '000'),
(834, 3, 46, 'build_menu', '1', '000'),
(835, 4, 46, 'has_children', '1', '000'),
(836, 4, 46, 'build_menu', '1', '000'),
(837, 1, 52, 'ajaxGetControlList', '1', '000'),
(838, 1, 52, 'ajaxSaveControl', '1', '000'),
(839, 1, 52, 'ajaxEditControl', '1', '000'),
(840, 1, 52, 'ajaxUpdateControl', '1', '000'),
(841, 1, 52, 'ajaxDeleteControl', '1', '000'),
(842, 1, 52, 'getHour', '1', '000'),
(843, 2, 52, 'ajaxGetControlList', '1', '000'),
(844, 2, 52, 'ajaxSaveControl', '1', '000'),
(845, 2, 52, 'ajaxEditControl', '1', '000'),
(846, 2, 52, 'ajaxUpdateControl', '1', '000'),
(847, 2, 52, 'ajaxDeleteControl', '1', '000'),
(848, 2, 52, 'getHour', '1', '000'),
(849, 3, 52, 'ajaxGetControlList', '1', '000'),
(850, 3, 52, 'ajaxSaveControl', '1', '000'),
(851, 3, 52, 'ajaxEditControl', '1', '000'),
(852, 3, 52, 'ajaxUpdateControl', '1', '000'),
(853, 3, 52, 'ajaxDeleteControl', '1', '000'),
(854, 3, 52, 'getHour', '1', '000'),
(855, 4, 52, 'ajaxGetControlList', '1', '000'),
(856, 4, 52, 'ajaxSaveControl', '1', '000'),
(857, 4, 52, 'ajaxEditControl', '1', '000'),
(858, 4, 52, 'ajaxUpdateControl', '1', '000'),
(859, 4, 52, 'ajaxDeleteControl', '1', '000'),
(860, 4, 52, 'getHour', '1', '000'),
(861, 1, 52, 'getMinute', '1', '000'),
(862, 2, 52, 'getMinute', '1', '000'),
(863, 3, 52, 'getMinute', '1', '000'),
(864, 4, 52, 'getMinute', '1', '000');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `u_group` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('1','0') DEFAULT '1' COMMENT '1->active; 0->inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `u_group`, `created`, `modified`, `status`) VALUES
(1, 'admin', '5d41402abc4b2a76b9719d911017c592', 1, '2010-07-21 22:45:07', '2011-09-29 01:32:33', '1'),
(2, 'dhanyalvian', 'a2c432fe603d7c9536c946ef158d4702', 2, '2011-07-20 18:34:08', '2011-09-29 01:44:38', '1'),
(3, 'gleen', '0dcd649d4ef5f787e39ddf48d8e625a5', 2, '2011-07-20 18:34:46', '2011-10-03 10:05:05', '1'),
(4, 'letitia', '5d41402abc4b2a76b9719d911017c592', 3, '2011-07-20 18:35:10', '2011-07-26 03:39:55', '1'),
(5, 'indra', '37ddcc8396001f769e8ec3f20ca90f9c', 2, '2011-07-22 00:00:00', '2011-11-08 13:40:45', '0'),
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
(18, 'testery', '9983bdd4d05379865cbe0b7df2b31fdf', 4, '2011-09-29 01:45:04', '2011-11-08 13:41:02', '1'),
(19, 'content', '9a0364b9e99bb480dd25e1f0284c8555', 3, '2011-09-29 01:47:09', '2011-10-12 13:16:14', '1'),
(20, 'sapi hernandez tralala', '58b2ce48fe1b77e164581ae6b1900131', 2, '2011-11-07 15:57:34', '2011-11-07 15:57:59', '0'),
(21, 'kamto', '5d41402abc4b2a76b9719d911017c592', 3, '2012-03-28 16:30:49', '2012-04-05 10:29:31', '1');
