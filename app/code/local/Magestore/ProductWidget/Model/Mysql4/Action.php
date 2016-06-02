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
class Magestore_ProductWidget_Model_Mysql4_Action extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct() {
        $this->_init('productwidget/action', "`action`,`widget_id`,`domain`,`ip_address`,`created_time`");
    }

    /**
     * Add Action record
     * keys : 'widget_id' and 'domain'
     * @param $data
     * @throws Exception
     */
    public function addAction($data) {
        $write = $this->_getWriteAdapter();
        $write->beginTransaction();
        $table = $this->getMainTable();
        try {
            $action = $data['action'];
            $widgetId = $data['widget_id'];
            $domain = $data['domain'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $total = $data['total'];
            $createdTime = date("Y-m-d H:01:00");
            $sql = "
            INSERT INTO `$table` (`action`, `widget_id`, `domain`, `ip_address`, `created_time`,`total`)
            VALUES ('$action','$widgetId','$domain','$ip','$createdTime',$total)
            ON DUPLICATE KEY UPDATE `total` = `total` + $total
            ";
            // $write->insertMultiple($this->getTable('productwidget/action'), $data);
            $write->query($sql);
            $write->commit();
        } catch (Exception $e) {
            $write->rollback();
            throw $e;
        }
    }

    /**
     * Add Click action
     * keys : 'widget_id` and 'domain
     * @param $data
     * @return $this
     * @throws Exception
     */
    public function addClickAction($data) {
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        if ($this->canClick($data)) {
            $data['action'] = 'click';
            $data['total'] = '1';
            $this->addAction($data);
        }
        return $this;
    }

    /**
     * @param $ip
     * @param $widgetId
     * @param $domain
     * @return bool
     */
    public function canClick($data) {
        $clickTime = Mage::helper('productwidget/config')->getClickTime();
        $checkTime = time() - 60 * $clickTime;
        $read = $this->_getReadAdapter();
        $selectSql = $read->select()->reset()
            ->from(array('main' => $this->getMainTable()), array('total'))
            ->where('action = ?', 'click')
            ->where('ip_address = ?', $data['ip_address'])
            ->where('widget_id = ?', $data['widget_id'])
            ->where('domain = ?', $data['domain'])
            ->where('created_time > ?', date('Y-m-d H:i:s', $checkTime));
        $actions = $read->fetchOne($selectSql);
        if (empty($actions)) {
            return true;
        }
        return false;
    }

    /**
     * Get View-Click-Sale ( VSC ) report.
     * $type : 'domain' or 'widget_id'
     * @param $type
     * @param $filter
     * @return array
     */
    public function getVCSReport($type, $filter) {
        $period = $filter['period_type'];
        if ($filter['period_type'] == 'day')
            $period = 'date';
        $from = $filter['from'] . " 00:00:01";
        $to = $filter['to'] . " 23:59:59";
        $typeValue = $filter[$type];
        $offset = Mage::getModel('core/date')->getGmtOffset('hours');
        $table = $this->getMainTable();
        $read = $this->_getReadAdapter();
        $data = array();
        $dataDate = "";
        switch ($filter['period_type']) {
            case 'hour' :
                $dataDate = 'm-d H';
                $format = "%Y-%m-%d %H";
                break;
            case 'day' :
                $dataDate = 'Y-m-d';
                $format = "%Y-%m-%d";
                break;
            case 'month':
                $dataDate = 'Y-m';
                $format = "%Y-%m";
                break;
            case 'year':
                $dataDate = 'Y';
                $format = "%Y";
                break;
        }
        $localTime = "DATE_FORMAT(`created_time` + INTERVAL $offset HOUR,'$format')";
        while (strtotime($from) <= strtotime($to)) {
            $query = "
			 SELECT 
			  (SELECT 
				SUM(`total`) 
			  FROM
				`$table`
			  WHERE `action` = 'view' AND `$type` = '$typeValue' AND $localTime = DATE_FORMAT('$from','$format')
			  GROUP BY $localTime) AS `view`,
			  (SELECT 
				SUM(`total`) 
			  FROM
				`$table`
			  WHERE `action` = 'click' AND `$type` = '$typeValue' AND $localTime = DATE_FORMAT('$from','$format')
			  GROUP BY $localTime) AS `click`,
			  (SELECT 
				SUM(`total`) 
			  FROM
				`$table`
			  WHERE `action` = 'buy' AND `$type` = '$typeValue' AND $localTime = DATE_FORMAT('$from','$format')
			  GROUP BY $localTime) AS `sales`
			";
            $result = $read->fetchRow($query);
            $data[] = array(date($dataDate, strtotime($from)), (int)$result['view'], (int)$result['click'], (int)$result['sales']);
            $datetime = new DateTime($from);
            $datetime->modify("+1 " . $filter['period_type']);
            $from = $datetime->format('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Delete all action record of the widget.
     * @param $widgetId
     * @throws Exception
     */
    public function deleteAllWidgetAction($widgetId) {
        if (!$widgetId)
            return;
        $write = $this->_getWriteAdapter();
        $actionTable = $this->getMainTable();
        $write->beginTransaction();
        $query = "DELETE FROM $actionTable WHERE `widget_id` = $widgetId";
        try {
            $write->query($query);
            $write->commit();
        } catch (Exception $e) {
            $write->rollback();
            throw $e;
        }
    }
}