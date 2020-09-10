<?php
namespace Bulbulatory\Recomendations\Controller\Adminhtml\Recommendations;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;

class Delete extends \Magento\Backend\App\Action
{
    private $_recommendationRepositoryInterface;

    public function __construct(
        Context $context,
        RecommendationRepositoryInterface $recommendationRepositoryInterface
    ) {
        parent::__construct($context);
        $this->_recommendationRepositoryInterface = $recommendationRepositoryInterface;
    }

    public function execute() 
    {
        try {
            $id = $this->getRequest()->getParam('recommendation_id');
            $recommendation = $this->_recommendationRepositoryInterface->getById($id);
            $this->_recommendationRepositoryInterface->delete($recommendation);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Cannot delete recommendation.');
        }

        $this->messageManager->addSuccessMessage(__('Recommendation deleted.'));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}