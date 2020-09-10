<?php
namespace Bulbulatory\Recomendations\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface RecommendationSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return Bulbulatory\Recomendations\Api\Data\RecommendationInterface[]
     */
    public function getItems();
 
    /**
     * @param Bulbulatory\Recomendations\Api\Data\RecommendationInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}