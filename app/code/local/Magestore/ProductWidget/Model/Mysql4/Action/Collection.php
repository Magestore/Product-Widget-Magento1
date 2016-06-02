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
 * Productwidget Resource Collection Model
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Model_Mysql4_Action_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productwidget/action');
    }

    /**
     * Add Filter for report.
     * @param string $filter
     * @return $this
     */
    public function addFilter($filter){
    	$period = $filter['period_type'];
    	if($filter['period_type'] == 'day')
    		$period = 'date';
        $format = "%Y-%m-%d %H:%i:%s";
        $offset= Mage::getModel('core/date')->getGmtOffset('hours');
        $from = $filter['from'] . " 00:00:01";
    	$to = $filter['to'] . " 23:59:59";
        switch ($filter['period_type']) {
            case 'hour' :
                $format = "%Y-%m-%d %H";
                break;
            case 'day' :
                $format = "%Y-%m-%d";
                break;
            case 'month':
                $format = "%Y-%m";
                break;
            case 'year':
                $format = "%Y";
                break;
        }
        $localTime = "DATE_FORMAT(`main_table`.`created_time` + INTERVAL $offset HOUR,'$format')";
        $this->getSelect()
            ->columns('SUM(`main_table`.`total`) as sum_total')
            ->columns("$localTime as new_created_time")
            ->where("$localTime BETWEEN DATE_FORMAT('$from','$format') AND DATE_FORMAT('$to','$format')")
            ->where("action = ?",$filter['action'])
//            ->group(array('domain','main_table.widget_id','action',"$period(DATE_FORMAT(`main_table`.`created_time` - INTERVAL $offset HOUR,'$format'))"));
            ->group(array('main_table.widget_id','action',"$localTime"));
        return $this;
    }

    /**
     * @return Varien_Db_Select
     * @throws Zend_Db_Select_Exception
     */
    public function getSelectCountSql() {
        $this->_renderFilters();
        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        if (count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) {
            $countSelect->reset(Zend_Db_Select::GROUP);
            $countSelect->distinct(true);
            $group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
            $countSelect->columns("COUNT(DISTINCT " . implode(", ", $group) . ")");
        } else {
            $countSelect->columns('COUNT(*)');
        }
        return $countSelect;
    }
}