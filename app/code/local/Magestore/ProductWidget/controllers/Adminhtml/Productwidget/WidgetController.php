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
class Magestore_ProductWidget_Adminhtml_Productwidget_WidgetController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('productwidget');
    }

    /**
     * init layout and set active for current menu
     *
     * @return Magestore_ProductWidget_Adminhtml_WidgetController
     */
    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('productwidget/widget')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Items Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
        return $this;
    }

    /**
     * index action
     */
    public function indexAction() {
        Mage::helper('productwidget')->updateAllWidgetInformation();
        if(version_compare(Mage::getVersion(), '1.9.0.0', '>=')){
            if (Mage::getStoreConfig('admin/security/domain_policy_frontend') != 1) {
                $configUrl = $this->getUrl('*/system_config/edit', array('section' => 'admin'));
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('productwidget')->__('To show widgets on other page, please set configuration "Allow Magento Frontend to run in frame" to "Enabled" at %s.', "<a href='$configUrl'>here</a>")
                );
            }
        }
        $this->_initAction()
            ->renderLayout();
    }

    public function _initWidget() {
        $widgetId = (int)$this->getRequest()->getParam('id');
        $widget = Mage::getModel('productwidget/widget');
        if (!$widgetId) {
            if ($type = $this->getRequest()->getParam('widget_type')) {
                $widget->setWidgetType($type);
            }
            if ($storeId = $this->getRequest()->getParam('store_id')) {
                $widget->setStoreId($storeId);
            }
        } else {
            $widget->load($widgetId);
        }
        Mage::register('widget', $widget);
        return $widget;
    }

    public function newAction() {
        $this->_forward('edit');
    }

    /**
     * view and edit item action
     */
    public function editAction() {
        $model = $this->_initWidget();
        Mage::register('productwidget_data', $model);
        Mage::register('back', $this->getRequest()->getParam('back'));
        $this->loadLayout();
        $this->_setActiveMenu('productwidget/widget');

        $this->_addBreadcrumb(
            Mage::helper('adminhtml')->__('Widget Manager'),
            Mage::helper('adminhtml')->__('Widget Manager')
        );
        $this->_addBreadcrumb(
            Mage::helper('adminhtml')->__('Widget News'),
            Mage::helper('adminhtml')->__('Widget News')
        );
        $this->renderLayout();
    }

    /**
     * save item action
     */
    public function saveAction() {
        $helper = Mage::helper('productwidget');
        $model = Mage::getModel('productwidget/widget');
        if ($data = $this->getRequest()->getPost()) {
            if (isset($_FILES['widget_image']['name']) && $_FILES['widget_image']['name'] != '') {
                try {
                    $uploader = new Varien_File_Uploader('widget_image');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $path = Mage::getBaseDir('media') . DS . 'productwidget' . DS;
                    $result = $uploader->save($path, $_FILES['widget_image']['name']);
                    $data['widget_image'] = 'productwidget/' . $result['file'];
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;;
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            } else {
                $data['widget_image'] = $data['current_image'];
            }
            if ($id = $this->getRequest()->getParam('id')) {
                $model->load($id);
            }
            $data = $helper->removeFormKey($data);
            $model->setData('widget_type', $data['widget_type'])
                ->setData('widget_name', $data['widget_name'])
                ->setData('store_id', $data['store_id'])
                ->setData('product_sku', $data['product_sku'])
                ->setData('extra', json_encode($data));
            if (!$model->getId()) {
                $model->setStatus(1);
            }
            try {
                if ($model->getCreatedTime() == NULL) {
                    $model->setCreatedTime(now());
                    $model->setId(null);
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productwidget')->__('Widget Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($back = $this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), 'back' => $back));
                    return;
                }
                if ($duplicate = $this->getRequest()->getParam('duplicate')) {
                    $this->_redirect('*/*/duplicate', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('productwidget')->__('Unable to find item to save')
        );
        $this->_redirect('*/*/');
    }

    public function duplicateAction(){
        $id = $this->getRequest()->getParam('id');
        $widget = Mage::getModel('productwidget/widget')->load($id);
        $newWidget = $widget->duplicate();
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productwidget')->__('Widget Item was successfully duplicated'));
        $this->_redirect('*/*/edit', array('id' => $newWidget->getId()));
    }

    /**
     * delete item action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productwidget/widget');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Item was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction() {
        $productwidgetIds = $this->getRequest()->getParam('productwidget');
        if (!is_array($productwidgetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productwidgetIds as $productwidgetId) {
                    $productwidget = Mage::getModel('productwidget/widget')->load($productwidgetId);
                    $productwidget->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                        count($productwidgetIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction() {
        $productwidgetIds = $this->getRequest()->getParam('productwidget');
        if (!is_array($productwidgetIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productwidgetIds as $productwidgetId) {
                    Mage::getSingleton('productwidget/widget')
                        ->load($productwidgetId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($productwidgetIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function getWidgetFormAction() {
        $html = $this->getLayout()->createBlock('productwidget/adminhtml_widget_form')->toHtml();
        $this->getResponse()->setBody($html);
    }

    public function autoCompleteAction() {
        $key = trim($this->getRequest()->getParam('q'));
        $storeId = $this->getRequest()->getParam('sid');
        $result = array();
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addFieldToFilter('status', '1')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'name', 'like' => "%$key%"),
                    array('attribute' => 'sku', 'like' => "%$key%")
                )
            )
            ->addStoreFilter($storeId)
            ->addAttributeToFilter('visibility', array('neq' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE))
            ->setCurPage(1)
            ->setPageSize(10);
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        foreach ($collection as $product) {
            $productLabel = '(' . $product->getSku() . ')' . $product->getName();
            $result[] = array(
                'value' => $product->getSku(),
                'label' => $productLabel
            );
        }
        $this->getResponse()->setBody(json_encode($result));
    }

    public function getReviewAction() {
        $params = $this->getRequest()->getParams();
        switch ($params['widget_type']) {
            case Magestore_ProductWidget_Model_Widget_Type::TYPE_PRODUCT:
                $block = $this->getLayout()->createBlock('productwidget/adminhtml_widget_type_product')
                    ->setData('params', $params)
                    ->setTemplate('productwidget/widget/type/product.phtml');
                $result = array('html' => $block->toHtml());
                $this->getResponse()->setBody(json_encode($result));
                break;
            default:
                break;
        }
    }

    public function showGenerateCodeAction() {
        $params = $this->getRequest()->getParams();
        $block = $this->getLayout()->createBlock('productwidget/adminhtml_widget_generate')
            ->setData('widget_id', $params['id']);
        $this->getResponse()->setBody($block->toHtml());
    }
}