<?php

/**
 * This file is part of OXID eSales OXID eShop Facts.
 *
 * OXID eSales OXID eShop Facts is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales OXID eShop Facts is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales OXID eShop Facts. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 */

namespace OxidEsales\Facts\Edition;

use OxidEsales\EshopCommunity\Core\Di\ContainerFacade;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface;
use OxidEsales\Facts\Config\ConfigFile;
use OxidEsales\Facts\Facts;

use OxidEsales\EshopCommunity\Internal\Framework\Edition\Edition;

/**
 * Class is responsible for returning edition of OXID eShop.
 */
#[\AllowDynamicProperties]
class EditionSelector
{
    private Edition $edition;

    public function __construct()
    {
        $this->edition = ContainerFacade::get(BasicContextInterface::class)->getEdition();
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
