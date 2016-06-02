<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @copyright   Copyright (c) 2015 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create productwidget table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('productwidget/widget')};

CREATE TABLE {$this->getTable('productwidget/widget')} (
  `widget_id` int(11) unsigned NOT NULL auto_increment,
  `widget_name` varchar(255) NOT NULL default '' UNIQUE,
  `widget_type` varchar(100) NOT NULL default '',
  `store_id` smallint(3) NOT NULL default '0',
  `product_sku` varchar(255) NOT NULL default '',
  `created_time` datetime NULL,
  `views` int(11) unsigned NOT NULL default '0',
  `clicks` int(11) unsigned NOT NULL default '0',
  `total` decimal(10,2) NOT NULL default '0',
  `status` smallint(3) NOT NULL default '0',
  `last_update` datetime NULL,
  `expiration_date` date NULL,
  `extra` text NULL,
  `cache` text NULL,
  PRIMARY KEY (`widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('productwidget/action')};

CREATE TABLE {$this->getTable('productwidget/action')} (
  `action` varchar(100) NULL default '',
  `widget_id` int(11) unsigned NULL default '0',
  `domain` varchar(255) NULL default '',
  `ip_address` varchar(100) NULL default '',
  `created_time` datetime NULL,
  `order_id` int(11) unsigned NULL,
  `total` decimal(10,2) NULL DEFAULT '0',
  PRIMARY KEY (`action`,`widget_id`,`domain`,`ip_address`,`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



UPDATE {$this->getTable('core/config_data')}
SET `value` = '1'
WHERE `path` = 'admin/security/domain_policy_frontend';


");



$installer->endSetup();

