<?php
//@TODO Modify form to use a dropdown for the state instead of a text input
use FedEx\RateService;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;
use FedEx\RateService\SimpleType\ReturnedRateType;

class FedExShippingModifier extends ShippingModifier {
    private static $service_type="FEDEX_GROUND";
    private static $default_charge=0;
    
    private static $api_key;
    private static $api_password;
    private static $meter_number;
    private static $account_number;
    
    private static $test_mode=false;
    private static $test_api_key;
    private static $test_api_password;
    private static $test_meter_number;
    private static $test_account_number;
    
    private static $origin_address;
    private static $origin_address_line2;
    private static $origin_city;
    private static $origin_state_province_code;
    private static $origin_postal_code;
    private static $origin_country_code;
    
    
    /**
     * Produces a title for use in templates.
     * @return string
     */
    public function TableTitle() {
        $title=_t('FedExShippingModifier.SINGULARNAME', '_Shipping ({method})', array('method'=>_t('FedExShippingModifier.'.self::config()->service_type)));
        
        $this->extend('updateTableTitle', $title);
        return $title;
    }
    
    /**
     * Calculates the value of the fedex shipping modifier. If the order information has changed or is not present in the session a call is made to the api to request the rates.
     * @return {float} Cost of the shipping
     */
    public function value($subtotal=0) {
        if($this->Order()->Items()->count()==0) {
            return 0;
        }
        
        
        $orderItems=implode(',', $this->Order()->Items()->column('ID'));
        $orderItemsCount=implode(',', $this->Order()->Items()->column('Quantity'));
        
        if($this->Order()->ShippingAddress && $this->Order->ShippingAddress->exists()) {
            $destination=$this->Order->ShippingAddress;
        
            $shippingAddress=array(
                                    'StreetLines'=>array($destination->Address),
                                    'City'=>$destination->City,
                                    'StateOrProvinceCode'=>$destination->State,
                                    'PostalCode'=>$destination->PostalCode,
                                    'CountryCode'=>$destination->Country
                                );
            
            $addrLine2=$destination->AddressLine2;
        }
        
        
        if($this->Amount>0 && $orderItems.'|'.$orderItemsCount.'|'.implode(',', $shippingAddress)==Session::get('FedExShipping_'.$this->Order()->ID.'.orderitems')) {
            return $this->Amount;
        }
        
        
        //Store the default charge in case something goes wrong
        $this->Amount=self::config()->default_charge;
        
        
        if(!isset($destination)) {
            return $this->Amount;
        }
        
        
        $packageItems=array();
        foreach($this->Order()->Items() as $item) {
            if($item instanceof Product_OrderItem) {
                $packageItems[]=new ComplexType\RequestedPackageLineItem(array(
                        'Weight'=>new ComplexType\Weight(array(
                                'Units'=>new SimpleType\WeightUnits(SimpleType\WeightUnits::_KG),
                                'Value'=>$item->Product()->Weight
                            )),
                        'Dimensions' => new ComplexType\Dimensions(array(
                                'Length'=>$item->Product()->Depth,
                                'Width'=>$item->Product()->Width,
                                'Height'=>$item->Product()->Height,
                                'Units'=>new SimpleType\LinearUnits(SimpleType\LinearUnits::_CM)
                            )),
                        'GroupPackageCount'=>$item->Quantity
                    ));
            }
        }
        
        
        $rateRequest=$this->getRateRequestAPI();
        
        //RequestedShipment
        $rateRequest->setRequestedShipment(new ComplexType\RequestedShipment(array(
            'DropoffType' => new SimpleType\DropoffType(SimpleType\DropoffType::_REGULAR_PICKUP),
            'ShipTimestamp' => date('c'),
            'Shipper' => new ComplexType\Party(array(
                'Address' => new ComplexType\Address($this->getOriginAddress())
            )),
            'Recipient' => new ComplexType\Party(array(
                'Address' => new ComplexType\Address($shippingAddress)
            )),
            'PreferredCurrency'=>ShopConfig::config()->base_currency,
            'RateRequestType'=>new SimpleType\RateRequestType(SimpleType\RateRequestType::_LIST),
            'PackageCount'=>$this->Order()->Items()->count(),
            //'PackageDetail'=>new SimpleType\RequestedPackageDetailType(SimpleType\RequestedPackageDetailType::_INDIVIDUAL_PACKAGES),
            'RequestedPackageLineItems'=>$packageItems
        )));
        
        
        //Allow extensions to modify the 
        $this->extend('updateRateRequest', $rateRequest);
        
        
        //Initialize the request
        $validateShipmentRequest=new RateService\Request();
        if(!$this->config()->test_mode) {
            $validateShipmentRequest->getSoapClient()->__setLocation('https://ws.fedex.com:443/web-services/rate');
        }
        
        
        //Call the api and look through the response
        $response=$validateShipmentRequest->getGetRatesReply($rateRequest);
        if(property_exists($response, 'RateReplyDetails') && count($response->RateReplyDetails)>0) {
            foreach($response->RateReplyDetails as $rates) {
                if($rates->ServiceType==self::config()->service_type) {
                    foreach($rates->RatedShipmentDetails as $rate) {
                        if(property_exists($rate, 'TotalNetCharge')) {
                            $charge=$rate->TotalNetCharge;
                            if($charge->Currency!=ShopConfig::get_base_currency() && property_exists($rate, 'CurrencyExchangeRate')) {
                                $charge->Amount=((1-$rate->CurrencyExchangeRate->Rate)+1)*$charge->Amount;
                            }
                            
                            $this->Amount=$charge->Amount;
                        }
                    }
                }
            }
        }
        
        
        Session::set('FedExShipping_'.$this->Order()->ID.'.orderitems', $orderItems.'|'.$orderItemsCount.'|'.implode(',', $shippingAddress));
        
        return $this->Amount;
    }
    
