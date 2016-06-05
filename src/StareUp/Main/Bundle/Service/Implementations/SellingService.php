<?php

namespace StareUp\Main\Bundle\Service;

use StareUp\Main\Bundle\Service\iSelling;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("selling.service", parent="base.manager")
 */
class SellingService extends BaseService implements iSelling{
    public function saveItem($sellingArray) {

    }
}

?>
