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
 * Productwidget Adminhtml Block
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_Adminhtml_Report_General extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    { 
        $this->_controller = 'adminhtml_report_general';
        $this->_blockGroup = 'productwidget';
        $this->_headerText = Mage::helper('productwidget')->__('General Report');
        parent::__construct();
    }
    public function _afterToHtml($html){
        return parent::_afterToHtml($html);
    }
    public function getFilterUrl()
    {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/detail', array('_current' => true));
    }
} 