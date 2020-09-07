<?php
namespace Bulbulatory\Recomendations\Ui\Component\Listing;

class RecommendationsTableDataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            [
                'customersTable' => $this->getTable('customer_entity')
            ],
            'main_table.customer_id = customersTable.entity_id',
            [
                'email as customer_email'
            ]
        );
        $this->addFilterToMap('customer_email', 'customersTable.email');
        $this->addFilterToMap('email', 'main_table.email');
        $this->addFilterToMap('created_at', 'main_table.created_at');
        return $this;
    }
}
