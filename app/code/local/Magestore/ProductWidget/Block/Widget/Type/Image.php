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
 * Productwidget Block
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_Widget_Type_Image extends Magestore_ProductWidget_Block_Widget_Type
{

	const TEMPLATE_PATH = 'productwidget/widget/type/image.phtml';

	protected $_product;
    /**
     * prepare block's layout
     *
     * @return Magestore_ProductWidget_Block_Productwidget
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getHtml($widget){
		return $this->setData($widget->getData())
					->setData('widget',$widget)
					->setTemplate(self::TEMPLATE_PATH)
					->toHtml();
	}

    public function getProduct(){
		if(!$this->_product){
			$sku = $this->getData('product_sku');
			$this->_product = Mage::getModel('catalog/product')
				->setStoreId($this->getData('store_id'))
				->loadByAttribute('sku',$sku);
		}
		return $this->_product;
	}

	public function getProductImage($product = null){
		if(!$product){
			$product = $this->getProduct();
		}
		return Mage::helper('catalog/image')->init($product, 'thumbnail)');
	}

	public function getExtra($key){
		if($key){
			$extra = json_decode($this->getData('extra'),true);
			return $extra[$key];
		}
		return '';
	}
	
}