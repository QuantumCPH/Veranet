<?php


/**
 * Description of payment gateway
 *
 * 
 */
class Payment {
    //put your code here
    private static $PaypalEmail   = 'paypal@example.com'; //'namikkelsen@rocketmail.com';  //'paypal@example.com';
    private static $environment   = "sandbox";      //live             //sandbox
    public static function SendPayment($querystring){
         $querystring = "?business=".urlencode(self::$PaypalEmail)."&".$querystring;
            if(self::$environment=='live'){
            $paypalUrl = 'https://www.paypal.com/cgi-bin/webscr';
        }else{
            $paypalUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
        
        //die($paypalUrl.$querystring);
        header("Location:".$paypalUrl.$querystring);
        exit();
    }
}
?>
