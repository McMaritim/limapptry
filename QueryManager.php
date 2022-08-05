<?php

include_once('Models.php');
/**
 * Returns an array with database results generated from $sql and $params
 */
function _select($sql, $params) {

    $username = 'kap_survey';
    $password = 'mhealth@123!@#';
    $database = 'lima';
    $host = 'localhost:3306';

    $res = array();
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetchAll();
    } catch (PDOException $error) {
       // var_dump($sql);
       // var_dump($params);
       // var_dump($error);        
       error_log("[ERROR : " . date("Y-m-d H:i:s") . "] _select error: " . $error . "\nSQL=" . $sql . "\nParams=" . print_r($params, true), 3, LOG_FILE);
    }
    return $res;
}

/**
 * Performs database insert, update and delete
 */
function _execute($sql, $params) {

    $username = 'kap_survey';
    $password = 'mhealth@123!@#';
    $database = 'lima';
    $host = 'localhost:3306';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return TRUE;
    } catch (PDOException $error) {
       // var_dump($error); 
       // var_dump($sql);
       // var_dump($params);
        error_log("[ERROR : " . date("Y-m-d H:i:s") . "] _execute error: " . $error . "\nSQL=" . $sql . "\nParams=" . print_r($params, true), 3, LOG_FILE);
        return FALSE;
    }
}

function createNewUssdSession($ussdSession) {
    $sql = "INSERT INTO ussd_sessions (SessionId,Msisdn,UssdCode,UssdString,UssdProcessString,currentFeedbackString,currentFeedbackType,startTime,userParams)"
            . " VALUES(:sessionId,:msisdn,:ussdCode,:ussdString,:ussdProcessString,:currentFeedbackString,:currentFeedbackType,:startTime,:userParams)";
    $params = array(
        ':sessionId' => $ussdSession->sessionId,
        ':msisdn' => $ussdSession->msisdn,
        ':ussdCode' => $ussdSession->ussdCode,
        ':ussdString' => $ussdSession->ussdString,
        ':ussdProcessString' => $ussdSession->ussdProcessString,
        ':currentFeedbackString' => $ussdSession->currentFeedbackString,
        ':currentFeedbackType' => $ussdSession->currentFeedbackType,
        ':startTime' => date('Y-m-d H:i:s'),
        ':userParams' => $ussdSession->userParams,
    );
    return _execute($sql, $params);
}

function getUssdSessionList($sessionId){
    $ussdSessionList = array();
    $sql = "SELECT sessionId,msisdn,UssdCode,UssdString,UssdStringPrefix,UssdProcessString,previousFeedbackType,currentFeedbackString,currentFeedbackType,startTime,userParams"
            . " FROM ussd_sessions"
            . " WHERE sessionId=:sessionId LIMIT 1";
    $params = array(
        ':sessionId' => $sessionId,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdSession = new UssdSession();
        $ussdSession->sessionId = $record['sessionId'];
        $ussdSession->msisdn = $record['msisdn'];
        $ussdSession->ussdCode = $record['UssdCode'];
        $ussdSession->ussdString = $record['UssdString'];
        $ussdSession->ussdStringPrefix = $record['UssdStringPrefix'];
        $ussdSession->ussdProcessString = $record['UssdProcessString'];
        $ussdSession->previousFeedbackType = $record['previousFeedbackType'];
        $ussdSession->currentFeedbackString = $record['currentFeedbackString'];
        $ussdSession->currentFeedbackType = $record['currentFeedbackType'];
        $ussdSession->startTime = $record['startTime'];
        $ussdSession->userParams = $record['userParams'];
        $ussdSessionList[] = $ussdSession;
    }
    return $ussdSessionList;
}

