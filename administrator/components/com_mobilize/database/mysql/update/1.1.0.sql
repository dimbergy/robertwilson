--
-- Table structure for table `#__jsn_mobilize_os`
--

DROP TABLE IF EXISTS `#__jsn_mobilize_os`;
CREATE TABLE IF NOT EXISTS `#__jsn_mobilize_os` (
  `os_id` int(11) NOT NULL AUTO_INCREMENT,
  `os_value` varchar(255) NOT NULL,
  `os_type` varchar(50) NOT NULL,
  `os_title` varchar(255) NOT NULL,
  `os_order` int(11) NOT NULL,
  PRIMARY KEY (`os_id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `#__jsn_mobilize_os`
--

INSERT INTO `#__jsn_mobilize_os` (`os_id`, `os_value`, `os_type`, `os_title`, `os_order`) VALUES
(1, '{"ios":["6","<"]}', 'ios', 'iOS 6.x and bellow', 1),
(2, '{"ios":["7"]}', 'ios', 'iOS 7.x', 2),
(3, '{"android":["2"]}', 'android', 'Android 2.2 - 2.3', 4),
(4, '{"android":["4"]}', 'android', 'Android 4.x', 5),
(5, '{"wmobilie":["6","<"]}', 'wmobilie', 'Windows Mobile 6.x and bellow', 6),
(6, '{"wmobilie":["7"]}', 'wmobilie', 'Windows Mobile 7.x', 7),
(7, '{"wmobilie":["8"]}', 'wmobilie', 'Windows Mobile 8.x', 8),
(8, '{"blackberry":["5","<"]}', 'blackberry', 'BlackBerry 5.x and bellow', 9),
(9, '{"blackberry":["6","7"]}', 'blackberry', 'BlackBerry 6x - 7x', 10),
(10, '{"blackberry":["10"]}', 'blackberry', 'BlackBerry 10x', 11),
(11, 'other', 'other', 'Other', 12),
(12, '{"ios":["8"]}', 'ios', 'iOS 8.x', 3);