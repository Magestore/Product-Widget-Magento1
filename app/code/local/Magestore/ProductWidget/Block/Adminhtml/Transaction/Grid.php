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
 * Productwidget Grid Block
 *
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_Adminhtml_Transaction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('productwidgetGrid');
        $this->setDefaultSort('created_time');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Grid
     */
    protected function _prepareCollection() {
        $collection = Mage::getModel('productwidget/action')->getCollection();
        $collection->getSelect()
            ->joinLeft(array('w' => $collection->getTable('productwidget/widget')), 'main_table.widget_id = w.widget_id', array('widget_name'));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('created_time', array(
            'header' => Mage::helper('productwidget')->__('Action Time'),
            'width' => '200',
            'index' => 'created_time',
            'type' => 'datetime',
            'gmtoffset' => true
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('productwidget')->__('Action'),
            'width' => '200',
            'index' => 'action',
            'type' => 'options',
            'options' => Mage::getSingleton('productwidget/action')->getOptionArray()
        ));

        $this->addColumn('widget_name', array(
            'header' => Mage::helper('productwidget')->__('Widget Name'),
            'width' => '200',
            'index' => 'widget_name',
            'type' => 'options',
            'options' => Mage::getSingleton('productwidget/widget')->getNameOptionArray()
        ));
        $this->addColumn('domain', array(
            'header' => Mage::helper('productwidget')->__('Domain'),
            'width' => '200',
            'type' => 'options',
            'index'=>'domain',
            'options' => Mage::getSingleton('productwidget/action')->getDomainOptionArray()
        ));
        $this->addColumn('ip_address', array(
            'header' => Mage::helper('productwidget')->__('IP Address'),
            'width' => '200',
            'index' => 'ip_address',
            'type' => 'text',
        ));
        $this->addColumn('total', array(
            'header' => Mage::helper('productwidget')->__('Number of Actions'),
            'width' => '50',
            'index' => 'total',
            'type' => 'number',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('productwidget')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('productwidget')->__('XML'));

        return parent::_prepareColumns();
    }
}