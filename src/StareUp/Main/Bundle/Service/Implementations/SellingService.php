<?php

namespace StareUp\Main\Bundle\Service\Implementations;

use StareUp\Main\Bundle\Service\Interfaces\iSelling;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("selling.service", parent="base.service")
 */
class SellingService extends BaseService implements iSelling 
{

    /** @DI\Inject ("dao.selling") */
    public $sellingDao;
    
    /** @DI\Inject ("common.validator") */
    public $commonValidator;

    public function saveItem($sellingArray) {
        $isValid = $this->commonValidator->validateItem($sellingArray);
        if($isValid !== true) {
            return $isValid;
        }
        return $this->sellingDao->saveItem($sellingArray);        
    }

    public function getItem($id) {
        $this->sellingDao->getItem($id);
    }
}

?>
