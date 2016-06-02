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
 * Productwidget Adminhtml Controller
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Adminhtml_Productwidget_TransactionController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('productwidget');
    }
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_ProductWidget_Adminhtml_ReportController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('productwidget/transaction');
        return $this;
    }
 
    public function indexAction(){
        $this->_initAction();
        $this->renderLayout();
    }

    public function exportCsvAction() {
        $fileName = 'productwidget_report.csv';
        $content = $this->getLayout()->createBlock('productwidget/adminhtml_transaction_grid')
        ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'productwidget_report.xml';
        $content = $this->getLayout()->createBlock('productwidget/adminhtml_transaction_grid')
        ->getXml();

        $this->_prepareDownloadResponse($fileName, $content);
    }

}