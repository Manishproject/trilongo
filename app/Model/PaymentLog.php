<?php
class PaymentLog extends Model{
    public $validate = array(
        "card_no" => array(
            "rule" => "notEmpty",
            "message" => "This field can not be left blank!"
        ),
        "card_cvv" => array(
            "rule" => "notEmpty",
            "message" => "This field can not be left blank!"
        ),
        "user_fname" => array(
            "rule" => "notEmpty",
            "message" => "This field can not be left blank!"
        ),
        "user_lname" => array(
            "rule" => "notEmpty",
            "message" => "This field can not be left blank!"
        ),
        "card_type" => array(
            "rule" => "notEmpty",
            "message" => "Invalid card number!"
        ),
        
      "amount" => array(
            "rule" => "notEmpty",
            "message" => "Please enter valid amount!"
        )
    );

}
?>