
-- --------------------------------------------------------

--
-- Table structure for table `taxonomies`
--

CREATE TABLE IF NOT EXISTS `taxonomies` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `parent_id` int(20) DEFAULT '0',
  `term_id` int(10) NOT NULL,
  `vocabulary_id` int(10) NOT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `taxonomies`
--

INSERT INTO `taxonomies` (`id`, `parent_id`, `term_id`, `vocabulary_id`, `lft`, `rght`) VALUES
(1, NULL, 1, 1, 5, 8),
(4, 1, 5, 1, 6, 7),
(9, NULL, 4, 1, 9, 10),
(10, NULL, 2, 1, 1, 4),
(11, 10, 10, 1, 2, 3),
(12, NULL, 11, 6, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE IF NOT EXISTS `terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vocabulary_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: inactive, 1 : active',
  `updated` datetime NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `vocabulary_id` (`vocabulary_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `vocabulary_id`, `title`, `slug`, `description`, `status`, `updated`, `created`) VALUES
(1, 1, 'About Find Kind Bud', 'about-find-kind-bud', '', 1, '2014-04-30 11:48:48', '2009-07-22 03:34:56'),
(2, 1, 'Announcements', 'announcements', '', 1, '2014-04-30 11:51:46', '2009-07-22 03:45:37'),
(4, 1, 'What is FKB ?', 'what-is-fkb', 'terms', 1, '2014-04-30 11:50:21', '0000-00-00 00:00:00'),
(5, 1, 'drupal2', 'drupal2', 'drupal2', 1, '2014-04-30 11:00:32', '2014-04-29 17:49:55'),
(10, 1, 'New sample', 'new-sample', '', 1, '2014-04-30 12:44:13', '2014-04-30 11:52:27'),
(11, 6, 'drupal21', 'drupal21', 'Description', 1, '2014-04-30 14:32:12', '2014-04-30 14:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `vocabularies`
--

CREATE TABLE IF NOT EXISTS `vocabularies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `plugin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vocabulary_alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `vocabularies`
--

INSERT INTO `vocabularies` (`id`, `title`, `alias`, `description`, `plugin`, `weight`, `updated`, `created`) VALUES
(1, 'Help', 'help', 'help', '', 1, '2014-04-29 17:54:46', '2009-07-22 02:16:21'),
(2, 'Tags', 'tags', 'storing tags', '', 4, '2014-04-29 17:03:47', '2009-07-22 02:16:34'),
(3, 'drupal and magento', 'drupal-and-magento', 'this is very basic information about category', NULL, 2, '2014-04-29 17:03:06', '2014-04-28 09:18:06'),
(4, 'Help Category', 'help_category', 'Used for Faq', NULL, 3, '2014-04-29 17:03:06', '2014-04-29 16:34:43'),
(5, 'categories', 'categories12', 'categories', NULL, 5, '2014-04-29 16:58:00', '2014-04-29 16:43:46'),
(6, 'User types', 'user-types', '', NULL, 1, '2014-04-30 14:57:42', '2014-04-30 14:30:33');
