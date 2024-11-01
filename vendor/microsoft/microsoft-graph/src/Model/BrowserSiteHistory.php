<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* BrowserSiteHistory File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Microsoft\Graph\Model;
/**
* BrowserSiteHistory class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class BrowserSiteHistory extends Entity
{
    /**
    * Gets the allowRedirect
    * Controls the behavior of redirected sites. If true, indicates that the site will open in Internet Explorer 11 or Microsoft Edge even if the site is navigated to as part of a HTTP or meta refresh redirection chain.
    *
    * @return bool|null The allowRedirect
    */
    public function getAllowRedirect()
    {
        if (array_key_exists("allowRedirect", $this->_propDict)) {
            return $this->_propDict["allowRedirect"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowRedirect
    * Controls the behavior of redirected sites. If true, indicates that the site will open in Internet Explorer 11 or Microsoft Edge even if the site is navigated to as part of a HTTP or meta refresh redirection chain.
    *
    * @param bool $val The value of the allowRedirect
    *
    * @return BrowserSiteHistory
    */
    public function setAllowRedirect($val)
    {
        $this->_propDict["allowRedirect"] = $val;
        return $this;
    }
    /**
    * Gets the comment
    * The comment for the site.
    *
    * @return string|null The comment
    */
    public function getComment()
    {
        if (array_key_exists("comment", $this->_propDict)) {
            return $this->_propDict["comment"];
        } else {
            return null;
        }
    }

    /**
    * Sets the comment
    * The comment for the site.
    *
    * @param string $val The value of the comment
    *
    * @return BrowserSiteHistory
    */
    public function setComment($val)
    {
        $this->_propDict["comment"] = $val;
        return $this;
    }

    /**
    * Gets the compatibilityMode
    * Controls what compatibility setting is used for specific sites or domains. The possible values are: default, internetExplorer8Enterprise, internetExplorer7Enterprise, internetExplorer11, internetExplorer10, internetExplorer9, internetExplorer8, internetExplorer7, internetExplorer5, unknownFutureValue.
    *
    * @return BrowserSiteCompatibilityMode|null The compatibilityMode
    */
    public function getCompatibilityMode()
    {
        if (array_key_exists("compatibilityMode", $this->_propDict)) {
            if (is_a($this->_propDict["compatibilityMode"], "\Microsoft\Graph\Model\BrowserSiteCompatibilityMode") || is_null($this->_propDict["compatibilityMode"])) {
                return $this->_propDict["compatibilityMode"];
            } else {
                $this->_propDict["compatibilityMode"] = new BrowserSiteCompatibilityMode($this->_propDict["compatibilityMode"]);
                return $this->_propDict["compatibilityMode"];
            }
        }
        return null;
    }

    /**
    * Sets the compatibilityMode
    * Controls what compatibility setting is used for specific sites or domains. The possible values are: default, internetExplorer8Enterprise, internetExplorer7Enterprise, internetExplorer11, internetExplorer10, internetExplorer9, internetExplorer8, internetExplorer7, internetExplorer5, unknownFutureValue.
    *
    * @param BrowserSiteCompatibilityMode $val The value to assign to the compatibilityMode
    *
    * @return BrowserSiteHistory The BrowserSiteHistory
    */
    public function setCompatibilityMode($val)
    {
        $this->_propDict["compatibilityMode"] = $val;
         return $this;
    }

    /**
    * Gets the lastModifiedBy
    * The user who last modified the site.
    *
    * @return IdentitySet|null The lastModifiedBy
    */
    public function getLastModifiedBy()
    {
        if (array_key_exists("lastModifiedBy", $this->_propDict)) {
            if (is_a($this->_propDict["lastModifiedBy"], "\Microsoft\Graph\Model\IdentitySet") || is_null($this->_propDict["lastModifiedBy"])) {
                return $this->_propDict["lastModifiedBy"];
            } else {
                $this->_propDict["lastModifiedBy"] = new IdentitySet($this->_propDict["lastModifiedBy"]);
                return $this->_propDict["lastModifiedBy"];
            }
        }
        return null;
    }

    /**
    * Sets the lastModifiedBy
    * The user who last modified the site.
    *
    * @param IdentitySet $val The value to assign to the lastModifiedBy
    *
    * @return BrowserSiteHistory The BrowserSiteHistory
    */
    public function setLastModifiedBy($val)
    {
        $this->_propDict["lastModifiedBy"] = $val;
         return $this;
    }

    /**
    * Gets the mergeType
    * The merge type of the site. The possible values are: noMerge, default, unknownFutureValue.
    *
    * @return BrowserSiteMergeType|null The mergeType
    */
    public function getMergeType()
    {
        if (array_key_exists("mergeType", $this->_propDict)) {
            if (is_a($this->_propDict["mergeType"], "\Microsoft\Graph\Model\BrowserSiteMergeType") || is_null($this->_propDict["mergeType"])) {
                return $this->_propDict["mergeType"];
            } else {
                $this->_propDict["mergeType"] = new BrowserSiteMergeType($this->_propDict["mergeType"]);
                return $this->_propDict["mergeType"];
            }
        }
        return null;
    }

    /**
    * Sets the mergeType
    * The merge type of the site. The possible values are: noMerge, default, unknownFutureValue.
    *
    * @param BrowserSiteMergeType $val The value to assign to the mergeType
    *
    * @return BrowserSiteHistory The BrowserSiteHistory
    */
    public function setMergeType($val)
    {
        $this->_propDict["mergeType"] = $val;
         return $this;
    }

    /**
    * Gets the publishedDateTime
    * The date and time when the site was last published.
    *
    * @return \DateTime|null The publishedDateTime
    */
    public function getPublishedDateTime()
    {
        if (array_key_exists("publishedDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["publishedDateTime"], "\DateTime") || is_null($this->_propDict["publishedDateTime"])) {
                return $this->_propDict["publishedDateTime"];
            } else {
                $this->_propDict["publishedDateTime"] = new \DateTime($this->_propDict["publishedDateTime"]);
                return $this->_propDict["publishedDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the publishedDateTime
    * The date and time when the site was last published.
    *
    * @param \DateTime $val The value to assign to the publishedDateTime
    *
    * @return BrowserSiteHistory The BrowserSiteHistory
    */
    public function setPublishedDateTime($val)
    {
        $this->_propDict["publishedDateTime"] = $val;
         return $this;
    }

    /**
    * Gets the targetEnvironment
    * The target environment that the site should open in. The possible values are: internetExplorerMode, internetExplorer11, microsoftEdge, configurable, none, unknownFutureValue.Prior to June 15, 2024, the internetExplorer11 option would allow opening a site in the Internet Explorer 11 (IE11) desktop application. Following the retirement of IE11 on June 15, 2024, the internetExplorer11 option will no longer open an IE11 window and will instead behave the same as the internetExplorerMode option.
    *
    * @return BrowserSiteTargetEnvironment|null The targetEnvironment
    */
    public function getTargetEnvironment()
    {
        if (array_key_exists("targetEnvironment", $this->_propDict)) {
            if (is_a($this->_propDict["targetEnvironment"], "\Microsoft\Graph\Model\BrowserSiteTargetEnvironment") || is_null($this->_propDict["targetEnvironment"])) {
                return $this->_propDict["targetEnvironment"];
            } else {
                $this->_propDict["targetEnvironment"] = new BrowserSiteTargetEnvironment($this->_propDict["targetEnvironment"]);
                return $this->_propDict["targetEnvironment"];
            }
        }
        return null;
    }

    /**
    * Sets the targetEnvironment
    * The target environment that the site should open in. The possible values are: internetExplorerMode, internetExplorer11, microsoftEdge, configurable, none, unknownFutureValue.Prior to June 15, 2024, the internetExplorer11 option would allow opening a site in the Internet Explorer 11 (IE11) desktop application. Following the retirement of IE11 on June 15, 2024, the internetExplorer11 option will no longer open an IE11 window and will instead behave the same as the internetExplorerMode option.
    *
    * @param BrowserSiteTargetEnvironment $val The value to assign to the targetEnvironment
    *
    * @return BrowserSiteHistory The BrowserSiteHistory
    */
    public function setTargetEnvironment($val)
    {
        $this->_propDict["targetEnvironment"] = $val;
         return $this;
    }
}
