<?php

/**
 * @author Phuc Duong <phuc.duong@kiss-concept.com>
 * @desc to management staticContent
 */
class Admin_Model_StaticContents extends Admin_Model_Abstract {

    protected $_name = "StaticContents";
    protected $_primary = "StaticContentId";

    /**
     * @author Phuc Duong <phuc.duong@kiss-concept.com>
     * @desc get static content value by code
     * @param string $code Code of Static Content
     */
    public function getContentValueByCode($code) {
        $select = $this->select()
                ->from($this->_name, array("StaticContent", "IsSerialize", "StaticContents.LastUpdatedBy", "StaticContents.LastUpdated", "Users.FirstName", "Users.LastName", "StaticCode"))
                ->setIntegrityCheck(false)
                ->join("Users", "StaticContents.LastUpdatedBy = UserId")
                ->where("StaticCode=?", $code);
        $res = $this->fetchRow($select);
        if (!empty($res)) {
            if (!empty($res->StaticContent)) {
                return $res;
                if (!empty($res->IsSerialize)) {
                    return unserialize($res->StaticContent);
                } else {
                    return $res->StaticContent;
                }
            }
        }
        return null;
    }

    /**
     * @author Phuc Duong
     * @desc update data from user
     * @param array $data post from user
     * @return int
     */
    public function updateAbout($data) {
        if (!empty($data)) {
            // update about line
            if (!empty($data['section']) && $data['section'] == 'text') {
                $aboutText = strip_tags($data['about_text'], '<br><b><strong>');
                if (!empty($aboutText)) {
                    $res1 = $this->update(array("StaticContent" => $aboutText, "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = 'ABOUT_TEXT'");
                }
                if (!empty($res1)) {
                    return true;
                }
            }

            // update about address
            if (!empty($data['section']) && $data['section'] == 'address') {
                if (!empty($data['about_address1'])) {
                    $res1 = $this->update(array("StaticContent" => $data['about_address1'], "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = 'ABOUT_ADDRESS1'");
                }
                $res2 = $this->update(array("StaticContent" => $data['about_address2'], "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = 'ABOUT_ADDRESS2'");

                if (!empty($res1) && !empty($res2)) {
                    return true;
                }
            }

            // update about lettalk
            if (!empty($data['section']) && $data['section'] == 'lettalk') {
                $dataLetTalk = $this->_generateLetTalkInfo($data);
                if (!empty($dataLetTalk)) {
                    return $this->update(array("StaticContent" => $dataLetTalk, "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = 'ABOUT_LETSTALK'");
                }
            }
        }
        return false;
    }

    /**
     * @author Phuc Duong
     * @desc update footer
     * @param array $data
     */
    public function updateFooter($data) {
        if (!empty($data['name']) && !empty($data['phone']) && !empty($data['email'])) {
            return $this->update(array("StaticContent" => serialize($data), "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = 'FOOTER_CONTACT'");
        }
    }

    /**
     * @author Phuc Duong
     * @desc update footer
     * @param array $data
     */
    public function updatePitch($data) {
        if (!empty($data['primary_pitch_line1']) && !empty($data['second_pitch'])) {
            return $this->update(array("StaticContent" => serialize($data), "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = 'HOME_PITCH'");
        }
    }

    /**
     * @author Quang Nguyen
     * @desc update heading
     * @param array $data Description
     * @return int
     * @since 2013-01-03
     */
    public function updateHeading($data) {
        if (!empty($data['home_people']) && !empty($data['home_technology'])) {
            return $this->update(array("StaticContent" => serialize($data), "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = 'ALL_HEADING'");
        }
    }

    /**
     * @author Phuc Duong
     * @desc update technology
     * @param array $data Description
     * @return int
     * @since 2013-01-03
     */
    public function updateTechnology($data) {
        $technology = strip_tags($data['technology'], '<br><b><strong>');
        if (!empty($technology)) {
            $res = $this->update(array("StaticContent" => $technology, "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = 'HOME_TECHNOLOGY'");
        }
        return $res;
    }

    /**
     * @author Phuc Duong
     */
    public function updateRichText($data, $code){
        $technology = strip_tags($data['content'], '<br><b><strong>');
        if (!empty($technology)) {
            $res = $this->update(array("StaticContent" => $technology, "LastUpdated" => new Zend_Db_Expr("NOW()"), "LastUpdatedBy" => USER_ID), "StaticCode = '".$code."'");
        }
        return $res;
    }

    /**
     * @author Phuc Duong
     * @desc generate let talk information
     * @param array $letTalk
     */
    private function _generateLetTalkInfo($letTalk) {
        $numberLetTalk = count($letTalk['name']);
        $arrLetTalk = array();
        $emailValidate = new Zend_Validate_EmailAddress();
        for ($i = 0; $i < $numberLetTalk; $i++) {
            if ($this->_validateLetTalk($emailValidate, $letTalk['name'][$i], $letTalk['phone'][$i], $letTalk['email'][$i]))
                $arrLetTalk[$i] = array(
                    'name' => $letTalk['name'][$i],
                    'phone' => $letTalk['phone'][$i],
                    'email' => strtolower($letTalk['email'][$i])
                );
        }
        if (!empty($arrLetTalk)) {
            return serialize($arrLetTalk);
        } else {
            return null;
        }
    }

    /**
     * @desc validate when update lettalk
     * @author Phuc Duong <phuc.duong@kiss-concept.com>
     * @param Zend_Validate_EmailAddress $emailValidate
     * @return boolean
     */
    private function _validateLetTalk($emailValidate, $name, $phone, $email) {
        // check name is required
        if (empty($name)) {
            return false;
        }

        // check phone is required
        if (empty($phone)) {
            return false;
        }

        // check email is required and syntax correct
        if (empty($email) || !$emailValidate->isValid($email)) {
            return false;
        }

        return true;
    }

}

?>
