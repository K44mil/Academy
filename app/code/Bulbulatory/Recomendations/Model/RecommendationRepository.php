<?php
namespace Bulbulatory\Recomendations\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Bulbulatory\Recomendations\Model\ResourceModel\Recommendation\CollectionFactory;
use Bulbulatory\Recomendations\Model\ResourceModel\Recommendation\Collection;
use Bulbulatory\Recomendations\Model\RecommendationFactory;
use Bulbulatory\Recomendations\Api\Data\RecommendationSearchResultInterfaceFactory;
use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;
use Magento\Framework\Math\Random;

class RecommendationRepository implements RecommendationRepositoryInterface
{
    /**
     * @var RecommendationFactory
     */
    private $recommendationFactory;

    /**
     * @var CollectionFactory
     */
    private $recommendationCollectionFactory;

    /**
     * @var RecommendationSearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @var Magento\Framework\Math\Random
     */
    private $mathRandom;

    public function __construct(
        RecommendationFactory $recommendationFactory,
        CollectionFactory $recommendationCollectionFactory,
        RecommendationSearchResultInterfaceFactory $searchResultFactory,
        Random $mathRandom
    ) {
        $this->recommendationFactory = $recommendationFactory;
        $this->recommendationCollectionFactory = $recommendationCollectionFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->mathRandom = $mathRandom;
    }

    public function getById($id)
    {
        $recommendation = $this->recommendationFactory->create();
        $recommendation->getResource()->load($recommendation, $id);
        if (!$recommendation->getId()) {
            throw new NoSuchEntityException(__('Unable to find recommendation with ID "%1"', $id));
        }
        return $recommendation;
    }

    public function getByHash($hash)
    {
        $recommendation = $this->recommendationFactory->create();
        $recommendation->getResource()->load($recommendation, $hash, 'hash');

        if (!$recommendation->getId()) {
            throw new NoSuchEntityException(__('Unable to find recommendation with hash "%1"', $hash));
        }

        return $recommendation;
    }

    public function confirmRecommendation(RecommendationInterface $recommendation)
    {
        $recommendation->setStatus(true);
        $recommendation->setConfirmationDate(date("Y-m-d H:i:s"));
        $recommendation->getResource()->save($recommendation);
        return $recommendation;
    }

    public function createRecommendation($customerId, $email)
    {
        $recommendation = $this->recommendationFactory->create();
        $recommendation->setCustomerId($customerId);
        $recommendation->setEmail($email);
        $recommendation->setHash($this->mathRandom->getUniqueHash());
        
        $recommendation->getResource()->save($recommendation);
        return $recommendation;
    }

    public function save(RecommendationInterface $recommendation)
    {
        $recommendation->getResource()->save($recommendation);
        return $recommendation;
    }

    public function delete(RecommendationInterface $recommendation)
    {
        $recommendation->getResource()->delete($recommendation);
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->recommendationCollectionFactory->create();
 
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
 
        $collection->load();
 
        return $this->buildSearchResult($searchCriteria, $collection);
    }

    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultFactory->create();
 
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
 
        return $searchResults;
    }
}