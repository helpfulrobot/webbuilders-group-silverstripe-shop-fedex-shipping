<?php
class FedExStateProvinceExtension extends Extension {
    private static $db=array(
                            'OtherState'=>'Varchar(200)'
                        );
    
    /**
     * Updates the form fields for address'es to use a dropdown for the state and an additional field for the other state
     * @param {FieldList} $fields Fields to modify
     */
    public function updateFormFields(FieldList $fields) {
        $stateField=$fields->dataFieldByName('State');
        if($stateField) {
            $newStateField=new GroupedDropdownField('State', $stateField->Title, array(
                                                                                        _t('FedExStateProvinceExtension.UNITED_STATES', '_United States')=>array(
                                                                                            'AL'=>_t('FedExStateProvinceExtension.US_AL', '_Alabama'),
                                                                                            'LA'=>_t('FedExStateProvinceExtension.US_LA', '_Louisiana'),
                                                                                            'OK'=>_t('FedExStateProvinceExtension.US_OK', '_Oklahoma'),
                                                                                            'AK'=>_t('FedExStateProvinceExtension.US_AK', '_Alaska'),
                                                                                            'ME'=>_t('FedExStateProvinceExtension.US_ME', '_Maine'),
                                                                                            'OR'=>_t('FedExStateProvinceExtension.US_OR', '_Oregon'),
                                                                                            'AZ'=>_t('FedExStateProvinceExtension.US_AZ', '_Arizona'),
                                                                                            'MD'=>_t('FedExStateProvinceExtension.US_MD', '_Maryland'),
                                                                                            'PA'=>_t('FedExStateProvinceExtension.US_PA', '_Pennsylvania'),
                                                                                            'AR'=>_t('FedExStateProvinceExtension.US_AR', '_Arkansas'),
                                                                                            'MA'=>_t('FedExStateProvinceExtension.US_MA', '_Massachusetts'),
                                                                                            'RI'=>_t('FedExStateProvinceExtension.US_RI', '_Rhode Island'),
                                                                                            'CA'=>_t('FedExStateProvinceExtension.US_CA', '_California'),
                                                                                            'MI'=>_t('FedExStateProvinceExtension.US_MI', '_Michigan'),
                                                                                            'SC'=>_t('FedExStateProvinceExtension.US_SC', '_South Carolina'),
                                                                                            'CO'=>_t('FedExStateProvinceExtension.US_CO', '_Colorado'),
                                                                                            'MN'=>_t('FedExStateProvinceExtension.US_MN', '_Minnesota'),
                                                                                            'SD'=>_t('FedExStateProvinceExtension.US_SD', '_South Dakota'),
                                                                                            'CT'=>_t('FedExStateProvinceExtension.US_CT', '_Connecticut'),
                                                                                            'MS'=>_t('FedExStateProvinceExtension.US_MS', '_Mississippi'),
                                                                                            'TN'=>_t('FedExStateProvinceExtension.US_TN', '_Tennessee'),
                                                                                            'DE'=>_t('FedExStateProvinceExtension.US_DE', '_Delaware'),
                                                                                            'MO'=>_t('FedExStateProvinceExtension.US_MO', '_Missouri'),
                                                                                            'TX'=>_t('FedExStateProvinceExtension.US_TX', '_Texas'),
                                                                                            'DC'=>_t('FedExStateProvinceExtension.US_DC', '_District of Columbia'),
                                                                                            'MT'=>_t('FedExStateProvinceExtension.US_MT', '_Montana'),
                                                                                            'UT'=>_t('FedExStateProvinceExtension.US_UT', '_Utah'),
                                                                                            'FL'=>_t('FedExStateProvinceExtension.US_FL', '_Florida'),
                                                                                            'NE'=>_t('FedExStateProvinceExtension.US_NE', '_Nebraska'),
                                                                                            'VT'=>_t('FedExStateProvinceExtension.US_VT', '_Vermont'),
                                                                                            'GA'=>_t('FedExStateProvinceExtension.US_GA', '_Georgia'),
                                                                                            'NV'=>_t('FedExStateProvinceExtension.US_NV', '_Nevada'),
                                                                                            'VA'=>_t('FedExStateProvinceExtension.US_VA', '_Virginia'),
                                                                                            'HI'=>_t('FedExStateProvinceExtension.US_HI', '_Hawaii'),
                                                                                            'NH'=>_t('FedExStateProvinceExtension.US_NH', '_New Hampshire'),
                                                                                            'WA'=>_t('FedExStateProvinceExtension.US_WA', '_Washington State'),
                                                                                            'ID'=>_t('FedExStateProvinceExtension.US_ID', '_Idaho'),
                                                                                            'NJ'=>_t('FedExStateProvinceExtension.US_NJ', '_New Jersey'),
                                                                                            'WV'=>_t('FedExStateProvinceExtension.US_WV', '_West Virginia'),
                                                                                            'IL'=>_t('FedExStateProvinceExtension.US_IL', '_Illinois'),
                                                                                            'NM'=>_t('FedExStateProvinceExtension.US_NM', '_New Mexico'),
                                                                                            'WI'=>_t('FedExStateProvinceExtension.US_WI', '_Wisconsin'),
                                                                                            'IN'=>_t('FedExStateProvinceExtension.US_IN', '_Indiana'),
                                                                                            'NY'=>_t('FedExStateProvinceExtension.US_NY', '_New York'),
                                                                                            'WY'=>_t('FedExStateProvinceExtension.US_WY', '_Wyoming'),
                                                                                            'IA'=>_t('FedExStateProvinceExtension.US_IA', '_Iowa'),
                                                                                            'NC'=>_t('FedExStateProvinceExtension.US_NC', '_North Carolina'),
                                                                                            'PR'=>_t('FedExStateProvinceExtension.US_PR', '_Puerto Rico'),
                                                                                            'KS'=>_t('FedExStateProvinceExtension.US_KS', '_Kansas'),
                                                                                            'ND'=>_t('FedExStateProvinceExtension.US_ND', '_North Dakota'),
                                                                                            'KY'=>_t('FedExStateProvinceExtension.US_KY', '_Kentucky'),
                                                                                            'OH'=>_t('FedExStateProvinceExtension.US_OH', '_Ohio')
                                                                                        ),
                                                                                        _t('FedExStateProvinceExtension.CANADA', '_Canada')=>array(
                                                                                            'AB'=>_t('FedExStateProvinceExtension.CA_AB', '_Alberta'),
                                                                                            'BC'=>_t('FedExStateProvinceExtension.CA_BC', '_British Columbia'),
                                                                                            'MB'=>_t('FedExStateProvinceExtension.CA_MB', '_Manitoba'),
                                                                                            'NB'=>_t('FedExStateProvinceExtension.CA_NB', '_New Brunswick'),
                                                                                            'NL'=>_t('FedExStateProvinceExtension.CA_NL', '_Newfoundland'),
                                                                                            'NT'=>_t('FedExStateProvinceExtension.CA_NT', '_Northwest Territories and Labrador'),
                                                                                            'NS'=>_t('FedExStateProvinceExtension.CA_NS', '_Nova Scotia'),
                                                                                            'NU'=>_t('FedExStateProvinceExtension.CA_NU', '_Nunavut'),
                                                                                            'ON'=>_t('FedExStateProvinceExtension.CA_ON', '_Ontario'),
                                                                                            'PE'=>_t('FedExStateProvinceExtension.CA_PE', '_Prince Edward Island'),
                                                                                            'QC'=>_t('FedExStateProvinceExtension.CA_QC', '_Quebec'),
                                                                                            'SK'=>_t('FedExStateProvinceExtension.CA_SK', '_Saskatchewan'),
                                                                                            'YT'=>_t('FedExStateProvinceExtension.CA_YT', '_Yukon')
                                                                                        ),
                                                                                        ''=>_t('FedExStateProvinceExtension.OTHER', '_Other')
                                                                                    ));
            $newStateField->setDescription=$stateField->getDescription();
            $newStateField->setForm($stateField->getForm());
            
            $fields->replaceField('State', $newStateField);
            
            $fields->insertAfter($otherState=new TextField('OtherState', _t('FedExStateProvinceExtension.OTHER_STATE', '_Other State'), null, 200), 'State');
            $otherState->setDescription(_t('FedExStateProvinceExtension.OTHER_DESC', '_If you chose other as your state please place it here'));
            $otherState->setForm($stateField->getForm());
        }
    }
}
?>