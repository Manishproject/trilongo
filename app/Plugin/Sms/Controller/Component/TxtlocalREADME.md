CakePHP 2.0 Component for sending a SMS using txtlocal.com's API
===========================

Sends an SMS message to a given phone number.
You will need to register for an account with txtlocal.com and purchase credits.
Cost is about 4p per text


To work in CakePHP 1.3

Change 

class SmsComponent extends Object

to 

class SmsComponent extends Component

You will also need to change the name of the component from SmsComponent.php to sms.php
