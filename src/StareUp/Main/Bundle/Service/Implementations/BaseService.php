<?php
namespace StareUp\Main\Bundle\Service;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseService
 *
 * @author Rohit Sharma
 */

use StareUp\Main\Bundle\Service\iBase;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("base.service", abstract=true)
 */
abstract class BaseService implements iBase{
}

?>
