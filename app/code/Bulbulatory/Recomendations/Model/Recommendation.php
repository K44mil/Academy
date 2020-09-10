<?php
namespace Bulbulatory\Recomendations\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;

class Recommendation extends AbstractExtensibleModel implements RecommendationInterface
{
    const ID = 'recommendation_id';
    const CUSTOMER_ID = 'customer_id';
    const EMAIL = 'email';
    CONST HASH = 'hash';
    CONST STATUS = 'status';
    CONST CREATION_DATE = 'create_at';
    CONST CONFIRMATION_DATE = 'confirmed_at';

    protected function _construct()
    {
        $this->_init(\Bulbulatory\Recomendations\Model\ResourceModel\Recommendation::class);
    }

    public function getId()
    {
        return $this->_getData(self::ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getCustomerId()
    {
        return $this->_getData(self::CUSTOMER_ID);
    }

    public function setCustomerId($id)
    {
        return $this->setData(self::CUSTOMER_ID, $id);
    }

    public function getEmail()
    {
        return $this->_getData(self::EMAIL);
    }

    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    public function getHash()
    {
        return $this->_getData(self::HASH);
    }

    public function setHash($hash)
    {
        return $this->setData(self::HASH, $hash);
    }

    public function getStatus()
    {
        return $this->_getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getCreationDate()
    {
        return $this->_getData(self::CREATION_DATE);
    }

    public function setCreationDate($creationDate)
    {
        return $this->setData(self::CREATION_DATE, $creationDate);
    }

    public function getConfirmationDate()
    {
        return $this->_getData(self::CONFIRMATION_DATE);
    }

    public function setConfirmationDate($confirmationDate)
    {
        return $this->setData(self::CONFIRMATION_DATE, $confirmationDate);
    }
}