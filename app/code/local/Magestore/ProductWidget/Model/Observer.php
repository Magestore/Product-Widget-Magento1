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
 * ProductWidget Observer Model
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Model_Observer
{
	public function quoteSubmitAfter($observer){
		$order = $observer['order'];
		$cookie = Mage::getSingleton('core/cookie');
		$widgetId = $cookie->get('wid');
		if(!$widgetId)
			return;
		try {
			Mage::getResourceSingleton('productwidget/action')->addAction(
				array(
					'action' => Magestore_ProductWidget_Model_Action::ACTION_BUY,
					'widget_id' => $widgetId,
					'domain' => $cookie->get('domain'),
					'order_id' => $order->getId(),
					'total' => '1'
				)
			);
		} catch (Exception $e) {
			// ^_^ 
		} 
		return $this;
	}
}