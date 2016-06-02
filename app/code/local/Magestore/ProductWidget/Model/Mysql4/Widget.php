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

/**
 * Productwidget Resource Model
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Model_Mysql4_Widget extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('productwidget/widget', 'widget_id');
    }

	/**
	 * UPDATE Views-Clicks-Sales for widget.
	 * @param $widget
	 */
    public function updateInformation($widget){
    	if(!$widget->getId())
    		return;
    	$widgetId = $widget->getId();
    	$widgetTable = $this->getMainTable();
    	$actionTable = $this->getTable('productwidget/action');
    	$lastUpdate = $widget->getLastUpdate();
    	$now = date("Y-m-d H:i:s");
    	$write = $this->_getWriteAdapter();
    	$query = "
			UPDATE 
			  `$widgetTable` 
			SET
			  `views` =
				  IFNULL((SELECT SUM(`total`) FROM `$actionTable` WHERE `action` = 'view' AND `widget_id` = '$widgetId'  GROUP BY `widget_id`),0),
			  `clicks` =
				  IFNULL((SELECT SUM(`total`) FROM `$actionTable` WHERE `action` = 'click' AND `widget_id` = '$widgetId'  GROUP BY `widget_id`),0),
			  `total` =
				  IFNULL((SELECT SUM(`total`) FROM `$actionTable` WHERE `action` = 'buy' AND `widget_id` = '$widgetId'  GROUP BY `widget_id`),0) ,
			  `last_update` = '$now'
			WHERE `widget_id` = '$widgetId' ;
    	";
    	try{
    		$write->query($query);
    	}catch(Exception $e){
    	}
        return;
    }
}
