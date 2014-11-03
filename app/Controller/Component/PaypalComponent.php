<?php
App::import("Vendor", "Paypal", array("file" => "paypal_pro/includes/config.php"));
App::import("Vendor", "Paypal1", array("file" => "paypal_pro/includes/paypal.class.php"));

class PaypalComponent extends Component
{
    public $paypal;
    public $api_config;
    public $cc_details;
    public $payer_info;
    public $payment_details;

    public function __construct()
    {
        $this->api_config = array(
            'Sandbox' => PAYPAL_SANDBOX,
            'APIUsername' => PAYPAL_USERNAME,
            'APIPassword' => PAYPAL_PASSWORD,
            'APISignature' => PAYPAL_SIGNATURE
        );
        $this->paypal = new PayPal($this->api_config);
    }

    public function DoDirect($cardType = null, $cardNo = null, $expMonth = null, $expYear = null, $cvv2 = null, $uFname = null, $uLname = null, $amt = null, $currCode = null)
    {
        /*** Credit card details***/
        $expDate = $expMonth . $expYear;
        $this->cc_details = array(
            'creditcardtype' => $cardType, // Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
            'acct' => $cardNo, // Required.  Credit card number.  No spaces or punctuation.
            'expdate' => $expDate, // Required.  Credit card expiration date.  Format is MMYYYY
            'cvv2' => $cvv2, // Requirements determined by your PayPal account settings.  Security digits for credit card.
        );

        /*** Payer information ***/
        $this->payer_info = array(
            'firstname' => $uFname, // Required.  Payer's first name.
            'lastname' => $uLname // Required.  Payer's last name.
        );

        /*** Payment information ***/
        $this->payment_details = array(
            'amt' => $amt, // Required.  Total amount of order, including shipping, handling, and tax.
            'currencycode' => $currCode, // Required.  Three-letter currency code.  Default is USD.
        );

        $PayPalRequestData = array(
            'CCDetails' => $this->cc_details,
            'PayerInfo' => $this->payer_info,
            'PaymentDetails' => $this->payment_details
        );
        $PayPalResult = $this->paypal->DoDirectPayment($PayPalRequestData);
        return $PayPalResult;
    }

    public function MassPay($email_subject, $currency_code, $MPItems)
    {
        // Prepare request arrays
        $MPFields = array(
            'emailsubject' => $email_subject, // The subject line of the email that PayPal sends when the transaction is completed.  Same for all recipients.  255 char max.
            'currencycode' => $currency_code, // Three-letter currency code.
            'receivertype' => 'EmailAddress'

        );
        /*
         // Prepare request arrays
            $MPFields = array(
            'emailsubject' => 'Test MassPay', // The subject line of the email that PayPal sends when the transaction is completed. Same for all recipients. 255 char max.
            'currencycode' => 'USD', // Three-letter currency code.
            'receivertype' => 'EmailAddress' // Indicates how you identify the recipients of payments in this call to MassPay. Must be EmailAddress or UserID
            );

            // Typically, you'll loop through some sort of records to build your MPItems array.
            // Here I simply include 3 items individually.

            $Item1 = array(
            'l_email' => 'andrew_1342623385_per@angelleye.com', // Required. Email address of recipient. You must specify either L_EMAIL or L_RECEIVERID but you must not mix the two.
            'l_receiverid' => '', // Required. ReceiverID of recipient. Must specify this or email address, but not both.
            'l_amt' => '10.00', // Required. Payment amount.
            'l_uniqueid' => '', // Transaction-specific ID number for tracking in an accounting system.
            'l_note' => '' // Custom note for each recipient.
            );

            $Item2 = array(
            'l_email' => 'usb_1329725429_biz@angelleye.com', // Required. Email address of recipient. You must specify either L_EMAIL or L_RECEIVERID but you must not mix the two.
            'l_receiverid' => '', // Required. ReceiverID of recipient. Must specify this or email address, but not both.
            'l_amt' => '10.00', // Required. Payment amount.
            'l_uniqueid' => '', // Transaction-specific ID number for tracking in an accounting system.
            'l_note' => '' // Custom note for each recipient.
            );

            $Item3 = array(
            'l_email' => 'andrew_1277258815_per@angelleye.com', // Required. Email address of recipient. You must specify either L_EMAIL or L_RECEIVERID but you must not mix the two.
            'l_receiverid' => '', // Required. ReceiverID of recipient. Must specify this or email address, but not both.
            'l_amt' => '10.00', // Required. Payment amount.
            'l_uniqueid' => '', // Transaction-specific ID number for tracking in an accounting system.
            'l_note' => '' // Custom note for each recipient.
            );

            $MPItems = array($Item1, $Item2, $Item3); // etc

            */

        $PayPalRequestData = array('MPFields' => $MPFields, 'MPItems' => $MPItems);

        // Pass data into class for processing with PayPal and load the response array into $PayPalResult
        $PayPalResult = $this->paypal->MassPay($PayPalRequestData);

        return $PayPalResult;
    }

