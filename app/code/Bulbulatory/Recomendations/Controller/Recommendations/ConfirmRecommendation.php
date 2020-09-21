<?php
namespace Bulbulatory\Recomendations\Controller\Recommendations;

use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session;
use Magento\Backend\App\Action\Context;

class ConfirmRecommendation extends \Magento\Framework\App\Action\Action
{
    protected $recommendation;
    protected $recommendationRepository;

    public function __construct(
        Context $context,
        RecommendationRepositoryInterface $recommendationRepository,
        RecommendationInterface $recommendation
    ) {
        parent::__construct($context);
        $this->recommendationRepository = $recommendationRepository;
        $this->recommendation = $recommendation;
    }

    public function execute()
    {
        try {
            $hash = $this->getRequest()->getParam('hash');
            $recommendation = $this->recommendationRepository->getByHash($hash);
            $recommendation->setStatus(true);
            $recommendation->setConfirmationDate(date("Y-m-d H:i:s"));
            $this->recommendationRepository->save($recommendation);

            $this->messageManager->addSuccessMessage(__('Recommendation confirmed.'));
        } catch (NoSuchEntityException $e) {
            $hash = $this->getRequest()->getParam('hash');
            $this->messageManager->addErrorMessage(__('Recommendation with hash "%1" does not exist.', $hash));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Cannot confirm recommendation.');
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl('/');

        return $resultRedirect;
    }

}