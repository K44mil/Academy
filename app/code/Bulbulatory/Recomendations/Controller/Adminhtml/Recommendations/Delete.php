<?php
namespace Bulbulatory\Recomendations\Controller\Adminhtml\Recommendations;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends \Magento\Backend\App\Action
{
    private $_recommendationRepository;

    public function __construct(
        Context $context,
        RecommendationRepositoryInterface $recommendationRepository
    ) {
        parent::__construct($context);
        $this->_recommendationRepository = $recommendationRepository;
    }

    public function execute() 
    {
        try {
            $id = $this->getRequest()->getParam('recommendation_id');
            $recommendation = $this->_recommendationRepository->getById($id);
            $this->_recommendationRepository->delete($recommendation);
        } catch (NoSuchEntityException $e) {
            $id = $this->getRequest()->getParam('recommendation_id');
            $this->messageManager->addErrorMessage(__('Recommendation with ID "%1" does not exist.', $id));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Cannot delete recommendation.');
        }

        $this->messageManager->addSuccessMessage(__('Recommendation deleted.'));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}