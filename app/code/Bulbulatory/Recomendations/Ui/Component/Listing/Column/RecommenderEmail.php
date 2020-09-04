<?php
namespace Bulbulatory\Recomendations\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Customer\Api\CustomerRepositoryInterface;
use \Magento\Framework\Exception\NoSuchEntityException;

class RecommenderEmail extends Column
{
    protected $customerRepositoryInterface;

    public function __construct(
        CustomerRepositoryInterface $customerRepositoryInterface,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item['customer_id']) {
                    $customerId = $item['customer_id'];
                    try {
                        $customer = $this->customerRepositoryInterface->getById($customerId);
                        $item['customer_email'] = $customer->getEmail();
                    } catch (NoSuchEntityException $e) {
                        $item['customer_email'] = '';
                    }           
                } else {
                    $item['customer_email'] = '';
                }
            }
        }
        return $dataSource;
    }
}