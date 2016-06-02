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
class Magestore_ProductWidget_Block_Adminhtml_Widget extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct() {
        $this->_controller = 'adminhtml_widget';
        $this->_blockGroup = 'productwidget';
        $this->_headerText = Mage::helper('productwidget')->__('Widget Manager');
        $this->_addButtonLabel = Mage::helper('productwidget')->__('Add Widget');
        parent::__construct();
    }

}