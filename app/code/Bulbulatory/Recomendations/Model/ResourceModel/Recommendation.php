<?php
namespace Bulbulatory\Recomendations\Model\ResourceModel;

class Recommendation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('recommendations_table', 'recommendation_id');
    }
}