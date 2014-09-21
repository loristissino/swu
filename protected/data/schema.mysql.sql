-- License: http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
-- Author: Loris Tissino <loris.tissino@gmail.com>
-- Copyright: 2013-2014 Loris Tissino

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `assignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '1',
  `url` varchar(128) NOT NULL DEFAULT '',
  `duedate` datetime DEFAULT NULL,
  `grace` int(11) NOT NULL DEFAULT '0',
  `checklist` text,
  `language` varchar(6) NOT NULL DEFAULT 'en',
  `status` int(11) NOT NULL DEFAULT '1',
  `notification` tinyint(4) NOT NULL DEFAULT '1',
  `shown_since` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject`),
  KEY `url` (`url`),
  KEY `title` (`title`),
  KEY `duedate` (`duedate`),
  KEY `language` (`language`,`status`),
  KEY `notification` (`notification`),
  KEY `shown_since` (`shown_since`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `exercise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `linked_to` int(11) DEFAULT NULL,
  `code` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `duedate` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `mark` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `comment` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `assignment_student` (`assignment_id`,`student_id`),
  KEY `assignment_id` (`assignment_id`),
  KEY `student_id` (`student_id`),
  KEY `linked_to` (`linked_to`),
  KEY `status` (`status`),
  KEY `mark` (`mark`),
  KEY `duedate` (`duedate`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `md5` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `exercise_id` int(11) NOT NULL,
  `comment` text,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `content` text,
  `checked_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exercise_id` (`exercise_id`),
  KEY `published_at` (`published_at`),
  KEY `checked_at` (`checked_at`),
  KEY `uploaded_at` (`uploaded_at`),
  KEY `md5` (`md5`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

CREATE TABLE IF NOT EXISTS `mailtemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) DEFAULT NULL,
  `lang` varchar(7) DEFAULT NULL,
  `subject` text,
  `plaintext_body` text,
  `html_body` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_lang` (`code`,`lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `html` text,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `seen_at` timestamp NULL DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `sent_at` (`sent_at`),
  KEY `acknowledged_at` (`acknowledged_at`),
  KEY `seen_at` (`seen_at`),
  KEY `confirmed_at` (`confirmed_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `gender` varchar(1) NOT NULL DEFAULT '-',
  `email` varchar(128) DEFAULT NULL,
  `roster` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `email` (`email`),
  KEY `gender` (`gender`),
  KEY `roster` (`roster`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;


ALTER TABLE `exercise`
  ADD CONSTRAINT `exercise_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `exercise_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `exercise_ibfk_3` FOREIGN KEY (`linked_to`) REFERENCES `exercise` (`id`) ON UPDATE CASCADE;

ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`) ON UPDATE CASCADE;
