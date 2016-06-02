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
 * Productwidget Model
 *
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Model_Widget extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'productwidget_widget';
    protected $_optionArray;
    protected $_nameOptionArray;

    public function _construct() {
        parent::_construct();
        $this->_init('productwidget/widget');
    }

    public function getTypeBlock() {
        $type = $this->getWidgetType();
        return Mage::getBlockSingleton("productwidget/widget_type_$type");
    }

    public function getTypeInstance() {
        $type = $this->getWidgetType();
        return Mage::getSingleton("productwidget/widget_type_$type");
    }

    public function getHtml() {
        $typeBlock = $this->getTypeBlock();
        return $typeBlock->getHtml($this);
    }

    public function validateProduct() {
        return $this->getTypeInstance()->validateProduct($this);
    }

    public function getWidgetLink($product) {
        return $this->getTypeInstance()->getWidgetLink($this, $product);
    }

    public function getButtonLink($product) {
        return $this->getTypeInstance()->getButtonLink($this, $product);
    }

    public function getExtra($key) {
        if ($key) {
            $extra = json_decode($this->getData('extra'), true);
            return $extra[$key];
        }else{
            return $this->getData('extra');
        }
    }

    public function getOptionArray() {
        if (!$this->_optionArray) {
            $result = array();
            $collection = $this->getCollection();
            foreach ($collection as $widget) {
                $result[$widget->getId()] = $widget->getWidgetName();
            }
            $this->_optionArray = $result;
        }
        return $this->_optionArray;
    }

    public function getNameOptionArray() {
        if (!$this->_nameOptionArray) {
            $result = array();
            $collection = $this->getCollection();
            foreach ($collection as $widget) {
                $result[$widget->getWidgetName()] = $widget->getWidgetName();
            }
            $this->_nameOptionArray = $result;
        }
        return $this->_nameOptionArray;
    }


    /**
     * Delete all action record of this widget. After this widget is deleted.
     * @return $this
     */
    public function _afterDeleteCommit() {
        if (!Mage::helper('productwidget/config')->deleteAllActionRecord())
            return $this;
        $widgetId = $this->getId();
        Mage::getResourceSingleton('productwidget/action')->deleteAllWidgetAction($widgetId);
        return $this;
    }


    public function duplicate() {
        $name = trim($this->getWidgetName());
        $newWidget = Mage::getModel('productwidget/widget')
            ->setWidgetName($name . '_' . date('His', Mage::getModel('core/date')->timestamp()))
            ->setWidgetType($this->getWidgetType())
            ->setStoreId($this->getStoreId())
            ->setProductSku($this->getProductSku())
            ->setCreatedTime(date('Y-m-d H:i:s',time()))
            ->setLastUpdate(date('Y-m-d H:i:s',time()))
            ->setStatus(1)
            ->setExtra($this->getData('extra'));
        $newWidget->save();
        return $newWidget;
    }
}