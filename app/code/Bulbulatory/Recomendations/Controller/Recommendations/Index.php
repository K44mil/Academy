<?php
namespace Bulbulatory\Recomendations\Controller\Recommendations;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $loggedCustomer;
	protected $moduleManager;
	protected $dataHelper;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Framework\Module\Manager $moduleManager,
		\Magento\Customer\Model\Session $loggedCustomer,
		\Bulbulatory\Recomendations\Helper\Data $dataHelper
	) {
		$this->_pageFactory = $pageFactory;
		$this->loggedCustomer = $loggedCustomer;
		$this->moduleManager = $moduleManager;
		$this->dataHelper = $dataHelper;
		parent::__construct($context);
	}

	public function execute()
	{
		if ($this->dataHelper->isModuleEnabled() && $this->loggedCustomer->getId()) {
			return $this->_pageFactory->create();
		}

		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		$resultRedirect->setUrl('/');
		return $resultRedirect;
	}
}
