<?php
namespace Naukri\ReferralBundle\Dao\ncPDO;

use JMS\DiExtraBundle\Annotation as DI;
use Naukri\ReferralBundle\Dao\BaseDao;
use Naukri\ReferralBundle\Dao\ncPDO\DBConnectionFactoryNcPDO;
use PDOException;
use Naukri\ReferralBundle\Util\Exceptions\DBConnectionException;
use Naukri\ReferralBundle\Util\Exceptions\DBException;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseDaoNcPDO
 *
 * @author Arpit
 */
/**
 * @DI\Service("base.dao", public=true, abstract=true)
 */
abstract class BaseDaoNcPDO implements BaseDao {


    /**
     * Gets a sharded DB connection based on the client id and the db tag (wrt the dao)
     * @param type $clientId
     * @return PDO connection
     */
        function getConnection() {
            return DBConnectionFactoryNcPDO::getInstance()->getReferralDBConnection();
        }
    }
?>
