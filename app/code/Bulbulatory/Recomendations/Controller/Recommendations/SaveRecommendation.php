<?php
namespace Bulbulatory\Recomendations\Controller\Recommendations;

use Magento\Framework\Controller\ResultFactory;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Math\Random;
use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;
use Bulbulatory\Recomendations\Helper\Email;
use Magento\Framework\UrlInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class SaveRecommendation extends \Magento\Framework\App\Action\Action
{
    const CONFIRMATION_URL = 'bulbulatory_recomendations/recommendations/confirmRecommendation';

    protected $recommendationRepository;
    protected $loggedCustomer;
    protected $mathRandom;
    protected $recommendation;
    protected $email;
    protected $urlBuilder;
    protected $customerRepository;

    public function __construct(
        Context $context,
        RecommendationRepositoryInterface $recommendationRepository,
        Session $loggedCustomer,
        Random $mathRandom,
        RecommendationInterface $recommendation,
        Email $email,
        UrlInterface $urlBuilder,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($context);
        $this->recommendationRepository = $recommendationRepository;
        $this->loggedCustomer = $loggedCustomer;
        $this->mathRandom = $mathRandom;
        $this->recommendation = $recommendation;
        $this->email = $email;
        $this->urlBuilder = $urlBuilder;
        $this->customerRepository = $customerRepository;
    }

	public function execute()
	{
        $post = (array)$this->getRequest()->getPost();
        if (!empty($post)) {
            $email = $post['email'];
            $hash = $this->mathRandom->getUniqueHash();

            try {
                $this->recommendation->setCustomerId($this->loggedCustomer->getId());
                $this->recommendation->setEmail($email);
                $this->recommendation->setHash($hash);
                $this->recommendation->setStatus(false);

                $url = $this->urlBuilder->getUrl(
                    static::CONFIRMATION_URL,
                    [
                        'hash' => $hash
                    ]
                );

                $customer = $this->customerRepository->getById($this->loggedCustomer->getId());

                $templateVars = [
                    'recommendationUrl' => $url,
                    'customerFirstName' => $customer->getFirstname(),
                    'customerLastName' => $customer->getLastname()
                ];

                $this->email->sendRecommendationEmail($email, $templateVars);            

                $this->recommendationRepository->save($this->recommendation);
                $this->messageManager->addSuccessMessage(__('Recommendation sent.'));
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__('Cannot send recommendation.'));
            }

            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl('/bulbulatory_recomendations/recommendations/index');

            return $resultRedirect;
        }
	}
}
