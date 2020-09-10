<?php
namespace Bulbulatory\Recomendations\Controller\Adminhtml\Recommendations;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Bulbulatory\Recomendations\Model\ResourceModel\Recommendation\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $filter;
    protected $collectionFactory;
    private $_recommendationRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        RecommendationRepositoryInterface $recommendationRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->_recommendationRepository = $recommendationRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            try {
                $id = $item['recommendation_id'];
                $recommendation = $this->_recommendationRepository->getById($id);
                $this->_recommendationRepository->delete($recommendation);
            } catch (NoSuchEntityException $e) {
                $id = $item['recommendation_id'];
                $this->messageManager->addErrorMessage(__('Recommendation with ID "%1" does not exist.', $id));
            } catch (Exception $e) {
                $id = $item['recommendation_id'];
                $this->messageManager->addErrorMessage(__('Cannot delete recommendation with ID "%1"', $id));
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 element(s) have been deleted.', $collectionSize));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}