<?php
namespace Bulbulatory\Recomendations\Controller\Recommendations;

use Magento\Framework\Controller\ResultFactory;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Backend\App\Action\Context;
use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;
use Bulbulatory\Recomendations\Helper\Email;
use Magento\Framework\UrlInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class SaveRecommendation extends \Magento\Framework\App\Action\Action
{
    const CONFIRMATION_URL = 'bulbulatory_recomendations/recommendations/confirmRecommendation';

    protected $recommendationRepository;
    protected $loggedCustomer;
    protected $email;
    protected $urlBuilder;
    protected $customerRepository;
    protected $logger;

    public function __construct(
        Context $context,
        RecommendationRepositoryInterface $recommendationRepository,
        Session $loggedCustomer,
        Email $email,
        UrlInterface $urlBuilder,
        CustomerRepositoryInterface $customerRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->recommendationRepository = $recommendationRepository;
        $this->loggedCustomer = $loggedCustomer;
        $this->email = $email;
        $this->urlBuilder = $urlBuilder;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

	public function execute()
	{
        $post = (array)$this->getRequest()->getPost();
        if (!empty($post) && $this->loggedCustomer->getId()) {
            $email = $post['email'];
            
            try {
                $customer = $this->customerRepository->getById($this->loggedCustomer->getId());

                if ($customer->getEmail() !== $email) {
                    $recommendation = $this->recommendationRepository
                    ->createRecommendation($this->loggedCustomer->getId(), $email);

                    $url = $this->urlBuilder->getUrl(
                        static::CONFIRMATION_URL,
                        [
                            'hash' => $recommendation->getHash()
                        ]
                    ); 

                    $templateVars = [
                        'recommendationUrl' => $url,
                        'customerFirstName' => $customer->getFirstname(),
                        'customerLastName' => $customer->getLastname()
                    ];

                    $this->email->sendRecommendationEmail($email, $templateVars);            
                    
                    $this->messageManager->addSuccessMessage(__('Recommendation sent.'));
                } else {
                    $this->messageManager->addErrorMessage(__('You cannot send a recommendation to your own email.'));
                }
                
            } catch (Exception $e) {
                $this->logger->error("Error message: ", ['exception' => $e]);
                $this->messageManager->addErrorMessage(__('Cannot send recommendation.'));
            }

            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl('/bulbulatory_recomendations/recommendations/index');

            return $resultRedirect;
        }
	}
}
