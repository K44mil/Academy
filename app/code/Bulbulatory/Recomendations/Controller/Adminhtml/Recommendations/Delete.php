<?php
namespace Bulbulatory\Recomendations\Controller\Adminhtml\Recommendations;

use \Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Bulbulatory\Recomendations\Model\ResourceModel\Recommendation\CollectionFactory;

class Delete extends \Magento\Backend\App\Action
{
    public function __construct(
        Context $context,
        collectionFactory $collectionFactory
    ) {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute() 
    {
        $model = $this->_objectManager->create(\Bulbulatory\Recomendations\Model\Recommendation::class);
        try {
            $id = $this->getRequest()->getParam('recommendation_id');
            $model->load($id);
            $model->delete();
        } catch (\Exception $e) {
            $this->messageManager->addError('Cannot delete recommendation.');
        }

        $this->messageManager->addSuccess(__('Recommendation deleted.'));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}