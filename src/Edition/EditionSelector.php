<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\Facts\Edition;

use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContext;
use OxidEsales\EshopCommunity\Internal\Framework\Edition\Edition;

/**
 * Class is responsible for returning edition of OXID eShop.
 */
#[\AllowDynamicProperties]
class EditionSelector
{
    const ENTERPRISE = 'EE';

    const PROFESSIONAL = 'PE';

    const COMMUNITY = 'CE';

    private Edition $edition;

    public function __construct()
    {
        $this->edition = (new BasicContext())->getEdition();
    }

    /**
     * Method returns edition.
     *
     * @return string
     */
    public function getEdition()
    {
        return (string) $this->edition?->value;
    }

    /**
     * @return bool
     */
    public function isEnterprise()
    {
        return $this->edition === Edition::Enterprise;
    }

    /**
     * @return bool
     */
    public function isProfessional()
    {
        return $this->edition === Edition::Professional;
    }

    /**
     * @return bool
     */
    public function isCommunity()
    {
        return $this->edition === Edition::Community;
    }
}
