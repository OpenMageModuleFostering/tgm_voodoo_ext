<?php
//this is the installer file..
//here we create the table for voodoo sms extension
// it contains some fields which are listed below
/*
 *voodoo_id: primary key for the table
 * order_id: store order id for sms message
 * from: where the
 */
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('voodoo')};
CREATE TABLE {$this->getTable('voodoo')} (
  `voodoo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL DEFAULT '',
  `from` varchar(255) NOT NULL DEFAULT '',
  `to` varchar(255) NOT NULL DEFAULT '',
  `sms_message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '',
  `status_message` varchar(255) NOT NULL DEFAULT '',
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`voodoo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 