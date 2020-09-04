<?php
namespace Bulbulatory\Recomendations\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Customer\Api\CustomerRepositoryInterface;

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
        foreach ($dataSource['data']['items'] as &$items) {
            if ($items['customer_id']) {
                $customerId = $items['customer_id'];
                $customer = $this->customerRepositoryInterface->getById($customerId);
                $items['customer_id'] = $customer->getEmail();
            } else {
                $items['customer_id'] = '';
            }
        }
    }
    return $dataSource;
}
}