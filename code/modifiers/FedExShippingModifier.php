<?php
/**
 * Flat shipping to specific countries.
 *
 * @package shop
 * @subpackage modifiers
 */
class FedExShippingModifier extends ShippingModifier {
	
	function required(){
		return true;
	}

	function value($incoming){
		
		if($this->Order() && $this->Order()->ShippingService){
			$service = json_decode(unserialize($this->Order()->ShippingService));
			return $service->Amount;
		}
		
		return 0;
	}
	
	function ShowInTable(){
		return true;
	}

	function TableTitle() {
		return 'Shipping - FedEx';
		//return $this->APIObject ? 'API Based Shipping Modifier: '.$this->APIObject->Type : 'API Based Shipping Modifier';
	}
	
}