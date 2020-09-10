<?php
namespace Bulbulatory\Recomendations\Controller\Adminhtml\Recommendations;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Bulbulatory\Recomendations\Model\ResourceModel\Recommendation\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;
use Bulbulatory\Recomendations\Model\Recommendation;
use Bulbulatory\Recomendations\Model\RecommendationRepository;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $filter;
    protected $collectionFactory;
    private $_recommendationRepositoryInterface;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        RecommendationRepositoryInterface $recommendationRepositoryInterface
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->_recommendationRepositoryInterface = $recommendationRepositoryInterface;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            try {
                $id = $item['recommendation_id'];
                $recommendation = $this->_recommendationRepositoryInterface->getById($id);
                $this->_recommendationRepositoryInterface->delete($recommendation);
            } catch (Exception $e) {
                $id = $item['recommendation_id'];
                $this->messageManager->addErrorMessage(__('Cannot delete recommendation with id: %1', $id));
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 element(s) have been deleted.', $collectionSize));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}