<?php

namespace StareUp\Main\Bundle\DBLayer\Implementations;

use JMS\DiExtraBundle\Annotation as DI;
use PDO;
use StareUp\Main\Bundle\DBLayer\Implementations\BaseDao;
use StareUp\Main\Bundle\DBLayer\Interfaces\iSellingDao;

/**
 * @DI\Service("dao.selling", parent="base.dao")
 */
class SellingDao extends BaseDao implements iSellingDao {

    public function saveItem($sellingArray) {
        try {

            $db = $this->getConnection();
            $selectQuery = "insert into items (title, description, userId, type, location, lattitude, longitude, quantity, duration, price, currency, "
                    . "negotiable, images, userInfo, postedOn, category) values(:title, :description, :userId, :type, :location, :lattitude, "
                    . ":longitude, :quantity, :duration, :price, :currency, :negotiable, :images, :userInfo, now(), :category);";

            $prepQuery = $db->prepare($selectQuery);

            $prepQuery->bindValue('title', $sellingArray['title'], PDO::PARAM_STR);
            $prepQuery->bindValue('description', $sellingArray['description'], PDO::PARAM_STR);
            $prepQuery->bindValue('userId', $sellingArray['userId'], PDO::PARAM_INT);
            $prepQuery->bindValue('type', $sellingArray['type'], PDO::PARAM_INT);
            $prepQuery->bindValue('location', $sellingArray['location'], PDO::PARAM_STR);
            $prepQuery->bindValue('lattitude', $sellingArray['lattitude'], PDO::PARAM_STR);
            $prepQuery->bindValue('longitude', $sellingArray['longitude'], PDO::PARAM_STR);
            $prepQuery->bindValue('quantity', $sellingArray['quantity'], PDO::PARAM_INT);
            $prepQuery->bindValue('duration', $sellingArray['duration'], PDO::PARAM_STR);
            $prepQuery->bindValue('price', $sellingArray['price'], PDO::PARAM_STR);
            $prepQuery->bindValue('currency', $sellingArray['currency'], PDO::PARAM_STR);
            $prepQuery->bindValue('negotiable', $sellingArray['negotiable'], PDO::PARAM_INT);
            $prepQuery->bindValue('images', $sellingArray['images'], PDO::PARAM_STR);
            $prepQuery->bindValue('userInfo', $sellingArray['userInfo'], PDO::PARAM_STR);
            $prepQuery->bindValue('category', $sellingArray['category'], PDO::PARAM_INT);

            $prepQuery->execute();
            $a = $prepQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            //TO DO STUB ::
            //Write a logger and mailer here to report errors.
        }

        return null;
    }

    public function getItem($id) {

        try {

            $sql = "
                         SELECT title
                           FROM items where id = {$id}
                     ";
		 
                 //$em = $this->getDoctrine()->getManager();
                 $stmt = $this->getConnection()->prepare($sql);
                 $stmt->execute();
                 $items = $stmt->fetchAll();
		 return $items;
        } catch (Exception $ex) {
            
        }
        return null;
    }

}
