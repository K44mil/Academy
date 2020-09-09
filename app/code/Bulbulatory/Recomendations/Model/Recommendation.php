<?php
namespace Bulbulatory\Recomendations\Model;

class Recommendation extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'bulbulatory_recomendations_recommendation';
    protected $_cacheTag = 'bulbulatory_recomendations_recommendation';
    protected $_eventPrefix = 'bulbulatory_recomendations_recommendation';

    protected function _construct()
    {
        $this->_init(\Bulbulatory\Recomendations\Model\ResourceModel\Recommendation::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId() ];
    }

    public function getDefaultValues() 
    {
        return [];
    }
}