function updateUssdSession($ussdSession) {
    $sql = "UPDATE ussd_sessions SET UssdString=:ussdString,UssdStringPrefix=:ussdStringPrefix, UssdProcessString=:ussdProcessString,"
            . "previousFeedbackType=:previousFeedbackType,currentFeedbackString=:currentFeedbackString,currentFeedbackType=:currentFeedbackType,userParams=:userParams"
            . " WHERE sessionId=:sessionId";
    $params = array(
        ':ussdString' => $ussdSession->ussdString,
        ':ussdStringPrefix' => $ussdSession->ussdStringPrefix,
        ':ussdProcessString' => $ussdSession->ussdProcessString,
        ':previousFeedbackType' => $ussdSession->previousFeedbackType,
        ':currentFeedbackString' => $ussdSession->currentFeedbackString,
        ':currentFeedbackType' => $ussdSession->currentFeedbackType,
        ':userParams' => $ussdSession->userParams,
        ':sessionId' => $ussdSession->sessionId,
    );
    return _execute($sql, $params);
}

function createUssdUser($ussdUser) {
    $sql = "INSERT INTO persons (phone_no,first_name,last_name,id_number,county,sub_county)"
            . " VALUES(:phone_no,:first_name,:last_name,:id_number,:county,:sub_county)";
    $params = array(
        ':phone_no' => $ussdUser->phone_no,
        ':first_name' => $ussdUser->first_name,
        ':last_name' => $ussdUser->last_name,
        ':id_number' => $ussdUser->id_number,
        ':county' => $ussdUser->county,
        ':sub_county' => $ussdUser->sub_county,
    
    );
    return _execute($sql, $params);
}


function getUssdUserList($phone_no) {
    $ussdUserList = array();
    $sql = "SELECT person_id,phone_no,first_name,last_name,id_number,county,sub_county"
            . " FROM persons"
            . " WHERE phone_no=:phone_no LIMIT 1";
    $params = array(
        ':phone_no' => $phone_no,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdUser = new Persons();
        $ussdUser->person_id = $record['person_id'];
        $ussdUser->phone_no = $record['phone_no'];
        $ussdUser->first_name = $record['first_name'];
        $ussdUser->last_name = $record['last_name'];
        $ussdUser->id_number = $record['id_number'];
        $ussdUser->county = $record['county'];
        $ussdUser->sub_county = $record['sub_county'];
       // $ussdUser->level = $record['level'];
        $ussdUserList[] = $ussdUser;
    }
    return $ussdUserList;
}
function getCountyList() {
    $countyList = array();
    $sql = "SELECT id,name"
            . " FROM county";
    $params = array(
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $countyCategory = new County();
        $countyCategory->id = $record['id'];
        $countyCategory->name = $record['name'];
        $countyList[] = $countyCategory;
    }
    return $countyList;
}
function getSubCountyList(){
    $subCountyList = array();
    $sql = "SELECT id,name,county_id"
            . " FROM sub_county";       
    $params = array(
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $subCountyCategory = new SubCounty();
        $subCountyCategory->id = $record['id'];
        $subCountyCategory->name = $record['name'];
        $subCountyCategory->county_id = $record['county_id'];
        $subCountyList[] = $subCountyCategory;
    }
    return $subCountyList;
}
function getLevelList(){
    $levelList = array();
    $sql = "SELECT level_id,level_name"
            . " FROM level";
    $params = array(
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $levelCategory = new Level();
        $levelCategory->level_id = $record['level_id'];
        $levelCategory->level_name = $record['level_name'];
        $levelList[] = $levelCategory;
    }
    return $levelList;
}


function getProductsList(){
    $List = array();
    $sql = "SELECT product_id,product_name,price"
            . " FROM products";
    $params = array(
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $rateCategory = new Product();
        $rateCategory->product_id = $record['product_id'];
        $rateCategory->product_name = $record['product_name'];
        $rateCategory->price = $record['price'];
        $roleRateCategoryList[] = $rateCategory;
    }
    return $roleRateCategoryList;  
}

