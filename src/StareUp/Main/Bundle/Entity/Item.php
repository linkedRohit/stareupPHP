<?php

namespace StareUp\Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="items")
 * @ORM\Entity(repositoryClass="StareUp\Main\Bundle\Repository\ItemRepository")
 */
class Item
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="userId", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="category", type="integer")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="lattitude", type="string", length=30)
     */
    private $lattitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=30)
     */
    private $longitude;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=3)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=10)
     */
    private $currency;

    /**
     * @var bool
     *
     * @ORM\Column(name="negotiable", type="boolean")
     */
    private $negotiable;

    /**
     * @var array
     *
     * @ORM\Column(name="images", type="json_array", nullable=true)
     */
    private $images;

    /**
     * @var array
     *
     * @ORM\Column(name="userInfo", type="json_array", nullable=true)
     */
    private $userInfo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="postedOn", type="datetime", nullable=true)
     */
    private $postedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedOn", type="datetime", nullable=true, columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $updatedOn;

    /**
     * @var array
     *
     * @ORM\Column(name="Json_field1", type="json_array", nullable=true)
     */
    private $jsonField1;

    /**
     * @var array
     *
     * @ORM\Column(name="Json_field2", type="json_array", nullable=true)
     */
    private $jsonField2;

    /**
     * @var string
     *
     * @ORM\Column(name="field1", type="string", length=255, nullable=true)
     */
    private $field1;

    /**
     * @var string
     *
     * @ORM\Column(name="field2", type="string", length=255, nullable=true)
     */
    private $field2;

    /**
     * @var string
     *
     * @ORM\Column(name="field3", type="string", length=255, nullable=true)
     */
    private $field3;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Item
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Item
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Item
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Item
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set lattitude
     *
     * @param string $lattitude
     *
     * @return Item
     */
    public function setLattitude($lattitude)
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    /**
     * Get lattitude
     *
     * @return string
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Item
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Item
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Item
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Item
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set negotiable
     *
     * @param boolean $negotiable
     *
     * @return Item
     */
    public function setNegotiable($negotiable)
    {
        $this->negotiable = $negotiable;

        return $this;
    }

    /**
     * Get negotiable
     *
     * @return bool
     */
    public function getNegotiable()
    {
        return $this->negotiable;
    }

    /**
     * Set images
     *
     * @param array $images
     *
     * @return Item
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set userInfo
     *
     * @param array $userInfo
     *
     * @return Item
     */
    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;

        return $this;
    }

    /**
     * Get userInfo
     *
     * @return array
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * Set postedOn
     *
     * @param \DateTime $postedOn
     *
     * @return Item
     */
    public function setPostedOn($postedOn)
    {
        $this->postedOn = $postedOn;

        return $this;
    }

    /**
     * Get postedOn
     *
     * @return \DateTime
     */
    public function getPostedOn()
    {
        return $this->postedOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return Item
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set jsonField1
     *
     * @param array $jsonField1
     *
     * @return Item
     */
    public function setJsonField1($jsonField1)
    {
        $this->jsonField1 = $jsonField1;

        return $this;
    }

    /**
     * Get jsonField1
     *
     * @return array
     */
    public function getJsonField1()
    {
        return $this->jsonField1;
    }

    /**
     * Set jsonField2
     *
     * @param array $jsonField2
     *
     * @return Item
     */
    public function setJsonField2($jsonField2)
    {
        $this->jsonField2 = $jsonField2;

        return $this;
    }

    /**
     * Get jsonField2
     *
     * @return array
     */
    public function getJsonField2()
    {
        return $this->jsonField2;
    }

    /**
     * Set field1
     *
     * @param string $field1
     *
     * @return Item
     */
    public function setField1($field1)
    {
        $this->field1 = $field1;

        return $this;
    }

    /**
     * Get field1
     *
     * @return string
     */
    public function getField1()
    {
        return $this->field1;
    }

    /**
     * Set field2
     *
     * @param string $field2
     *
     * @return Item
     */
    public function setField2($field2)
    {
        $this->field2 = $field2;

        return $this;
    }

    /**
     * Get field2
     *
     * @return string
     */
    public function getField2()
    {
        return $this->field2;
    }

    /**
     * Set field3
     *
     * @param string $field3
     *
     * @return Item
     */
    public function setField3($field3)
    {
        $this->field3 = $field3;

        return $this;
    }

    /**
     * Get field3
     *
     * @return string
     */
    public function getField3()
    {
        return $this->field3;
    }

    /**
     * Set category
     *
     * @param integer $category
     *
     * @return Item
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }
}
