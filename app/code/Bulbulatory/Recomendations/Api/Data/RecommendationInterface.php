<?php
namespace Bulbulatory\Recomendations\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface RecommendationInterface extends ExtensibleDataInterface
{
     /**
     * @return int
     */
    public function getId();
 
    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $id
     * @return void
     */
    public function setCustomerId($id);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return void
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getHash();

    /**
     * @param string $hash
     * @return void
     */
    public function setHash($hash);

    /**
     * @return boolean
     */
    public function getStatus();

    /**
     * @param boolean $status
     * @return void
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getCreationDate();

    /**
     * @param string $creationDate
     * @return string
     */
    public function setCreationDate($creationDate);

    /**
     * @return string
     */
    public function getConfirmationDate();

    /**
     * @param string $confirmationDate
     * @return string
     */
    public function setConfirmationDate($confirmationDate);
}