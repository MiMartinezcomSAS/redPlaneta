ALTER TABLE 'planet_planeta'.'yq6g5_community_users' 
ADD COLUMN 'country' INT NULL AFTER 'search_email';

ALTER TABLE `planet_planeta`.`yq6g5_users`
ADD COLUMN `country` INT NULL AFTER `resetCount`;


/***********************************************************************************/

/**
 * Clasificados
 */

 CREATE TABLE `yq6g5_community_clasificados` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL COMMENT 'parent for recurring clasificado',
  `catid` int(11) unsigned NOT NULL,
  `contentid` int(11) unsigned DEFAULT '0' COMMENT '0 - if type == profile, else it hold the group id',
  `type` varchar(255) NOT NULL DEFAULT 'profile' COMMENT 'profile, group',
  `title` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `summary` text NOT NULL,
  `description` text,
  `creator` int(11) unsigned NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `permission` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0 - Open (Anyone can mark attendence), 1 - Private (Only invited can mark attendence)',
  `avatar` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `cover` text NOT NULL,
  `invitedcount` int(11) unsigned DEFAULT '0',
  `confirmedcount` int(11) unsigned DEFAULT '0' COMMENT 'treat this as member count as well',
  `declinedcount` int(11) unsigned DEFAULT '0',
  `maybecount` int(11) unsigned DEFAULT '0',
  `wallcount` int(11) unsigned DEFAULT '0',
  `ticket` int(11) unsigned DEFAULT '0' COMMENT 'Represent how many guest can be joined or invited.',
  `allowinvite` tinyint(1) unsigned DEFAULT '1' COMMENT '0 - guest member cannot invite thier friends to join. 1 - yes, guest member can invite any of thier friends to join.',
  `created` datetime DEFAULT NULL,
  `hits` int(11) unsigned DEFAULT '0',
  `published` int(11) unsigned DEFAULT '1',
  `latitude` float(10,6) NOT NULL DEFAULT '255.000000',
  `longitude` float(10,6) NOT NULL DEFAULT '255.000000',
  `offset` varchar(5) DEFAULT NULL,
  `allday` tinyint(11) NOT NULL DEFAULT '0',
  `repeat` varchar(50) DEFAULT NULL COMMENT 'null,daily,weekly,monthly',
  `repeatend` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_creator` (`creator`),
  KEY `idx_period` (`startdate`,`enddate`),
  KEY `idx_type` (`type`),
  KEY `idx_catid` (`catid`),
  KEY `idx_published` (`published`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `yq6g5_community_clasificados_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `yq6g5_community_clasificados_members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clasificadoid` int(11) unsigned NOT NULL,
  `memberid` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '[Join / Invite]: 0 - [pending approval/pending invite], 1 - [approved/confirmed], 2 - [rejected/declined], 3 - [maybe/maybe], 4 - [blocked/blocked]',
  `permission` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '1 - creator, 2 - admin, 3 - member',
  `invited_by` int(11) unsigned DEFAULT '0',
  `approval` tinyint(1) unsigned DEFAULT '0' COMMENT '0 - no approval required, 1 - required admin approval',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_clasificadoid` (`clasificadoid`),
  KEY `idx_status` (`status`),
  KEY `idx_invitedby` (`invited_by`),
  KEY `idx_permission` (`clasificadoid`,`permission`),
  KEY `idx_member_clasificado` (`clasificadoid`,`memberid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SELECT * FROM lumni_abril_cp.quiz_opciones;