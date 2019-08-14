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
DROP TABLE IF EXISTS {$this->getTable('voodoo_number')};
CREATE TABLE {$this->getTable('voodoo_number')} (
  `number_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL DEFAULT '',
  `sms_number` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`number_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
//START Add order attribute by Branko Ajzele
$sql = "SELECT entity_type_id FROM ".$this->getTable('eav_entity_type')." WHERE entity_type_code='order'";
$row = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow($sql);

$attribute  = array(
    'type'			=> 'text',
    'label'			=> 'voodoo',
    'visible'		=> false,
    'required'		=> false,
    'user_defined'	=> false,
    'searchable'	=> false,
    'filterable'	=> false,
    'comparable'	=> false,
);

$installer->addAttribute($row['entity_type_id'], 'voodoo', $attribute);
//END Add customer attribute Branko Ajzele

$installer->endSetup(); 