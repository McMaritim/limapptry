<?php
include_once('./QueryManager.php');
include_once('MenuItems.php');
include_once('UssdUtils.php');
class KishwahiAction {
    public function process($ussdSession) {
        $menuItems = new MenuItems();
        $menuSuffix = "\n00 Home";
        $params = explode("*", $ussdSession->ussdProcessString);
        $lastSelection = trim($params[count($params) - 1]);
            if(MenuItems::MAINMENU_REQ == $ussdSession->previousFeedbackType){
                $ussdSession = $menuItems->setKishwahiliMenu($ussdSession);
                $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::KISWAHILIMENU_REQ == $ussdSession->previousFeedbackType) {
                if ("1" == $lastSelection) {
                    $ussdSession = $menuItems->setAccountCategoryKis($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                } elseif ("2" == $lastSelection) {
                    $ussdSession = $menuItems->setOrderProductsRequestKis($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                } else {
                    $ussdSession = $menuItems->setKishwahiliMenu($ussdSession);     
                    $reply = "CON INVALID INPUT. Only number 1-2 allowed.\n" . $ussdSession->currentFeedbackString;

                }          
            } elseif (MenuItems::MYACCOUNT_CATEGORY_REQ_KIS == $ussdSession->previousFeedbackType) {
                if (is_numeric($lastSelection) && $lastSelection > 0 && $lastSelection <= 2) {
                    if ("1" == $lastSelection) {//My Vehicles
                        $ussdSession = $menuItems->setProfileKis($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    } else {//Add Vehicle (lastSelection.equals("2"))
                        $ussdSession = $menuItems->setMiniStatementReqKis($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }
                } else {
                    $ussdSession = $menuItems->setAccountCategoryKis($ussdSession);
                    $reply = "CON INVALID INPUT. Only number 1-3 allowed.\n" . $ussdSession->currentFeedbackString;
                }
          
  
            } elseif (MenuItems::PRODUCT_REQ_KIS == $ussdSession->previousFeedbackType) {
                $displayedRateIdsListArray = explode("#", UssdSession::getUserParam(UssdSession::DISPLAYED_RATE_IDS, $ussdSession->userParams));
                if (is_numeric($lastSelection) && $lastSelection >=1 && $lastSelection <= count($displayedRateIdsListArray)) {
                    $selectedRateId = $displayedRateIdsListArray[$lastSelection - 1];
                    $userParams = $ussdSession->userParams . UssdSession::SELECTED_RATE_ID . "=" . $selectedRateId . "*";
                    $ussdSession->userParams = $userParams;
                    $ratesList = getRatesListWithId($selectedRateId);
                    $itemPayable = $ratesList[0]->product_name." @ Ksh".$ratesList[0]->rate;
                     error_log("[ERROR : " . date("Y-m-d H:i:s") . "] query RATE1 SELECTED\nParams=" . print_r($itemPayable, true), 3, LOG_FILE);
                    $ussdSession = $menuItems->setQuantityRequestKis($ussdSession, $itemPayable);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                } else {
                    $ussdSession = $menuItems->setOrderProductsRequestKis($ussdSession);
                    $reply = "CON PEMBEJEO ISIYO HALALI. Chagua kutoka 1-" . ".\n" . $ussdSession->currentFeedbackString;
                }

            } elseif (MenuItems::QUANTITY_REQ_KIS == $ussdSession->previousFeedbackType) {
                $ratesList = getRatesListWithId(UssdSession::getUserParam(UssdSession::SELECTED_RATE_ID, $ussdSession->userParams));
                if (is_numeric($lastSelection)) {
                    $paymentAmount = $ratesList[0]->rate * $lastSelection;
                    error_log("[ERROR : " . date("Y-m-d H:i:s") . "] query TOTAL TO MPESA \nParams=" . print_r($paymentAmount, true), 3, LOG_FILE);

                    $accountRef = substr($ratesList[0]->product_name, 0, 12) . " x" . $lastSelection;
                    $transactionDescription = $accountRef . " Malipo";
                    $ref = time().rand(10000000000000000,90000000000000000);
                    if (generateStk(MpesaRequest::PAYBILL, $ussdSession->msisdn, $paymentAmount, $accountRef, $transactionDescription, $ref)) {
                        $ussdSession = $menuItems->setSTKSuccessResponse($ussdSession);
                        $reply = "END " . $ussdSession->currentFeedbackString;
                        $mpesaRequest = new MpesaRequest();
                        $mpesaRequest->ussdSessionId = $ussdSession->sessionId;
                        $mpesaRequest->msisdn = $ussdSession->msisdn;
                        $mpesaRequest->amount = $paymentAmount;
                        $mpesaRequest->mpesaReceiptNumber = $ref;
                        $mpesaRequest->rateId = $ratesList[0]->product_id;

                        $mpesaRequest->paymentStatus = MpesaRequest::PAYMENTSTATUS_NOTPAID;
                        $mpesaRequest->accountReference = $accountRef;
                        $mpesaRequest->transactionDescription = $transactionDescription;
                        saveMpesaRequest($mpesaRequest);
                    } else {
                        $ussdSession = $menuItems->setSTKFailureResponse($ussdSession);
                        $reply = "END " . $ussdSession->currentFeedbackString;
                    }
                } else {
                    $itemPayable = $ratesList[0]->product_name;
                    $ussdSession = $menuItems->setQuantityRequestKis($ussdSession, $itemPayable);
                    $reply = "CON PEMBEJEO ISIYO HALALI. Nambari tu zinaruhusiwa.\n" . $ussdSession->currentFeedbackString . $menuSuffix;
                }
            } else {
                    $reply = "END HITILAFU YA MUUNGANISHO. Tafadhali jaribu tena..";
            }
                $ussdSession->currentFeedbackString = $reply;
                return $ussdSession;          
      
    }
}

