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
class Magestore_ProductWidget_Block_Widget_Type_Product extends Magestore_ProductWidget_Block_Widget_Type
{

	// const TEMPLATE_PATH = 'productwidget/widget/type/product.phtml';

	protected $_products;
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
		$block = $this->setData($widget->getData())
					->setData('widget',$widget);
		$widgetDesign = $widget->getExtra('design');
		$block->setTemplate("productwidget/widget/type/product_$widgetDesign.phtml");
		return $block->toHtml();
	}

    public function getProducts(){
		if(!$this->_products){
			$sku = $this->getData('product_sku');
			$sku = explode(',', $sku);
			$products = array();
			foreach ($sku as $key => $value) {
				if(!$value)continue;
				$product = Mage::getModel('catalog/product')
					->setStoreId($this->getData('store_id'))
					->loadByAttribute('sku',$value);
				if($product->getId())
					$products[] = $product;
			}
			$this->_products = $products;
		}
		return $this->_products;
	}

	public function getProductImage($product = null){
		if(!$product){
			$product = $this->getProduct();
		}
		return Mage::helper('catalog/image')->init($product, 'image')->resize($this->getExtra('width'));
	}

	public function getProductPrice($product = null){
		return Mage::app()->getStore($this->getWidget()->getStoreId())->formatPrice($product->getFinalPrice(),false);
	}

	/**
	 * Format price for this widget's store.
	 * @param $price
	 * @return mixed
	 */
	public function formatPrice($price){
		if(!$price){
			return null;
		}
		return Mage::app()->getStore($this->getWidget()->getStoreId())->formatPrice($price,false);
	}

	/**
	 *
	 */
	public function getSpecialOffer($product){
		$rate = 0;
		$price = $product->getPrice();
		$finalPrice = $product->getFinalPrice();
		$discount = $price- $finalPrice;
		if($discount){
			$rate = round($discount/$price, 2, PHP_ROUND_HALF_UP) * 100 .'%';
		}
		return Mage::helper('productwidget')->__("(%s off)",$rate);
	}
	
	public function getButtonText(){
		$default = Mage::helper('productwidget')->__('Buy Now');
		$buttonText= $this->getExtra('button_text');
		return $buttonText ? $buttonText : $default;
	}

	public function showPrice($product){
		$price = $product->getFinalPrice();
		$type = $product->getTypeID();
		if(!$price && in_array($type, array(
				Mage_Catalog_Model_Product_Type::TYPE_BUNDLE
				,Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE
				,Mage_Catalog_Model_Product_Type::TYPE_GROUPED
			)))
			return false;
		return true;
	}

	public function getDescription($product){
		$description = $product->getShortDescription();
		$limit = Mage::helper('productwidget/config')->getLimitDescription();
		return Mage::helper('productwidget')->limitString($description,$limit);
	}

	public function getExtra($key){
		if($key){
			$extra = json_decode($this->getData('extra'),true);
			return $extra[$key];
		}
		return '';
	}
	
}