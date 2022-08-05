<?php

include_once('UssdUtils.php');
include_once('./QueryManager.php');
class MenuItems {
    const MAINMENU_REQ = "MAINMENU_REQ";
    const PROFILE_REQ = "PROFILE_REQ";
    const PROFILE_REQ_KIS = "PROFILE_REQ_KIS";
	const LANGUAGE_REQ = "LANGUAGE_REQ";
	const FIRSTNAME_REQ = "FIRSTNAME_REQ";
    const LASTNAME_REQ = "LASTNAME_REQ";
    const ID_NUMBER_REQ = "ID_NUMBER_REQ";
    const COUNTY_ID_REQ =	"COUNTY_ID_REQ";
    const LEVEL_ID_REQ = "LEVEL_ID_REQ";
    const SUB_COUNTY_ID_REQ = "SUB_COUNTY_ID_REQ";
    const REG_NUMBER_REQ = "REG_NUMBER_REQ";
    const REQUEST_VET_REQ  = "REQUEST_VET_REQ";
    const ORDER_PRODUCTS_REQ = "ORDER_PRODUCTS_REQ";
    const CHOOSE_PAYMENT_REQ = "CHOOSE_PAYMENT_REQ";
    const PAY_NOW_REQ = "PAY_NOW_REQ";
    const PAY_LATER_REQ = "PAY_LATER_REQ";
    const PRODUCT_REQ = "PRODUCT_REQ";
    const QUANTITY_REQ = "QUANTITY_REQ";
    const MYACCOUNT_CATEGORY_REQ = "MYACCOUNT_CATEGORY_REQ";
    const KISWAHILIMENU_REQ = "KISWAHILIMENU_REQ";
    const ENGLISHMENU_REQ = "ENGLISHMENU_REQ";
    const REQUEST_VET_REQ_KIS = "REQUEST_VET_REQ_KIS";
    const PRODUCT_REQ_KIS = "PRODUCT_REQ_KIS";
    const STK_FAILURE_RESPONSE = "STK_FAILURE_RESPONSE";
    const STK_SUCCESS_RESPONSE = "STK_SUCCESS_RESPONSE";
    const MINI_STMT_REQ = "MINI_STMT_REQ";
    const MINI_STMT_REQ_KIS = "MINI_STMT_REQ_KIS";
    const MYACCOUNT_CATEGORY_REQ_KIS = "MYACCOUNT_CATEGORY_REQ_KIS";
    const QUANTITY_REQ_KIS = "QUANTITY_REQ_KIS";
    const COUNTY_REQ = "COUNTY_REQ";
    const SUB_COUNTY_REQ = "SUB_COUNTY_REQ";
  //  const MEAT_INSPECTION_REQ = "MEAT_INSPECTION_REQ";





