<?php

namespace StareUp\Main\Bundle\DBLayer\Implementations;

use JMS\DiExtraBundle\Annotation as DI;
use PDO;
use StareUp\Main\Bundle\DBLayer\Implementations\BaseDao;
use StareUp\Main\Bundle\DBLayer\Interfaces\iSellingDao;

/**
 * @DI\Service("dao.selling", parent="base.dao")
 */
class SellingDao extends BaseDao implements iSellingDao
{

    public function saveItem($sellingArray) {

         try{
              $db = $this->getConnection();

            $selectQuery = "insert into items () values();";//levelId , points ,levelName  from levelSettings where  companyId = :companyId and deleted = 'N' and disabled = 'N'" ;

            $prepQuery = $db->prepare($selectQuery);

//            $prepQuery->bindValue(':companyId',$companyId,PDO::PARAM_INT);
            $prepQuery->execute();
           $a = $prepQuery->fetchAll(PDO::FETCH_ASSOC);
var_dump($a);die;

        } catch (Exception $ex) {

        }
        return null;
    }

    public function getItem($id) {

         try{

                 $sql = "
                         SELECT title
                           FROM items where id = {$id}
                     ";

                 $em = $this->getDoctrine()->getManager();
                 $stmt = $em->getConnection()->prepare($sql);
                 $stmt->execute();
                 $item = $stmt->fetchAll();
                 var_dump($item);die;

            /*$db = $this->getConnection();


            $selectQuery = "select levelId , points ,levelName  from levelSettings where  companyId = :companyId and deleted = 'N' and disabled = 'N'" ;

            $prepQuery = $db->prepare($selectQuery);

            $prepQuery->bindValue(':companyId',$companyId,PDO::PARAM_INT);
            $prepQuery->execute();
           return $prepQuery->fetchAll(PDO::FETCH_ASSOC);*/

        } catch (Exception $ex) {

        }
        return null;
      }
}
