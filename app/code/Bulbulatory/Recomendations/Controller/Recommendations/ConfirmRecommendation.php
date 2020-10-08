<?php
namespace Bulbulatory\Recomendations\Controller\Recommendations;

use Magento\Backend\App\Action\Context;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;

class ConfirmRecommendation extends \Magento\Framework\App\Action\Action
{
    protected $recommendationRepository;
    protected $logger;

    public function __construct(
        Context $context,
        RecommendationRepositoryInterface $recommendationRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->recommendationRepository = $recommendationRepository;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            $hash = $this->getRequest()->getParam('hash');
            $recommendation = $this->recommendationRepository->getByHash($hash);
            $this->recommendationRepository->confirmRecommendation($recommendation);

            $this->messageManager->addSuccessMessage(__('Recommendation confirmed.'));
        } catch (NoSuchEntityException $e) {
            $hash = $this->getRequest()->getParam('hash');
            $this->messageManager->addErrorMessage(__('Recommendation with hash "%1" does not exist.', $hash));
        } catch (\Exception $e) {
            $this->logger->error("Error message: ", ['exception' => $e]);
            $this->messageManager->addErrorMessage('Cannot confirm recommendation.');
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl('/');

        return $resultRedirect;
    }

}