<?php
namespace StareUp\Main\Bundle\DBLayer\Implementations;

use JMS\DiExtraBundle\Annotation as DI;
use StareUp\Main\Bundle\DBLayer\Interfaces\iBaseDao;
use PDOException;
use PDO;

/**
 * Description of BaseDao
 *
 * @author Rohit Sharma
 */

/**
 * @DI\Service("base.dao", public=true, abstract=true)
 */
abstract class BaseDao implements iBaseDao {
    /*
     * Gets a sharded DB connection based on the client id and the db tag (wrt the dao)
     * @param type $clientId
     * @return PDO connection
     */
        function getConnection() {
	       $dsn = 'mysql:host=localhost;dbname=stareup';
		$username = 'stareup';
		$password = 'stareup789*';
		$options = array(
		    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		);

		$db = new PDO($dsn, $username, $password, $options);
	        return $db;
        }
    }
?>
