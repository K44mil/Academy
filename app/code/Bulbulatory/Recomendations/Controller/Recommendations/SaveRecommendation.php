<?php
namespace Bulbulatory\Recomendations\Controller\Recommendations;

use Magento\Framework\Controller\ResultFactory;
use Bulbulatory\Recomendations\Api\RecommendationRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Math\Random;
use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;
use Bulbulatory\Recomendations\Helper\Email;

class SaveRecommendation extends \Magento\Framework\App\Action\Action
{
    protected $_recommendationRepository;
    protected $_customer;
    protected $_mathRandom;
    protected $_recommendation;
    protected $_email;

    public function __construct(
        Context $context,
        RecommendationRepositoryInterface $recommendationRepository,
        Session $customer,
        Random $mathRandom,
        RecommendationInterface $recommendation,
        Email $email
    ) {
        parent::__construct($context);
        $this->_recommendationRepository = $recommendationRepository;
        $this->_customer = $customer;
        $this->_mathRandom = $mathRandom;
        $this->_recommendation = $recommendation;
        $this->_email = $email;
    }

	public function execute()
	{
        $post = (array)$this->getRequest()->getPost();
        if (!empty($post)) {
            $email = $post['email'];
            $hash = $this->_mathRandom->getUniqueHash();

            try {
                $this->_recommendation->setCustomerId($this->_customer->getId());
                $this->_recommendation->setEmail($email);
                $this->_recommendation->setHash($hash);
                $this->_recommendation->setStatus(false);
                $this->_recommendationRepository->save($this->_recommendation);

                // $receiverInfo = [
                //     'name' => 'John Receiver',
                //     'email' => $email
                // ];

                // $senderInfo = [
                //     'name' => 'John Bulbulator',
                //     'email' => 'test@bulbulatory.test'
                // ];

                // $emailTempVariables = array();
                // $emailTempVariables['myvar1'] = '1';
                // $emailTempVariables['myvar2'] = '1';
                // $emailTempVariables['myvar3'] = '1';
                // $emailTempVariables['myvar4'] = '1';
                // $emailTempVariables['myvar5'] = '1';
                // $emailTempVariables['myvar6'] = '1';

                // $this->_email->sendEmail(
                //     $emailTempVariables,
                //     $senderInfo,
                //     $receiverInfo
                // );

                $this->_email->sendEmail();
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
