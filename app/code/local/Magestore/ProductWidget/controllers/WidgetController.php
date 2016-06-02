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
 * ProductWidget Index Controller
 *
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_WidgetController extends Mage_Core_Controller_Front_Action
{

    public function indexAction() {
        $params = $this->getRequest()->getParams();
        Mage::helper('productwidget')->addCookie($params);
        if($params['wid']){
            Mage::getResourceSingleton('productwidget/action')->addClickAction(
                array(
                    'widget_id' => $params['wid'],
                    'domain' => $params['domain'],
                )
            );
        }
        $this->widgetRedirect($params);
    }

    public function widgetRedirect($params) {
        $widgetId = $params['wid'];
        $productId = $params['pid'];
        $redirectCode = $params['rd'];
        $widget = Mage::getModel('productwidget/widget')->load($widgetId);
        $product = Mage::getModel('catalog/product')->setStoreId($widget->getStoreId())->load($productId);
        if ($redirectCode == 'product') {
            $this->getResponse()->setRedirect($product->getProductUrl());
        } elseif ($redirectCode == 'custom') {
            $customLink = base64_decode($this->getRequest()->getParam('customlink'));
            $this->getResponse()->setRedirect($customLink);
        } else {
            //cart or checkout page
            $productType = $product->getTypeId();
            $checkoutSession = Mage::getSingleton('checkout/session');
            if (in_array($productType, array(
                Mage_Catalog_Model_Product_Type::TYPE_SIMPLE
            , Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL
            ))) {
                $cart = Mage::getSingleton('checkout/cart');
                $cart->init();
                $cart->addProduct($product->getId())->save();
                $this->getResponse()->setRedirect(Mage::helper('productwidget')->getRedirectUrl($redirectCode, $widget->getStoreId()));
            } else {
                $this->getResponse()->setRedirect($product->getProductUrl());
            }
        }
    }

    public function viewAction() {
        $widgetId = Mage::helper('productwidget')->decode($this->getRequest()->getParam('wid'));
        $widget = Mage::getModel('productwidget/widget')->load($widgetId);
        $helper = Mage::helper('productwidget/config');
        if (!$widget->getId() || $widget->getStatus() == Magestore_ProductWidget_Model_Status::STATUS_DISABLED || !$helper
                ->isEnabled()
        ) {
            return;
        }
        $block = $this->getLayout()->createBlock('productwidget/productWidget')
            ->setTemplate('productwidget/productwidget.phtml');
        Mage::register('current_widget', $widget);
        $this->getResponse()->setBody($block->toHtml());
    }

    /**
     * render view for backend.
     */
    public function backendViewAction() {
        $params = $this->getRequest()->getParams();
        $widget = Mage::getModel('productwidget/widget')
            ->setData($params)
            ->setData('extra', json_encode($params));
        Mage::register('current_widget', $widget);
        $block = $this->getLayout()->createBlock('productwidget/productWidget')
            ->setTemplate('productwidget/productwidget.phtml');
        $this->getResponse()->setBody($block->toHtml());
    }

    /**
     *   $wid : "abc,acb,abc"
     *   $widgetArray = array('abc','acb','abc')
     *   $widgetAmount = array('abc'=>2,'acb'=>1)
     */
    public function countViewAction() {
        $params = $this->getRequest()->getParams();
        $wid = $params['wid'];

        $widgetArray = explode(',', $wid);
        $widgetAmount = array_count_values($widgetArray);
        foreach ($widgetAmount as $id => $amount) {
            $id = Mage::helper('productwidget')->decode($id);
            if (!is_numeric($id)) continue;
            Mage::getResourceSingleton('productwidget/action')->addAction(
                array(
                    'action' => 'view',
                    'total' => $amount,
                    'widget_id' => $id,
                    'domain' => $params['domain']
                )
            );
        }
        $this->getResponse()->setBody('');
    }

}