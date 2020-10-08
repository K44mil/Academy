<?php
namespace Bulbulatory\Recomendations\Block\Recommendations;

use Magento\Catalog\Block\Product\Context;
use Bulbulatory\Recomendations\Api\Data\RecommendationInterface;
use Magento\Customer\Model\Session;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $recommendation;
    protected $customer;

    public function __construct(
        Context $context,
        RecommendationInterface $recommendation,
        Session $session
    ) {
        parent::__construct($context);
        $this->recommendation = $recommendation;
        $this->customer = $session->getCustomer();   
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getRecommendationCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'custom.recommendation.pager'
            )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(true)
                ->setCollection($this->getRecommendationCollection());
            $this->setChild('pager', $pager);
            $this->getRecommendationCollection()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getFormAction()
    {
        return '/bulbulatory_recomendations/recommendations/saveRecommendation';
    }

    public function getRecommendationCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;

        $collection = $this->recommendation->getCollection();
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        $collection->addFieldToFilter('customer_id', $this->customer->getId());
        return $collection;
    }

    public function getConfirmedRecommendations()
    {
        $collection = $this->getRecommendationCollection();
        $collection->addFieldToFilter('status', 1);
        return $collection;
    }

    public function getDiscountValue()
    {
        $countConfirmedRecommendations = $this->getConfirmedRecommendations()->getSize();
        return 5 * floor($countConfirmedRecommendations/10);
    }
}