     var $reply;
    var $userParams;


    
    public function setFirstNameRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter your First Name:";
        $ussdSession->currentFeedbackType = self::FIRSTNAME_REQ;
        return $ussdSession;
    }
    public function setLastNameRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter your Last Name:";
        $ussdSession->currentFeedbackType = self::LASTNAME_REQ;
        return $ussdSession;
    }
    public function setIdNumberRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter ID Number:";
        $ussdSession->currentFeedbackType = self::ID_NUMBER_REQ;
        return $ussdSession;
    }
    public function setCountyRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Your County:";
        $ussdSession->currentFeedbackType = self::COUNTY_REQ;
        return $ussdSession;
    }
    public function setSubCountyRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Your Sub County:";
        $ussdSession->currentFeedbackType = self::SUB_COUNTY_REQ;
        return $ussdSession;
    }

    public function setLevelRequest($ussdSession) {
        $levelCategoryList = getLevelList();
        $reply = "Select Category:";
        if (count($levelCategoryList) > 0) {
            $displayedLevelCategoryList = "";
            for ($i = 1; $i <= count($levelCategoryList); $i++) {
                $reply .= "\n" . $i . ":" . $levelCategoryList[$i - 1]->level_name;
                if ($i != count($levelCategoryList)) {
                    $displayedLevelCategoryList .= $levelCategoryList[$i - 1]->level_id . "#";
                } else {
                    $displayedLevelCategoryList .= $levelCategoryList[$i - 1]->level_id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::DISPLAY_LEVEL_ID . "=" . $displayedLevelCategoryList . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Category was found.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::LEVEL_ID_REQ;
        return $ussdSession;
    }  
    // public function setCountyRequest($ussdSession) {
    //     $countyCategoryList = getCountyList();
    //     $reply = "Select County:";
    //     if (count($countyCategoryList) > 0) {
    //         $displayedCountyCategoryList = "";
    //         for ($i = 1; $i <= count($countyCategoryList); $i++) {
    //             $reply .= "\n" . $i . ":" . $countyCategoryList[$i - 1]->name;
    //             if ($i != count($countyCategoryList)) {
    //                 $displayedCountyCategoryList .= $countyCategoryList[$i - 1]->id . "#";
    //             } else {
    //                 $displayedCountyCategoryList .= $countyCategoryList[$i - 1]->id;
    //             }
    //         }
    //         $userParams = $ussdSession->userParams . UssdSession::DISPLAY_COUNTY_ID . "=" . $displayedCountyCategoryList . "*";
    //         $ussdSession->userParams = $userParams;
    //     } else {
    //         $reply = "\nCounty not found.";
    //     }
    //     $ussdSession->currentFeedbackString = $reply;
    //     $ussdSession->currentFeedbackType = self::COUNTY_ID_REQ;
    //     return $ussdSession;
    // } 
    
    // public function setSubCountyRequest($ussdSession) {
    //     $subCountyCategoryList = getSubCountyList();
    //     $reply = "Select Sub County:";
    //     if (count($subCountyCategoryList) > 0) {
    //         $displayedCountyCategoryList = "";
    //         for ($i = 1; $i <= count($subCountyCategoryList); $i++) {
    //             $reply .= "\n" . $i . ":" . $subCountyCategoryList[$i - 1]->name;
    //             if ($i != count($subCountyCategoryList)) {
    //                 $displayedCountyCategoryList .= $subCountyCategoryList[$i - 1]->id . "#";
    //             } else {
    //                 $displayedCountyCategoryList .= $subCountyCategoryList[$i - 1]->id;
    //             }
    //         }
    //         $userParams = $ussdSession->userParams . UssdSession::DISPLAY_SUB_COUNTY_ID . "=" . $displayedCountyCategoryList . "*";
    //         $ussdSession->userParams = $userParams;
    //     } else {
    //         $reply = "\nSub County not found.";
    //     }
    //     $ussdSession->currentFeedbackString = $reply;
    //     $ussdSession->currentFeedbackType = self::SUB_COUNTY_ID_REQ;
    //     return $ussdSession;
    // }
    public function setRegNumberRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Registration Number:";
        $ussdSession->currentFeedbackType = self::REG_NUMBER_REQ;
        return $ussdSession;
    }  



    public function setMainMenu($ussdSession) {
        $userId = UssdSession::getUserParam(UssdSession::USER_ID, $ussdSession->userParams);

        $userParams = UssdSession::USER_ID . "=" . $userId . "*";
        $ussdSession->userParams = $userParams;
        $menuArray = array("English","Kiswahili");
        $ussdSession->currentFeedbackString = "Lima USSD Platform:\nSelect one(Chagua moja):\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::MAINMENU_REQ;
        return $ussdSession;
    }
    // public function setEnglishMenu($ussdSession) {
    //     $userId = UssdSession::getUserParam(UssdSession::USER_ID, $ussdSession->userParams);
    //     $userParams = UssdSession::USER_ID . "=" . $userId . "*";
    //     $ussdSession->userParams = $userParams;
    //     $menuArray = array("My Account", "Order Products");
    //     $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
    //     $ussdSession->currentFeedbackType = self::ENGLISHMENU_REQ;
    //     return $ussdSession;
    // }

    public function setEnglishMenu($ussdSession) {
        $menuArray = array("My Account", "Order Products");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::ENGLISHMENU_REQ;
        return $ussdSession;
    }

    public function setKishwahiliMenu($ussdSession) {
        $menuArray = array("Akaunti yangu", "Bidhaa za Agizo");
        $ussdSession->currentFeedbackString = "Chagua moja:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::KISWAHILIMENU_REQ;
        return $ussdSession;
    }


    // public function setKishwahiliMenu($ussdSession) {

    //     $userId = UssdSession::getUserParam(UssdSession::USER_ID, $ussdSession->userParams);
    //     $userParams = UssdSession::USER_ID . "=" . $userId . "*";
    //     $ussdSession->userParams = $userParams;
    //     $menuArray = array("Akaunti yangu", "Bidhaa za Agizo");
    //     $ussdSession->currentFeedbackString = "Chagua moja:\n" . generateMenu($menuArray);
    //     $ussdSession->currentFeedbackType = self::KISWAHILIMENU_REQ;
    //     return $ussdSession;
    // }
   
    public function setOrderProductsRequest($ussdSession) {
        $rateCategoryList = getRatesListWithCategoryCode(Rate::CATEGORY_PRODUCT);
      //  $rateCategoryList = getProductsList();
        $reply = "Product Category:";
        if (count($rateCategoryList) > 0) {
            $displayedRateCategoryList = "";
            for ($i = 1; $i <= count($rateCategoryList); $i++) {
                $reply .= "\n" . $i . ":" . $rateCategoryList[$i - 1]->product_name." "."Ksh: ".$rateCategoryList[$i - 1]->rate."\n";
                if ($i != count($rateCategoryList)) {
                    $displayedRateCategoryList .= $rateCategoryList[$i - 1]->product_id . "#";
                } else {
                    $displayedRateCategoryList .= $rateCategoryList[$i - 1]->product_id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::DISPLAYED_RATE_IDS . "=" . $displayedRateCategoryList . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Record found.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::PRODUCT_REQ;
        return $ussdSession;
    } 

    public function setOrderProductsRequestKis($ussdSession) {
        $rateCategoryList = getRatesListWithCategoryCode(Rate::CATEGORY_PRODUCT);
      //  $rateCategoryList = getProductsList();
        $reply = "Chagua Jamii:";
        if (count($rateCategoryList) > 0) {
            $displayedRateCategoryList = "";
            for ($i = 1; $i <= count($rateCategoryList); $i++) {
                $reply .= "\n" . $i . ":" . $rateCategoryList[$i - 1]->product_name." "."Ksh: ".$rateCategoryList[$i - 1]->rate."\n";
                if ($i != count($rateCategoryList)) {
                    $displayedRateCategoryList .= $rateCategoryList[$i - 1]->product_id . "#";
                } else {
                    $displayedRateCategoryList .= $rateCategoryList[$i - 1]->product_id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::DISPLAYED_RATE_IDS . "=" . $displayedRateCategoryList . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nHakuna kitengo cha Kiwango kilichopatikana.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::PRODUCT_REQ_KIS;
        return $ussdSession;
    } 

    public function setVetRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Reason for vet:";
        $ussdSession->currentFeedbackType = self::REQUEST_VET_REQ;
        return $ussdSession;
    }
    public function setVetRequestKis($ussdSession) {
        $ussdSession->currentFeedbackString = "Sababu ya daktari wa wanyama:";
        $ussdSession->currentFeedbackType = self::REQUEST_VET_REQ_KIS;
        return $ussdSession;
    }
    public function setChoosePayment($ussdSession) {
        $menuArray = array("Pay Now", "Pay Later");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::CHOOSE_PAYMENT_REQ;
        return $ussdSession;
    }
    public function setSTKSuccessResponse($ussdSession) {
        $ussdSession->currentFeedbackString = "Please wait to input your Mpesa PIN to complete payment.";
        $ussdSession->currentFeedbackType = self::STK_SUCCESS_RESPONSE;
        return $ussdSession;
    }


    public function setSTKFailureResponse($ussdSession) {
        $ussdSession->currentFeedbackString = "We are not able to complete your transaction at the moment. Try again later.";
        $ussdSession->currentFeedbackType = self::STK_FAILURE_RESPONSE;
        return $ussdSession;
    }

    public function setQuantityRequestKis($ussdSession, $itemPayable) {
        $ussdSession->currentFeedbackString = "Weka Kiwango unachotaka kulipa kwa " . $itemPayable . ":";
        $ussdSession->currentFeedbackType = self::QUANTITY_REQ_KIS;
        return $ussdSession;
    }
    
    public function setQuantityRequest($ussdSession, $itemPayable) {
        $ussdSession->currentFeedbackString = "Enter quantity to pay for " . $itemPayable . ":";
        $ussdSession->currentFeedbackType = self::QUANTITY_REQ;
        return $ussdSession;
    }



    public function setAccountCategory($ussdSession) {
        $menuArray = array("My Profile", "Mini Statement");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::MYACCOUNT_CATEGORY_REQ;
        return $ussdSession;
    }
    
    public function setAccountCategoryKis($ussdSession) {
        $menuArray = array("Profaili yangu", "Taarifa ndogo");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::MYACCOUNT_CATEGORY_REQ_KIS;
        return $ussdSession;
    }
 


    public function setMiniStatementReq($ussdSession) {
        $mpesaRequestsList = getPaidMpesaRequestsList($ussdSession->msisdn);
        $reply = "Mini statement:";
        if (count($mpesaRequestsList) > 0) {
            for ($i = 0; $i < count($mpesaRequestsList); $i++) {
                $reply .= "\n" . date_format(new DateTime($mpesaRequestsList[$i]->transactionDate), "d/m") . " Sh." . $mpesaRequestsList[$i]->amount . " " . substr($mpesaRequestsList[$i]->transactionDescription, 0, 10);
            }
        } else {
            $reply = "No transaction was found.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::MINI_STMT_REQ;
        return $ussdSession;
    }

    public function setMiniStatementReqKis($ussdSession) {
        $mpesaRequestsList = getPaidMpesaRequestsList($ussdSession->msisdn);
        $reply = "Taarifa ndogo:";
        if (count($mpesaRequestsList) > 0) {
            for ($i = 0; $i < count($mpesaRequestsList); $i++) {
                $reply .= "\n" . date_format(new DateTime($mpesaRequestsList[$i]->transactionDate), "d/m") . " Sh." . $mpesaRequestsList[$i]->amount . " " . substr($mpesaRequestsList[$i]->transactionDescription, 0, 10);
            }
        } else {
            $reply = "Hakuna shughuli yoyote iliyopatikana.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::MINI_STMT_REQ_KIS;
        return $ussdSession;
    }
    public function setProfile($ussdSession) {
        $profileRequestsList = getUssdUserList($ussdSession->msisdn);
        $reply = "Profile:";
        if (count($profileRequestsList) > 0) {
            for ($i = 0; $i < count($profileRequestsList); $i++) {
                $reply .= "\n" . " Name: ." . $profileRequestsList[$i]->first_name . " " . $profileRequestsList[$i]->last_name ."\n "."ID Number: ". $profileRequestsList[$i]->id_number;
            }
        } else {
            $reply = "No Profilefound.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::PROFILE_REQ;
        return $ussdSession;
    }

    public function setProfileKis($ussdSession) {
        $profileRequestsList = getUssdUserList($ussdSession->msisdn);
        $reply = "Profaili:";
        if (count($profileRequestsList) > 0) {
            for ($i = 0; $i < count($profileRequestsList); $i++) {
                $reply .= "\n" . " Name: ." . $profileRequestsList[$i]->first_name . " " . $profileRequestsList[$i]->last_name ."\n "."ID Number: ". $profileRequestsList[$i]->id_number;
            }
        } else {
            $reply = "Hakuna Profaili Iliyopatikana.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::PROFILE_REQ_KIS;
        return $ussdSession;
    }
}