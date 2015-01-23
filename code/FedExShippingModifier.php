<?php
use FedEx\RateService;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;

class FedExShippingModifier extends ShippingModifier {
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
    
    
    public function value($subtotal=0) {
        if($this->Order()->Items()->count()==0) {
            return 0;
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
                'Address' => new ComplexType\Address(array(
                    'StreetLines' => array('13450 Farmcrest Ct'),
                    'City' => 'Herndon',
                    'StateOrProvinceCode' => 'VA',
                    'PostalCode' => 20171,
                    'CountryCode' => 'US'
                ))
            )),
            'RateRequestTypes'=>array(
                new SimpleType\RateRequestType(SimpleType\RateRequestType::_ACCOUNT),
                new SimpleType\RateRequestType(SimpleType\RateRequestType::_LIST)
            ),
            'PackageCount'=>$this->Order()->Items()->count(),
            //'PackageDetail'=>new SimpleType\RequestedPackageDetailType(SimpleType\RequestedPackageDetailType::_INDIVIDUAL_PACKAGES),
            'RequestedPackageLineItems'=>$packageItems
        )));
        
        
        print '<pre>';
        var_dump($rateRequest->toArray());
        
        $validateShipmentRequest=new RateService\Request();
        if(!$this->config()->test_mode) {
            $validateShipmentRequest->getSoapClient()->__setLocation('https://ws.fedex.com:443/web-services/rate');
        }
        
        var_dump($validateShipmentRequest->getGetRatesReply($rateRequest));exit;
    }
    
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
                'MeterNumber'=>($this->config()->test_mode ? $this->config()->test_meter_number:$this->config()->meter_number)
            )));
        
        
        //Transaction Detail
        $rateRequest->setTransactionDetail(new ComplexType\TransactionDetail(array(
                'CustomerTransactionId'=>' *** Rate Available Services Request v8 using PHP ***'
            )));
        
        //Version
        $rateRequest->setVersion(new ComplexType\VersionId(array(
                'ServiceId' => 'crs',
                'Major' => 10,
                'Intermediate' => 0,
                'Minor' => 0
            )));
        
        
        //Include transit time in response
        $rateRequest->setReturnTransitAndCommit(true);
        
        
        return $rateRequest;
    }
    
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