function getRatesListWithCategoryCode($product_serial_code) {
    $ratesList = array();
    $sql = "SELECT product_id, product_code,product_serial_code, product_name, rate"
            . " FROM products"
            . " WHERE product_serial_code=:product_serial_code"
            . " ORDER BY product_id ASC";
           // . " LIMIT 50";
    $params = array(
        ':product_serial_code' => $product_serial_code,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $rate = new Rate();
        $rate->product_id = $record['product_id'];
        $rate->product_code = $record['product_code'];
        $rate->product_serial_code = $record['product_serial_code'];
        $rate->product_name = $record['product_name'];
        $rate->rate = $record['rate'];
        $ratesList[] = $rate;
    }
    return $ratesList;
}


function getRatesListWithId($rateId) {
    $ratesList = array();
    $sql = "SELECT product_id, product_code, product_serial_code, product_name, rate"
            . " FROM products"
            . " WHERE product_id=:product_id"
            . " LIMIT 1";
    $params = array(
        ':product_id' => $rateId,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $rate = new Rate();
        $rate->product_id = $record['product_id'];
        $rate->product_serial_code = $record['product_serial_code'];
        $rate->product_code = $record['product_code'];
        $rate->product_name = $record['product_name'];
        $rate->rate = $record['rate'];
        $ratesList[] = $rate;
    }
    return $ratesList;
}


function saveMpesaRequest($mpesaRequest) {
    $msisdn = ltrim($mpesaRequest->msisdn, '+');
    $sql = "INSERT INTO mpesa_requests (ussdSessionId,msisdn,amount,rateId,paymentStatus,accountReference,transactionDescription)"
            . " VALUES(:ussdSessionId,:msisdn,:amount,:rateId,:paymentStatus,:accountReference,:transactionDescription)";
    $params = array(
        ':ussdSessionId' => $mpesaRequest->ussdSessionId,
        ':msisdn' => $msisdn,
        ':amount' => $mpesaRequest->amount,
        ':rateId' => $mpesaRequest->rateId,
        ':paymentStatus' => $mpesaRequest->paymentStatus,        
        ':accountReference' => $mpesaRequest->accountReference,
        ':transactionDescription' => $mpesaRequest->transactionDescription,
    );
    return _execute($sql, $params);
}


function getPaidMpesaRequestsList($msisdn) {
    $msisdn = ltrim($msisdn, '+');
    $mpesaRequestsList = array();
    $sql = "SELECT id,ussdSessionId,msisdn,amount,paymentStatus,mpesaReceiptNumber,rateId,transactionDescription,dateCreated,transactionDate,datePushedToLimaPro"
            . " FROM mpesa_requests"
            . " WHERE msisdn=:msisdn"
            . " AND paymentStatus=:paymentStatus"
            . " ORDER BY id DESC"
            . " LIMIT 5";
    $params = array(
        ':msisdn' => $msisdn,
        ':paymentStatus' => MpesaRequest::PAYMENTSTATUS_PAID,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $mpesaRequest = new MpesaRequest();
        $mpesaRequest->id = $record['id'];
        $mpesaRequest->ussdSessionId = $record['ussdSessionId'];
        $mpesaRequest->msisdn = $record['msisdn'];
        $mpesaRequest->amount = $record['amount']; 
        $mpesaRequest->paymentStatus = $record['paymentStatus'];        
        $mpesaRequest->mpesaReceiptNumber = $record['mpesaReceiptNumber'];
        $mpesaRequest->rateId = $record['rateId'];
        $mpesaRequest->transactionDescription = $record['transactionDescription']; 
        $mpesaRequest->dateCreated = $record['dateCreated'];        
        $mpesaRequest->transactionDate = $record['transactionDate'];
        $mpesaRequest->datePushedToLimaPro = $record['datePushedToLimaPro'];
        $mpesaRequestsList[] = $mpesaRequest;
    }
    return $mpesaRequestsList;
}