    public function RefundTransaction($txn, $amt, $currency = 'USD', $log_id = '')
    {


        // Prepare request arrays
        $RTFields = array(
            'transactionid' => $txn, // Required. PayPal transaction ID for the order you're refunding.
            //'payerid' => '', // Encrypted PayPal customer account ID number. Note: Either transaction ID or payer ID must be specified. 127 char max
            //'invoiceid' => '', // Your own invoice tracking number.
            'refundtype' => 'Full', // Required. Type of refund. Must be Full, Partial, or Other.
            'amt' => $amt, // Refund Amt. Required if refund type is Partial.
            'currencycode' => $currency, // Three-letter currency code. Required for Partial Refunds. Do not use for full refunds.
            'note' => 'event cancelation', // Custom memo about the refund. 255 char max.
            //'retryuntil' => '', // Maximum time until you must retry the refund. Note: this field does not apply to point-of-sale transactions.
            //'refundsource' => '', // Type of PayPal funding source (balance or eCheck) that can be used for auto refund. Values are: any, default, instant, eCheck
            //'merchantstoredetail' => '', // Information about the merchant store.
            //'refundadvice' => '', // Flag to indicate that the buyer was already given store credit for a given transaction. Values are: 1/0
            //'refunditemdetails' => '', // Details about the individual items to be returned.
            'msgsubid' => 'GroupDating ' . $log_id, // A message ID used for idempotence to uniquely identify a message.
            //	'storeid' => '', // ID of a merchant store. This field is required for point-of-sale transactions. 50 char max.
            //	'terminalid' => ''	// ID of the terminal. 50 char max.
        );

        $PayPalRequestData = array('RTFields' => $RTFields);

        // Pass data into class for processing with PayPal and load the response array into $PayPalResult
        $PayPalResult = $this->paypal->RefundTransaction($PayPalRequestData);


        return $PayPalResult;
    }


    /**
     *
     *
     */
    public function Refund()
    {

        // Prepare request arrays
        $RefundFields = array(
            'CurrencyCode' => 'USD', // Required. Must specify code used for original payment. You do not need to specify if you use a payKey to refund a completed transaction.
            'PayKey' => '', // Required. The key used to create the payment that you want to refund.
            'TransactionID' => '3KX096199G693891J', // Required. The PayPal transaction ID associated with the payment that you want to refund.
            'TrackingID' => '' // Required. The tracking ID associated with the payment that you want to refund.
        );

        $Receivers = array();
        $Receiver = array(
            'Email' => 'ram@mailinator.com', // A receiver's email address.
            'Amount' => '1.00', // Amount to be debited to the receiver's account.
            'Primary' => '', // Set to true to indicate a chained payment. Only one receiver can be a primary receiver. Omit this field, or set to false for simple and parallel payments.
            'InvoiceID' => '', // The invoice number for the payment. This field is only used in Pay API operation.
            'PaymentType' => 'GOODS' // The transaction subtype for the payment. Allowable values are: GOODS, SERVICE
        );

        array_push($Receivers, $Receiver);

        $PayPalRequestData = array(
            'RefundFields' => $RefundFields,
            'Receivers' => $Receivers
        );


        // Pass data into class for processing with PayPal and load the response array into $PayPalResult
        $PayPalResult = 'This is not exist in this api'; //$this->paypal->Refund($PayPalRequestData);
        return $PayPalResult;
    }
}
