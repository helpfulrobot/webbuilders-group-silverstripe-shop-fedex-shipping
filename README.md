Shop FedEx Shipping Modifier
=================
FedEx shipping modifier for BurnBright's Shop Module that provides a quote for the order being shipped through FedEx. Note that you must have each shippable project with dimentions and weights for the quote to be accurate.

## Maintainer Contact
* Ed Chipman ([UndefinedOffset](https://github.com/UndefinedOffset))

## Requirements
* SilverStripe CMS 3.1.x
* [SilverStripe Shop](https://github.com/burnbright/silverstripe-shop/)


## Installation
* Download the module from here https://github.com/webbuilders-group/silverstripe-shop-fedex-shipping/archive/master.zip
* Extract the downloaded archive into your site root so that the destination folder is called shop-fedex-shipping, opening the extracted folder should contain _config.php in the root along with other files/folders
* Run dev/build?flush=all to regenerate the manifest

If you prefer you may also install using composer:
```
composer require webbuilders-group/silverstripe-shop-fedex-shipping
```


## Usage
To use this modifier you must add the FedExShippingModifer class to your shop configuration.
```yml
Order:
    modifiers:
        - "FedExShippingModifier"
```


You then need to apply for credentials to access the FedEx api, you can do this via their [developer portal](http://www.fedex.com/us/developer/web-services/index.html). You need to set your credentials you will receive from the portal in your site's config.yml.
```yml
FedExShippingModifier:
    test_mode: true #Places the endpoint in test mode, for live set this to false
    test_api_key: "YOUR_TEST_API_KEY"
    test_api_password: "YOUR_TEST_API_PASSWORD"
    test_account_number: "YOUR_TEST_ACCOUNT_NUMBER"
    test_meter_number: "YOUR_TEST_METER_NUMBER"
    api_key: "YOUR_LIVE_API_KEY"
    api_password: "YOUR_LIVE_API_PASSWORD"
    account_number: "YOUR_LIVE_ACCOUNT_NUMBER"
    meter_number: "YOUR_LIVE_METER_NUMBER"
    origin_address: "ADDR" #Shipper's address
    origin_address_line2: "ADDR_LINE_2" #Second line of the shipper's address (optional)
    origin_city: "CITY" #Shipper's city
    origin_state_province_code: "STATE" #Your 2 character state/province code for the shipper's address
    origin_postal_code: "ZIP_POSTAL" #Shipper's postal code/zip code
    origin_country_code: "COUNTRY" #Your 2 character country code for the shipper's address
    service_type: "FEDEX_GROUND" #The service type you want to retrieve quotes for, see bellow for more information
    default_charge: 0 #Default amount to charge for shipping should the api return an invalid response
```

For the ``service_type`` configuration option you need to pick from one of the service types bellow.

* FEDEX_GROUND: FedEx Ground
* EUROPE_FIRST_INTERNATIONAL_PRIORITY: Europe First International Priority
* FEDEX_1_DAY_FREIGHT: FedEx 1 Day Freight
* FEDEX_2_DAY: FedEx 2 Day
* FEDEX_2_DAY_AM: FedEx 2 Day AM
* FEDEX_2_DAY_FREIGHT: FedEx 2 Day Freight
* FEDEX_3_DAY_FREIGHT: FedEx 3 DAY Freight
* FEDEX_DISTANCE_DEFERRED: FedEx Distance Deferred
* FEDEX_EXPRESS_SAVER: FedEx Express Saver
* FEDEX_FIRST_FREIGHT: FedEx First Freight
* FEDEX_FREIGHT_ECONOMY: FedEx Freight Economy
* FEDEX_FREIGHT_PRIORITY: FedEx Freight Priority
* FEDEX_NEXT_DAY_AFTERNOON: FedEx Next Day Afternoon
* FEDEX_NEXT_DAY_EARLY_MORNING: FedEx Next Day Early Morning
* FEDEX_NEXT_DAY_END_OF_DAY: FedEx Next Day End of Day
* FEDEX_NEXT_DAY_FREIGHT: FedEx Next Day Freight
* FEDEX_NEXT_DAY_MID_MORNING: FedEx Next Day Mid Morning
* FIRST_OVERNIGHT: First Overnight
* GROUND_HOME_DELIVERY: Ground Home Delivery
* INTERNATIONAL_ECONOMY: International Economy
* INTERNATIONAL_ECONOMY_FREIGHT: International Economy Freight
* INTERNATIONAL_FIRST: International First
* INTERNATIONAL_PRIORITY: International Priority
* INTERNATIONAL_PRIORITY_FREIGHT: International Priority Freight
* PRIORITY_OVERNIGHT: Priority Overnight
* SAME_DAY: Same Day
* SAME_DAY_CITY: Same Day City
* SMART_POST: Smart Post
* STANDARD_OVERNIGHT: Standard Overnight
