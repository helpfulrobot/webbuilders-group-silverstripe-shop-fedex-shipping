<?php
class FedExOrderFormExtension extends Extension
{
    /**
     * Updates the form to include an update shipping button
     * @param {Form} $form Form to update
     */
    public function updateOrderForm(Form $form)
    {
        $form->Actions()->insertBefore(FormAction::create('doUpdateShipping', _t('FedExOrderFormExtension.UPDATE_SHIPPING', '_Update Shipping'))->setForm($form), 'action_checkoutSubmit');
    }
    
    /**
     * Handles requests to update the shipping
     * @param {array} $data Submitted data
     * @param {Form} $form Submitting form
     * @return {SS_HTTPResponse} Response Object
     */
    public function doUpdateShipping($data, Form $form)
    {
        //form validation has passed by this point, so we can save data
        $form->getConfig()->setData($form->getData());
        
        return $this->owner->redirectBack();
    }
}
