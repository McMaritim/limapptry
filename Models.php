<?php


class UssdSession {

    var $sessionId;
    var $msisdn;
    var $ussdCode;
    var $ussdString;
    var $ussdStringPrefix;
    var $ussdProcessString;
    var $previousFeedbackType;
    var $currentFeedbackString;
    var $currentFeedbackType;
    var $startTime;
    var $userParams;
    var $test;
    var $rate_cartegory;
    const LANGUAGE_ID = "LANGUAGE_ID";
    const MENU_ID = "MENU_ID";
    const MENU_ID1 = "MENU_ID1";
    const USER_ID = "USER_ID";
    const FIRSTNAME = "FIRSTNAME";
    const LASTNAME = "LASTNAME";
    const IDNUMBER = "IDNUMBER";
    const LOCATION = "LOCATION";
    const COUNTY = "COUNTY";
    const SUB_COUNTY = "SUB_COUNTY";
    const NO_LIVESTOCK = "NO_LIVESTOCK";
    const NOT_FOUND = "NOT_FOUND";
    const REGNUMBER = "REGNUMBER";
    const REQUEST_VET_ID = "REQUEST_VET_ID";
    const VIEWMYACCOUNTS_LIST_IDS = "VIEWMYACCOUNTS_LIST_IDS";
    const DISPLAY_LEVEL_ID = "DISPLAY_LEVEL_ID";
    const DISPLAY_COUNTY_ID = "DISPLAY_COUNTY_ID";
    const DISPLAY_SUB_COUNTY_ID = "DISPLAY_SUB_COUNTY_ID";
    const PRODUCT_ID = "PRODUCT_ID";
    const QUANTITY = "QUANTITY";
    const SELECTED_RATE_ID = "SELECTED_RATE_ID";
    const RATE_ID = "RATE_ID";
    const MYACCOUNT_LIST_IDS = "MYACCOUNT_LIST_IDS";
    const DISPLAYED_RATE_IDS = "DISPLAYED_RATE_IDS";
    const REQUEST_VET_REQ_ID = "REQUEST_VET_REQ_ID";
    const REQUEST_VET_REQ_KIS = "REQUEST_VET_REQ_KIS";
    const MINI_STMT_ID = "MINI_STMT_ID";
    public static function getUserParam($paramName, $userParams) {
        $params = explode("*", $userParams);
        //get latest input
        for ($i = count($params) - 1; $i > -1; $i--) {
            $keyValue = explode("=", $params[$i]);
            if ($paramName == $keyValue[0]) {
                return $keyValue[1];
            }
        }
        return self::NOT_FOUND;
    }

}
class Persons {
    var $person_id;
    var $first_name;
    var $last_name;
    var $id_number;
    var $phone_no;
    var $county;
    var $sub_county;
   
}

class County {
    var $id;
    var $name;
}
class Subcounty {
    var $id;
    var $name;
    var $county_id;
}
class Level {
    var $level_id;
    var $level_name;
}

class Product {
    
    var $product_id;
    var $product_name;
    var $price;
    var $product_serial_code;
}

class MpesaRequest {
    var $id;
    var $ussdSessionId;
    var $msisdn;
    var $amount;
    var $rateId;
    var $paymentStatus;
    var $accountReference;
    var $transactionDescription;
    var $mpesaReceiptNumber;
    var $dateCreated;
    var $transactionDate;
    var $sentToLimaPro;
    var $datePushToLimaProAttempted;
    const PAYMENTSTATUS_NOTPAID = 201;
    const PAYMENTSTATUS_PAID = 202;
    const PAYBILL = "4043889";

}

class Rate {
     const CATEGORY_PRODUCT = 101;
    var $product_id;
    var $product_code;
    var $product_serial_code;
    var $product_name;
    var $rate;
    var $dateLastModified;

}

class VetRequest{
    var $vet_request_id;
    var $source;
    var $vet;
    var $vet_comments;
}
