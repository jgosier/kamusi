<?php
 // $Id:
 
/**
 * @file
 * User page callbacks for the moneybookers module.
 * 
 */
 
 
/**
 * Site-wide reportbug page.
 */
function reportbug_site_page() {
	return drupal_get_form('moneybookers_pay_page');
}
 
 /**
  * builds the form
  */
 function moneybookers_pay_page() {
 	$email = 'pesa@kamusiproject.org';
 	$request_url = 'https://www.moneybookers.com/app/payment.pl';
 	
 	$form['#action'] = url( $request_url );
 	$form['amount_details'] = array(
 		'#type' => 'fieldset',
 		'#title' => t('Amount Details'),
 		'#collapsible' => FALSE,
 		'#collapsed' => TRUE,
 	);
 	
	$form['amount_details']['pay_to_email'] = array(
		'#type' => 'hidden',
		'#value' => $email,
	);
 	
 	$form['amount_details']['status_url'] = array(
 		'#type' => 'hidden',
 		'#value' => $email,
 	);
 	
 	$form['amount_details']['language'] = array(
 		'#type' => 'hidden',
 		'#value' => 'EN',
 	);
 	
 	$form['amount_details']['amount'] = array(
 		'#type' => 'textfield',
 		'#title' => t('Amount'),
 		'#description' => t('Please enter the amount you want to give. Eg. 10000'),
 		'#required' => TRUE,
 	);
 	
 	$currency = array('EUR' =>'Euro', 'USD' => 'US Dollar','GBP' => 'British Pound');
 	$form['amount_details']['currency'] = array(
 		'#type' => 'select',
 		'#title' => t('Currency'),
 		'#default_value' => variable_get('currency','USD'),
 		'#options' => $currency,
 		'#description' => t('Select the curreny for the payment.'),
 	); 
 	
 	$form['amount_details']['detail1_description'] = array(
 		'#type' => 'hidden',
 		'#value' => t( 'Donation' )
 	);
 	
 	$form['amount_details']['detail1_text'] = array(
 		'#type' => 'hidden',
 		'#value' => t('Support the Kamusi Project'),
 	);
 	
 	$form['submit'] = array(
		'#type' => 'submit', 
		'#value' => t('Pay!')
	);
 	
 	return $form;
 }
 
 /**
 * Validate the site-wide reportbug page form submission.
 */
function moneybookers_pay_page_validate($form, &$form_state) {
  if (!$form_state['values']['amount']) {
    form_set_error('amount', t('You must enter an amount for the donation.'));
  }
  
}

?>