    /**
     * Generates the base RateRequest object
     * @return {RateRequest}
     */
    protected function getRateRequestAPI() {
        $rateRequest=new ComplexType\RateRequest();
        
        //Set Authentication data
        $rateRequest->setWebAuthenticationDetail(new ComplexType\WebAuthenticationDetail(array(
                'UserCredential'=>new ComplexType\WebAuthenticationCredential(array(
                        'Key'=>($this->config()->test_mode ? $this->config()->test_api_key:$this->config()->api_key),
                        'Password'=>($this->config()->test_mode ? $this->config()->test_api_password:$this->config()->api_password)
                    ))
            )));
        
        
        //Client detail
        $rateRequest->setClientDetail(new ComplexType\ClientDetail(array(
                'AccountNumber'=>($this->config()->test_mode ? $this->config()->test_account_number:$this->config()->account_number),
                'MeterNumber'=>($this->config()->test_mode ? $this->config()->test_meter_number:$this->config()->meter_number),
                'IntegratorId'=>"123"
            )));
        
        
        //Transaction Detail
        $rateRequest->setTransactionDetail(new ComplexType\TransactionDetail(array(
                'CustomerTransactionId'=>$this->Order()->ID
            )));
        
        //Version
        $rateRequest->setVersion(new ComplexType\VersionId(array(
                'ServiceId' => 'crs',
                'Major' => 16,
                'Intermediate' => 0,
                'Minor' => 0
            )));
        
        
        //Include transit time in response
        $rateRequest->setReturnTransitAndCommit(true);
        
        
        return $rateRequest;
    }
    
    /**
     * Retrieves the origin address based on the configuration options
     * @return {array} Origin address to be sent to the api
     */
    protected function getOriginAddress() {
        $address=array(
                        'StreetLines' => array($this->config()->origin_address),
                        'City'=>$this->config()->origin_city,
                        'StateOrProvinceCode'=>$this->config()->origin_state_province_code,
                        'PostalCode'=>$this->config()->origin_postal_code,
                        'CountryCode'=>$this->config()->origin_country_code
                    );
        
        $addressLine2=$this->config()->origin_address_line2;
        if(!empty($addressLine2)) {
            $address['StreetLines'][]=$addressLine2;
        }
        
        return $address;
    }
}
?>