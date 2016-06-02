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
class Magestore_ProductWidget_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function getReviewAction(){
        $params = $this->getRequest()->getParams();
        switch ($params['widget_type']) {
            case Magestore_ProductWidget_Model_Widget_Type::TYPE_PRODUCT:
                $block = $this->getLayout()->createBlock('productwidget/widget_type_product')
                        ->setData('params',$params)
                        ->setTemplate('productwidget/widget/type/product.phtml');
                $result = array('html' => $block->toHtml());
                $this->getResponse()->setBody(json_encode($result));
                break;
            default:
                break;
        }
    }


}