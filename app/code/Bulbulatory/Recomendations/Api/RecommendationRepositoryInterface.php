<?php
namespace Bulbulatory\Recomendations\Api;

use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface RecommendationRepositoryInterface
{
    /**
     * @param int $id
     * @return \Bulbulatory\Recomendations\Api\Data\RecommendationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param string $hash
     * @return \Bulbulatory\Recomendations\Api\Data\RecommendationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByHash($hash);

    /**
     * @param \Bulbulatory\Recomendations\Api\Data\RecommendationInterface $recommendation
     * @return \Bulbulatory\Recomendations\Api\Data\RecommendationInterface
     */
    public function save(RecommendationInterface $recommendation);

    /**
     * @param \Bulbulatory\Recomendations\Api\Data\RecommendationInterface $recommendation
     * @return void
     */
    public function delete(RecommendationInterface $recommendation);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Bulbulatory\Recomendations\Api\Data\RecommendationSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}