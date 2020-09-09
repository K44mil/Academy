<?php
namespace Bulbulatory\Recomendations\Model\ResourceModel\Recommendation;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'recommendation_id';
    protected $_eventPrefix = 'bulbulatory_recomendations_recomendation_collection';
    protected $_eventObject = 'recomendation_collection';

    protected function _construct()
    {
        $this->_init(\Bulbulatory\Recomendations\Model\Recommendation::class,
            \Bulbulatory\Recomendations\Model\ResourceModel\Recommendation::class);
    }
}