<?php

include_once('./Models.php');
include_once('./QueryManager.php');
include_once('MenuItems.php');
include_once('UssdUtils.php');

class RegistrationAction {

    public function process($ussdSession) {
        $menuItems = new MenuItems();
        $menuSuffix = "\n00 Home";
        $params = explode("*", $ussdSession->ussdProcessString);
        $lastSelection = trim($params[count($params) - 1]);

        if ($ussdSession->ussdProcessString == "") {
            $ussdSession = $menuItems->setFirstNameRequest($ussdSession);
            $reply = "CON Welcome to LIMA Platform. " . $ussdSession->currentFeedbackString;
        } else {
            $params = explode("*", $ussdSession->ussdProcessString);

            if (MenuItems::FIRSTNAME_REQ == $ussdSession->previousFeedbackType) {
                $firstName = trim($params[count($params) - 1]);
                if (isValidName($firstName)) {
                    $userParams = UssdSession::FIRSTNAME . "=" . $firstName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setLastNameRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setFirstNameRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }
      
            } elseif (MenuItems::LASTNAME_REQ == $ussdSession->previousFeedbackType) {
                $lastName = trim($params[count($params) - 1]);
                if (isValidName($lastName)) {
                    $userParams = $ussdSession->userParams . UssdSession::LASTNAME . "=" . $lastName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setIdNumberRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setLastNameRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }
            } elseif (MenuItems::ID_NUMBER_REQ == $ussdSession->previousFeedbackType) {
                $lastName = trim($params[count($params) - 1]);
                if (isValidIdNumber($lastName)) {
                    $userParams = $ussdSession->userParams . UssdSession::IDNUMBER . "=" . $lastName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setCountyRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setIdNumberRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }

            } elseif (MenuItems::COUNTY_REQ == $ussdSession->previousFeedbackType) {
                $lastName = trim($params[count($params) - 1]);
                if (isValidIdNumber($lastName)) {
                    $userParams = $ussdSession->userParams . UssdSession::COUNTY . "=" . $lastName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setSubCountyRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setCountyRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }

            } elseif (MenuItems::SUB_COUNTY_REQ == $ussdSession->previousFeedbackType) {
                $idNumber = trim($params[count($params) - 1]);
                if (isValidIdNumber($idNumber)) {
                    $userParams = $ussdSession->userParams . UssdSession::SUB_COUNTY . "=" . $idNumber . "*";
                    $ussdSession->userParams = $userParams;
                    $reply = "END " . self::registerNewUser($ussdSession);
                } else {
                    $ussdSession = $menuItems->setIdNumberRequest($ussdSession);

                        $reply = "CON You entered an INVALID ID number.\n" . $ussdSession->currentFeedbackString;

                }
      
            // } elseif (MenuItems::COUNTY_ID_REQ == $ussdSession->previousFeedbackType) {
            //     $displayedRateCategoryCodesArray = explode("#", UssdSession::getUserParam(UssdSession::DISPLAY_COUNTY_ID, $ussdSession->userParams));
            //     $rateCategoriesSize = count($displayedRateCategoryCodesArray);

            //     if (is_numeric($lastSelection) && $lastSelection > 0 && $lastSelection <= $rateCategoriesSize) {
            //         $userParams = $ussdSession->userParams . UssdSession::DISPLAY_COUNTY_ID . "=" . $rateCategoriesSize . "*";
            //         $ussdSession->userParams = $userParams;
            //         $ussdSession = $menuItems->setSubCountyRequest($ussdSession);
            //         $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;   
                              
            //     } else {
            //         $ussdSession = $menuItems->setCountyRequest($ussdSession);
            //         $reply = "CON INVALID INPUT. Select from 1-" . $rateCategoriesSize . ".\n" . $ussdSession->currentFeedbackString;
                    
            //     }
            // } elseif (MenuItems::SUB_COUNTY_ID_REQ == $ussdSession->previousFeedbackType) {
        
            //     $displayedRateCategoryCodesArray = explode("#", UssdSession::getUserParam(UssdSession::DISPLAY_SUB_COUNTY_ID, $ussdSession->userParams));
               
            //     $rateCategoriesSize = count($displayedRateCategoryCodesArray);
            //     if (is_numeric($lastSelection) && $lastSelection > 0 && $lastSelection <= $rateCategoriesSize) {

            //         $userParams = $ussdSession->userParams . UssdSession::DISPLAY_SUB_COUNTY_ID . "=" . $rateCategoriesSize . "*";
            //         $ussdSession->userParams = $userParams;
            //         $reply = "END " . self::registerNewUser($ussdSession);
                   
            //     } else {
            //         $ussdSession = $menuItems->setSubCountyRequest($ussdSession);
            //         $reply = "CON INVALID INPUT. Select from 1-" . $rateCategoriesSize . ".\n" . $ussdSession->currentFeedbackString;
            //           //  $reply = "CON You entered an INVALID CCC number.\n" . $ussdSession->currentFeedbackString;
            //     }
            // } elseif (MenuItems::REG_NUMBER_REQ == $ussdSession->previousFeedbackType) {
            //     $lastName = trim($params[count($params) - 1]);
            //     if (isValidName($lastName)) {
            //         $userParams = $ussdSession->userParams . UssdSession::REGNUMBER . "=" . $lastName . "*";
            //         $ussdSession->userParams = $userParams;
            //         $ussdSession = $menuItems->registerNewUser($ussdSession);
            //         $reply = "CON " . $ussdSession->currentFeedbackString;
            //     } else {
            //         $ussdSession = $menuItems->setLastNameRequest($ussdSession);
            //             $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
            //     }
            }
        }
        $ussdSession->currentFeedbackString = $reply;
        return $ussdSession;
    }


    function registerNewUser($ussdSession){
        $ussdUser = new Persons();
        $ussdUser->phone_no = $ussdSession->msisdn;
        $ussdUser->first_name = UssdSession::getUserParam(UssdSession::FIRSTNAME, $ussdSession->userParams);
        $ussdUser->last_name = UssdSession::getUserParam(UssdSession::LASTNAME, $ussdSession->userParams);
        $ussdUser->id_number = UssdSession::getUserParam(UssdSession::IDNUMBER, $ussdSession->userParams);
        $ussdUser->county = UssdSession::getUserParam(UssdSession::COUNTY, $ussdSession->userParams);
        $ussdUser->sub_county = UssdSession::getUserParam(UssdSession::SUB_COUNTY, $ussdSession->userParams);
     

        if(createUssdUser($ussdUser)){
                return "You have been registered successfully!";
            
        } else {
                return "There was an error in your registration. Please try again.";
            
        }
    }

}
