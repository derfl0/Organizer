<?php
/**
 * This file was generated with Studip Dev Tools
 */

class SetupDb extends DBMigration {
    function up() {
DBManager::get()->exec("CREATE TABLE IF NOT EXISTS `organizer_asset_items` (
  `item_id` char(32) NOT NULL DEFAULT '' COMMENT 'it',
  `name` varchar(255) DEFAULT NULL,
  `asset_id` char(32) NOT NULL DEFAULT '',
  `assign_id` char(32) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
)");
    DBManager::get()->exec("CREATE TABLE IF NOT EXISTS `organizer_assets` (
  `asset_id` char(32) NOT NULL DEFAULT '',
  `course_id` char(32) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `visible` tinyint(4) NOT NULL DEFAULT '0',
  `assignable` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`asset_id`),
  KEY `course_id` (`course_id`)
)");
    DBManager::get()->exec("CREATE TABLE IF NOT EXISTS `organizer_groups` (
  `group_id` char(32) NOT NULL DEFAULT '',
  `course_id` char(32) NOT NULL DEFAULT '',
  `chdate` int(11) DEFAULT NULL,
  PRIMARY KEY (`group_id`),
  KEY `course_id` (`course_id`)
)");
    DBManager::get()->exec("CREATE TABLE IF NOT EXISTS `organizer_groupuser` (
  `group_id` char(32) NOT NULL DEFAULT '',
  `user_id` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`group_id`,`user_id`)
)");
    DBManager::get()->exec("CREATE TABLE IF NOT EXISTS `organizer_invites` (
  `user1` char(32) NOT NULL DEFAULT '',
  `user2` char(32) NOT NULL DEFAULT '',
  `course_id` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`user1`,`user2`,`course_id`)
)");
    DBManager::get()->exec("CREATE TABLE IF NOT EXISTS `organizer_settings` (
  `course_id` char(32) NOT NULL DEFAULT '',
  `min_size` int(11) NOT NULL DEFAULT '3',
  `max_size` int(11) NOT NULL DEFAULT '4',
  `locked` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`course_id`)
)");
    }
    function down() {
DBManager::get()->exec("DROP TABLE IF EXISTS `organizer_asset_items`");
    DBManager::get()->exec("DROP TABLE IF EXISTS `organizer_assets`");
    DBManager::get()->exec("DROP TABLE IF EXISTS `organizer_groups`");
    DBManager::get()->exec("DROP TABLE IF EXISTS `organizer_groupuser`");
    DBManager::get()->exec("DROP TABLE IF EXISTS `organizer_invites`");
    DBManager::get()->exec("DROP TABLE IF EXISTS `organizer_settings`");
    }
}