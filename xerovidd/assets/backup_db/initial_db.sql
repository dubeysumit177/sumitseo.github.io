SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `add_ons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `add_on_name` varchar(255) NOT NULL,
  `unique_name` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `installed_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  `purchase_code` varchar(100) NOT NULL,
  `module_folder_name` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`unique_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `ad_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section1_html` longtext,
  `section1_html_mobile` longtext,
  `section2_html` longtext,
  `section3_html` longtext,
  `section4_html` longtext,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `announcement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 means all',
  `is_seen` enum('0','1') NOT NULL DEFAULT '0',
  `seen_by` text NOT NULL COMMENT 'if user_id = 0 then comma seperated user_ids',
  `last_seen_at` datetime NOT NULL,
  `color_class` varchar(50) NOT NULL DEFAULT 'primary',
  `icon` varchar(50) NOT NULL DEFAULT 'fas fa-bell',
  `status` enum('published','draft') NOT NULL DEFAULT 'draft',
  PRIMARY KEY (`id`),
  KEY `for_user_id` (`user_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `auto_channel_subscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_id` varchar(255) NOT NULL COMMENT 'channel_id',
  `channel_auto_id` int(11) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `keywords` text NOT NULL,
  `date_range` varchar(255) NOT NULL,
  `max_activity_per_day` int(11) NOT NULL,
  `expire_type` varchar(255) NOT NULL,
  `campaign_expire_max_activity` int(11) NOT NULL,
  `expire_date` datetime NOT NULL,
  `total_activity` int(11) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `last_processed_date` date NOT NULL,
  `last_used_keyword` varchar(255) NOT NULL,
  `error` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_auto_id` (`channel_auto_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `auto_channel_subscription_prepared` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_auto_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `targeted_channel_id` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `time_zone` varchar(100) NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `subscription_status` enum('0','1') NOT NULL DEFAULT '0',
  `subscribed_id` varchar(255) NOT NULL,
  `subscribed_at` datetime NOT NULL,
  `unsubscribed_at` datetime NOT NULL,
  `error` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `status` (`status`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `auto_comment_templete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `auto_like_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_id` varchar(255) NOT NULL COMMENT 'channel_id',
  `channel_auto_id` int(11) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `auto_comment_template_id` int(11) NOT NULL,
  `keyword_or_channel` varchar(255) NOT NULL,
  `keywords` text NOT NULL,
  `channels` text NOT NULL,
  `auto_like` tinyint(4) NOT NULL,
  `max_activity_per_day` int(11) NOT NULL,
  `expire_type` varchar(255) NOT NULL,
  `campaign_expire_max_activity` int(11) NOT NULL,
  `expire_date` datetime NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `last_processed_date` date NOT NULL,
  `total_activity` int(11) NOT NULL,
  `last_used_keyword` varchar(255) NOT NULL,
  `last_used_channel` varchar(100) NOT NULL,
  `error` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_auto_id` (`channel_auto_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `auto_like_comment_campaign_prepared` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_auto_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` varchar(255) NOT NULL,
  `auto_comment_template_id` int(11) NOT NULL,
  `auto_like` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `time_zone` varchar(100) NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `comment_id` varchar(200) NOT NULL,
  `author` varchar(200) NOT NULL,
  `comment_text` text NOT NULL,
  `published_at` datetime NOT NULL,
  `error` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `auto_reply_campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `channel_auto_id` int(11) NOT NULL,
  `video_id` varchar(255) NOT NULL,
  `delete_offensive_comment` enum('0','1') NOT NULL,
  `offensive_words` text NOT NULL,
  `multiple_reply` enum('0','1') NOT NULL,
  `reply_type` enum('generic','filter') NOT NULL,
  `generic_reply_message` text NOT NULL,
  `filter_reply_message` text NOT NULL,
  `filter_no_match_message` text NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL,
  `last_processed_date` datetime NOT NULL,
  `error` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_auto_id` (`channel_auto_id`),
  KEY `video_id` (`video_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `auto_reply_campaign_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_auto_id` int(11) NOT NULL,
  `auto_reply_campaign_table_id` int(11) NOT NULL,
  `comment_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `comment_author` varchar(255) NOT NULL,
  `comment_text` text NOT NULL,
  `comment_author_avatar` varchar(255) NOT NULL,
  `comment_author_channel_id` varchar(100) NOT NULL,
  `is_offensive` enum('0','1') NOT NULL DEFAULT '0',
  `reply_to_be_given` text NOT NULL,
  `replied_at` datetime NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `error` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auto_reply_campaign_table_id` (`auto_reply_campaign_table_id`),
  KEY `status` (`status`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `auto_reply_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `delete_offensive_comment` enum('0','1') NOT NULL,
  `offensive_words` text,
  `multiple_reply` enum('0','1') NOT NULL,
  `reply_type` enum('generic','filter') NOT NULL,
  `generic_reply_message` text,
  `filter_reply_message` text,
  `filter_no_match_message` text,
  `created_at` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_type` enum('Default','tls','ssl') NOT NULL DEFAULT 'Default',
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `email_template_management` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `template_type` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'fas fa-folder-open',
  `tooltip` text NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `email_template_management` (`id`, `title`, `template_type`, `subject`, `message`, `icon`, `tooltip`, `info`) VALUES
(1, 'Signup Activation', 'signup_activation', '#APP_NAME# | Account Activation', '<p>To activate your account please perform the following steps :</p>\r\n<ol>\r\n<li>Go to this url : #ACTIVATION_URL#</li>\r\n<li>Enter this code : #ACCOUNT_ACTIVATION_CODE#</li>\r\n<li>Activate your account</li>\r\n</ol>', 'fas fa-skating', '#APP_NAME#,#ACTIVATION_URL#,#ACCOUNT_ACTIVATION_CODE#', 'When a new user open an account'),
(2, 'Reset Password', 'reset_password', '#APP_NAME# | Password Recovery', '<p>To reset your password please perform the following steps :</p>\r\n<ol>\r\n<li>Go to this url : #PASSWORD_RESET_URL#</li>\r\n<li>Enter this code : #PASSWORD_RESET_CODE#</li>\r\n<li>reset your password.</li>\r\n</ol>\r\n<h4>Link and code will be expired after 24 hours.</h4>', 'fas fa-retweet', '#APP_NAME#,#PASSWORD_RESET_URL#,#PASSWORD_RESET_CODE#', 'When a user forget login password'),
(3, 'Change Password', 'change_password', 'Change Password Notification', 'Dear #USERNAME#,<br/> \r\nYour <a href="#APP_URL#">#APP_NAME#</a> password has been changed.<br>\r\nYour new password is: #NEW_PASSWORD#.<br/><br/> \r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-key', '#APP_NAME#,#APP_URL#,#USERNAME#,#NEW_PASSWORD#', 'When admin reset password of any user'),
(4, 'Subscription Expiring Soon', 'membership_expiration_10_days_before', 'Payment Alert', 'Dear #USERNAME#,\r\n<br/> Your account will expire after 10 days, Please pay your fees.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-clock', '#APP_NAME#,#APP_URL#,#USERNAME#', '10 days before user subscription expires'),
(5, 'Subscription Expiring Tomorrow', 'membership_expiration_1_day_before', 'Payment Alert', 'Dear #USERNAME#,<br/>\r\nYour account will expire tomorrow, Please pay your fees.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-stopwatch', '#APP_NAME#,#APP_URL#,#USERNAME#', '1 day before user subscription expires'),
(6, 'Subscription Expired', 'membership_expiration_1_day_after', 'Subscription Expired', 'Dear #USERNAME#,<br/>\r\nYour account has been expired, Please pay your fees for continuity.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-user-clock', '#APP_NAME#,#APP_URL#,#USERNAME#', 'Subscription is already expired of a user'),
(7, 'Paypal Payment Confirmation', 'paypal_payment', 'Payment Confirmation', 'Congratulations,<br/> \r\nWe have received your payment successfully.<br/>\r\nNow you are able to use #PRODUCT_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>\r\nThank you,<br/>\r\n<a href="#SITE_URL#">#APP_NAME#</a> Team', 'fab fa-paypal', '#APP_NAME#,#CYCLE_EXPIRED_DATE#,#PRODUCT_SHORT_NAME#,#SITE_URL#', 'User pay through Paypal & gets confirmation'),
(8, 'Paypal New Payment', 'paypal_new_payment_made', 'New Payment Made', 'New payment has been made by #PAID_USER_NAME#', 'fab fa-cc-paypal', '#PAID_USER_NAME#', 'User pay through Paypal & admin gets notified'),
(9, 'Stripe Payment Confirmation', 'stripe_payment', 'Payment Confirmation', 'Congratulations,<br/>\r\nWe have received your payment successfully.<br/>\r\nNow you are able to use #APP_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fab fa-stripe-s', '#APP_NAME#,#CYCLE_EXPIRED_DATE#,#PRODUCT_SHORT_NAME#,#SITE_URL#', 'User pay through Stripe & gets confirmation'),
(10, 'Stripe New Payment', 'stripe_new_payment_made', 'New Payment Made', 'New payment has been made by #PAID_USER_NAME#', 'fab fa-cc-stripe', '#PAID_USER_NAME#', 'User pay through Stripe & admin gets notified');


CREATE TABLE IF NOT EXISTS `fb_simple_support_desk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ticket_title` text NOT NULL,
  `ticket_text` longtext NOT NULL,
  `ticket_status` enum('1','2','3') CHARACTER SET latin1 NOT NULL DEFAULT '1' COMMENT '1=> Open. 2 => Closed, 3 => Resolved',
  `display` enum('0','1') NOT NULL DEFAULT '1',
  `support_category` int(11) NOT NULL,
  `last_replied_by` int(11) NOT NULL,
  `last_replied_at` datetime NOT NULL,
  `last_action_at` datetime NOT NULL COMMENT 'close resolve reopen etc',
  `ticket_open_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `support_category` (`support_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `fb_support_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `fb_support_category` (`id`, `category_name`, `user_id`, `deleted`) VALUES
(1, 'Billing', 1, '0'),
(2, 'Technical', 1, '0'),
(3, 'Query', 1, '0');


CREATE TABLE IF NOT EXISTS `fb_support_desk_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_reply_text` longtext NOT NULL,
  `ticket_reply_time` datetime NOT NULL,
  `reply_id` int(11) NOT NULL COMMENT 'ticket_id',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(15) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `add_ons_id` int(11) NOT NULL,
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  `header_text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `menu` (`id`, `name`, `icon`, `url`, `serial`, `module_access`, `have_child`, `only_admin`, `only_member`, `add_ons_id`, `is_external`, `header_text`) VALUES
(1, 'Dashboard', 'fa fa-fire', 'dashboard', 1, '', '0', '0', '0', 0, '0', ''),
(2, 'System', 'fas fa-laptop-code', '', 9, '', '1', '1', '0', 0, '0', 'Administration'),
(3, 'Subscription', 'fas fa-coins', '', 13, '', '1', '1', '0', 0, '0', ''),
(4, 'Social Accounts', 'far fa-user-circle', 'social_accounts', 5, '', '0', '0', '0', 0, '0', ''),
(5, 'Channel Manager', 'fas fa-tv', 'social_accounts/channel_manager', 17, '5,8,10,12,13', '0', '0', '0', 0, '0', 'Automation Tools'),
(6, 'Template Manager', 'fas fa-th-large', 'responder/template_manager', 21, '6,12', '0', '0', '0', 0, '0', ''),
(7, 'Search Engine', 'fas fa-search', '', 25, '14,15,16', '1', '0', '0', 0, '0', 'Search Tools'),
(8, 'Keyword Finder', 'fas fa-tags', 'search_engine/tag_keyword_scraper', 29, '', '0', '0', '0', 0, '0', ''),
(10, 'Auto Reply', 'fas fa-reply-all', 'responder/auto_reply_campaign', 39, '6', '0', '0', '0', 0, '0', 'Reporting'),
(11, 'Rank Tracking', 'fas fa-trophy', 'social_accounts/rank_tracking_settings', 44, '11', '0', '0', '0', 0, '0', ''),
(12, 'Link Wheel', 'fas fa-dharmachakra', 'link_wheel', 44, '18', '0', '0', '0', 0, '0', '');


CREATE TABLE IF NOT EXISTS `menu_child_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `menu_child_1` (`id`, `name`, `url`, `serial`, `icon`, `module_access`, `parent_id`, `have_child`, `only_admin`, `only_member`, `is_external`) VALUES
(1, 'Settings', 'admin/settings', 1, 'fas fa-sliders-h', '', 2, '0', '1', '0', '0'),
(2, 'Social Apps', 'social_apps/settings', 5, 'fas fa-hands-helping', '', 2, '0', '1', '0', '0'),
(3, 'Cron Job', 'cron_job/index', 9, 'fas fa-clipboard-list', '', 2, '0', '1', '0', '0'),
(4, 'Language Editor', 'multi_language/index', 13, 'fas fa-language', '', 2, '0', '1', '0', '0'),
(5, 'Add-on Manager', 'addons/lists', 17, 'fas fa-plug', '', 2, '0', '1', '0', '0'),
(6, 'Check Update', 'update_system/index', 25, 'fas fa-leaf', '', 2, '0', '1', '0', '0'),
(7, 'Package Manager', 'payment/package_manager', 1, 'fas fa-shopping-bag', '', 3, '0', '1', '0', '0'),
(8, 'User Manager', 'admin/user_manager', 5, 'fas fa-users', '', 3, '0', '1', '0', '0'),
(9, 'Announcement', 'announcement/full_list', 9, 'far fa-bell', '', 3, '0', '1', '0', '0'),
(10, 'Payment Accounts', 'payment/accounts', 13, 'far fa-credit-card', '', 3, '0', '1', '0', '0'),
(11, 'Earning Summary', 'payment/earning_summary', 17, 'fas fa-hand-holding-usd', '', 3, '0', '1', '0', '0'),
(12, 'Transaction Log', 'payment/transaction_log', 27, 'fas fa-history', '', 3, '0', '1', '0', '0'),
(16, 'Theme Manager', 'themes/lists', 21, 'fas fa-palette', '', 2, '0', '1', '0', '0'),
(17, 'Video', 'search_engine/video', 1, 'fab fa-youtube', '14', 7, '0', '0', '0', '0'),
(18, 'Channel', 'search_engine/channel', 5, 'fas fa-tv', '16', 7, '0', '0', '0', '0'),
(19, 'Playlist', 'search_engine/playlist', 9, 'far fa-list-alt', '15', 7, '0', '0', '0', '0');



CREATE TABLE IF NOT EXISTS `menu_child_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_child` int(11) NOT NULL,
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `add_ons_id` int(11) NOT NULL,
  `extra_text` enum('Monthly','Total','No Limit') NOT NULL DEFAULT 'Total',
  `limit_enabled` enum('0','1') NOT NULL DEFAULT '1',
  `bulk_limit_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `modules` (`id`, `module_name`, `add_ons_id`, `extra_text`, `limit_enabled`, `bulk_limit_enabled`, `deleted`) VALUES
(1, 'Social Accounts - Youtube', 0, 'Total', '1', '0', '0'),
(6, 'Automation Tools - Auto Reply Campaign', 0, 'Monthly', '1', '0', '0'),
(8, 'Automation Tools - Upload Video', 0, 'Monthly', '1', '0', '0'),
(9, 'Automation Tools - Edit Video', 0, 'Monthly', '1', '0', '0'),
(10, 'Automation Tools - Playlist Manager', 0, '', '0', '0', '0'),
(11, 'Ranking Tools - Rank Tracking', 0, 'Monthly', '1', '0', '0'),
(12, 'Automation Tools - Auto Like & Comment', 0, 'Monthly', '1', '0', '0'),
(13, 'Automation Tools - Auto Subscription', 0, 'Monthly', '1', '0', '0'),
(14, 'Search Tools - Video Search', 0, '', '0', '0', '0'),
(15, 'Search Tools - Playlist Search', 0, '', '0', '0', '0'),
(16, 'Search Tools - Channel Search', 0, '', '0', '0', '0'),
(18, 'Ranking Tools - Link Wheel', 0, '', '1', '1', '0');



CREATE TABLE IF NOT EXISTS `native_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(250) NOT NULL,
  `module_ids` varchar(250) NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `validity` int(11) NOT NULL,
  `validity_extra_info` varchar(255) NOT NULL DEFAULT '1,M',
  `is_default` enum('0','1') NOT NULL,
  `visible` enum('0','1') DEFAULT '1',
  `highlight` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `validity_extra_info`, `is_default`, `visible`, `highlight`, `deleted`) VALUES
(1, 'Trial', '12,6,13,9,10,8,18,11,16,17,15,14,1', '{"12":"10","6":"10","13":"10","9":"10","10":"0","8":"10","18":"10","11":"10","16":"0","17":"0","15":"0","14":"0","1":"3"}', '{"12":"0","6":"0","13":"0","9":"0","10":"0","8":"0","18":"10","11":"0","16":"0","17":"0","15":"0","14":"0","1":"0"}', 'Trial', 7, '1,W', '1', '0', '0', '0');




CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_payment_type` enum('manual','recurring') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `paypal_mode` enum('live','sandbox') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'live',
  `stripe_secret_key` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_publishable_key` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` enum('USD','AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','RUB','SGD','SEK','CHF','VND') COLLATE utf8mb4_unicode_ci NOT NULL,
  `manual_payment` enum('no','yes') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `manual_payment_instruction` mediumtext COLLATE utf8mb4_unicode_ci,
  `deleted` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE IF NOT EXISTS `paypal_error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `call_time` datetime DEFAULT NULL,
  `ipn_value` text,
  `error_log` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `social_app_facebook_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) NOT NULL,
  `app_id` varchar(250) NOT NULL,
  `app_secret` varchar(250) NOT NULL,
  `user_access_token` varchar(250) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `social_app_google_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `is_admin_app` enum('0','1') NOT NULL DEFAULT '1',
  `app_name` varchar(100) DEFAULT NULL,
  `api_key` text NOT NULL,
  `google_client_id` text,
  `google_client_secret` varchar(250) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_status` varchar(200) CHARACTER SET latin1 NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `paypal_email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `receiver_email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `country` varchar(100) CHARACTER SET latin1 NOT NULL,
  `payment_date` varchar(100) CHARACTER SET latin1 NOT NULL,
  `payment_type` varchar(100) CHARACTER SET latin1 NOT NULL,
  `transaction_id` varchar(150) CHARACTER SET latin1 NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cycle_start_date` date NOT NULL,
  `cycle_expired_date` date NOT NULL,
  `package_id` int(11) NOT NULL,
  `stripe_card_source` text CHARACTER SET latin1 NOT NULL,
  `paypal_txn_type` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `transaction_history_manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` varchar(255) NOT NULL,
  `paid_currency` char(4) NOT NULL,
  `additional_info` longtext NOT NULL,
  `filename` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thm_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `update_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `files` text NOT NULL,
  `sql_query` text NOT NULL,
  `update_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `usage_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(99) NOT NULL,
  `email` varchar(99) CHARACTER SET latin1 NOT NULL,
  `mobile` varchar(100) CHARACTER SET latin1 NOT NULL,
  `password` varchar(99) CHARACTER SET latin1 NOT NULL,
  `address` text NOT NULL,
  `user_type` enum('Member','Admin') CHARACTER SET latin1 NOT NULL,
  `status` enum('1','0') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `add_date` datetime NOT NULL,
  `purchase_date` datetime NOT NULL,
  `last_login_at` datetime NOT NULL,
  `activation_code` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `expired_date` datetime NOT NULL,
  `package_id` int(11) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `brand_logo` text,
  `brand_url` text,
  `vat_no` varchar(100) DEFAULT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK','BRL') CHARACTER SET latin1 NOT NULL DEFAULT 'USD',
  `time_zone` varchar(255) CHARACTER SET latin1 NOT NULL,
  `company_email` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `paypal_email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `paypal_subscription_enabled` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `last_payment_method` varchar(50) CHARACTER SET latin1 NOT NULL,
  `last_login_ip` varchar(25) CHARACTER SET latin1 NOT NULL,
  `social_app_google_config_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `address`, `user_type`, `status`, `add_date`, `purchase_date`, `last_login_at`, `activation_code`, `expired_date`, `package_id`, `deleted`, `brand_logo`, `brand_url`, `vat_no`, `currency`, `time_zone`, `company_email`, `paypal_email`, `paypal_subscription_enabled`, `last_payment_method`, `last_login_ip`) VALUES
(1, 'Admin', 'admin@admin.com', '', '259534db5d66c3effb7aa2dbbee67ab0', '', 'Admin', '1', '2019-08-25 18:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', 0, '0', '', NULL, NULL, 'USD', '', NULL, '', '0', '', '');


CREATE TABLE IF NOT EXISTS `user_login_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) NOT NULL,
  `user_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `user_email` varchar(150) CHARACTER SET latin1 NOT NULL,
  `login_time` datetime NOT NULL,
  `login_ip` varchar(25) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `current` enum('1','0') NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `video_position_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `youtube_position` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `video_position_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(250) NOT NULL,
  `youtube_video_id` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `mark_for_dashboard` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `last_process_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `youtube_video_id` (`youtube_video_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `youtube_channels_playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `channel_auto_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumbnails` varchar(255) CHARACTER SET latin1 NOT NULL,
  `itemCount` int(11) NOT NULL,
  `published_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`channel_id`),
  KEY `channel_auto_id` (`channel_auto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `youtube_channel_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `social_app_google_config_table_id` int(11) NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_email` varchar(255) NOT NULL,
  `access_token` text,
  `refresh_token` varchar(255) NOT NULL,
  `title` text,
  `description` text,
  `profile_image` text,
  `cover_image` text,
  `view_count` varchar(250) DEFAULT NULL,
  `video_count` varchar(250) DEFAULT NULL,
  `subscriber_count` varchar(250) DEFAULT NULL,
  `comment_count` int(11) NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `youtube_link_wheel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_auto_id` int(11) NOT NULL,
  `video_ids` longtext NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `wheel_name` varchar(200) DEFAULT NULL,
  `last_updated_at` datetime NOT NULL,
  `wheel_type` enum('open','close') NOT NULL DEFAULT 'close',
  `money_video_id` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `error` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `channel_auto_id` (`channel_auto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `youtube_link_wheel_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wheel_id` int(11) NOT NULL,
  `video_id` varchar(100) NOT NULL,
  `update_str` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wheel_id` (`wheel_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `youtube_video_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `channel_auto_id` int(11) NOT NULL,
  `channel_id` varchar(200) DEFAULT NULL,
  `video_id` varchar(200) DEFAULT NULL,
  `title` text,
  `image_link` text,
  `publish_time` varchar(200) DEFAULT NULL,
  `tags` text,
  `categoryId` int(11) DEFAULT NULL,
  `defaultLanguage` varchar(255) NOT NULL,
  `privacyStatus` varchar(255) DEFAULT NULL,
  `localizations` text,
  `liveBroadcastContent` varchar(250) DEFAULT NULL,
  `duration` varchar(250) DEFAULT NULL,
  `dimension` varchar(200) DEFAULT NULL,
  `definition` varchar(200) DEFAULT NULL,
  `caption` text,
  `licensedContent` text,
  `projection` varchar(250) DEFAULT NULL,
  `viewCount` int(11) DEFAULT NULL,
  `likeCount` int(11) DEFAULT NULL,
  `dislikeCount` int(11) DEFAULT NULL,
  `favoriteCount` int(11) DEFAULT NULL,
  `commentCount` int(11) DEFAULT NULL,
  `description` text,
  `backlink_status` enum('0','2','1') NOT NULL DEFAULT '0' COMMENT '0 = incomplete, 2 = submitted, 1 = completed',
  `rank_status` enum('0','1') NOT NULL DEFAULT '0',
  `backlink_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`),
  KEY `user_id` (`user_id`,`channel_id`),
  KEY `channel_auto_id` (`channel_auto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `youtube_video_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_id` varchar(200) DEFAULT NULL,
  `channel_auto_id` int(11) NOT NULL,
  `video_id` varchar(150) DEFAULT NULL,
  `title` text,
  `description` text,
  `tags` text,
  `category` varchar(100) DEFAULT NULL,
  `privacy_type` varchar(100) DEFAULT NULL,
  `link` varchar(150) DEFAULT NULL,
  `time_zone` varchar(100) DEFAULT NULL,
  `upload_time` datetime DEFAULT NULL,
  `upload_status` enum('0','1','2') NOT NULL COMMENT 'pending,processing,completed',
  `error` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`channel_id`),
  KEY `channel_auto_id` (`channel_auto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `transaction_history_manual`
  ADD CONSTRAINT `thm_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;



ALTER TABLE `auto_reply_campaign` ADD `youtube_api_called_at` DATETIME NOT NULL AFTER `error`;


ALTER TABLE `payment_config` CHANGE `currency` `currency` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD';
ALTER TABLE `youtube_channels_playlist` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `youtube_channels_playlist` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;