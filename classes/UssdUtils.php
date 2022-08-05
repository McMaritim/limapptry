<?php

function generateMenu($menuArray) {
    $menu = "";
    for ($i = 1; $i <= count($menuArray); $i++) {
        $menu .= $i . ": " . $menuArray[($i - 1)];
        if ($i != count($menuArray)) {
            $menu .= "\n";
        }
    }
    return $menu;
}

function cleanUssdString($ussdString) {
    if (strpos($ussdString, "*98*") !== false) {
        $ussdString = str_replace("\\*98\\*", "*", $ussdString);
    }

    if (strpos($ussdString, "*0*") !== false) {
        $ussdString = str_replace("\\*0\\*", "*", $ussdString);
    }
    return $ussdString;
}

function isValidName($name) {
    if ($name == " ") {
        return false;
    } elseif (is_numeric($name)) {
        return false;
    } else {
        return true;
    }
}

function isRequiredMinimumSize($string, $requiredSize) {
    if (strlen($string) >= $requiredSize) {
        return true;
    } else {
        return false;
    }
}

function isValidIdNumber($idNumber) {
    $idNumber = str_replace(" ", "", $idNumber);
    if (strlen($idNumber) < 5) {
        return false;
    } else {
        return true;
    }
}

function isValidPIN($pin) {
    if (strlen($pin) < 4) {
        return false;
    } else {
        return true;
    }
}


function generateStk($shortcode, $msisdn, $amount, $reference, $transactionDesc, $ussdSessionId) {
    $msisdn = ltrim($msisdn, '+');
    $post = array(
        "shortcode" => $shortcode,
        "msisdn" => $msisdn,
        "amount" => $amount,
        "reference" => $msisdn
    );
    $data_string = json_encode($post);
    $url = "https://payments.afyacash.app/afyacash_stk.php";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $resp = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
        error_log("[ERROR: " . date("Y-m-d H:i:s") . "] generateStkPush() error: " . $err . "\n", 3, LOG_FILE);
    if ($err) {
        error_log("[ERROR: " . date("Y-m-d H:i:s") . "] generateStkPush() error: " . $err . "\n", 3, LOG_FILE);
        return false;
    } else {
        return true;
    }
}


function mpesaTrans($msisdn) {
    $msisdn = ltrim($msisdn, '+');
    $post = array(
        "msisdn" => $msisdn
    );
    $data_string = json_encode($post);
    $url = "https://portal.afyacash.app/api/trans/msisdn";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $resp = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        error_log("[ERROR: " . date("Y-m-d H:i:s") . "] generateStkPush() error: " . $err . "\n", 3, LOG_FILE);
        return false;
    } else {
        return $resp;
    }
}
