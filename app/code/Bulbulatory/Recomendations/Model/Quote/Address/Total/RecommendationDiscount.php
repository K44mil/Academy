<?php

namespace Bulbulatory\Recomendations\Model\Quote\Address\Total;

class RecommendationDiscount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
    * @var \Magento\Framework\Pricing\PriceCurrencyInterface
    */
    protected $_priceCurrency;

    protected $_config;

    protected $_customer;

    protected $recommendationCollection;

    public $discount;

    /**
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Bulbulatory\Recomendations\Helper\Data $config,
        \Magento\Customer\Model\Session $session,
        \Bulbulatory\Recomendations\Api\Data\RecommendationInterface $recommendation
    ) {
        $this->_priceCurrency = $priceCurrency;
        $this->_config = $config;
        $this->recommendationCollection = $recommendation->getCollection();
        $this->_customer = $session->getCustomer();

        $this->recommendationCollection->addFieldToFilter('customer_id', $this->_customer->getId());
        $this->recommendationCollection->addFieldToFilter('status', 1);
        $countConfirmedRecommendations = $this->recommendationCollection->getSize();
        $this->discount = 5 * floor($countConfirmedRecommendations/10);
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        
        if ($this->_config->isModuleEnabled() && $this->discount > 0) {
            $customDiscount = $total->getSubtotal() * ($this->discount/100);
            $total->addTotalAmount('recommendation_discount', -$customDiscount);
            $total->addBaseTotalAmount('recommendation_discount', -$customDiscount);
            $quote->setCustomDiscount(-$customDiscount);
        }
        
        return $this;
    }

    public function fetch(
        \Magento\Quote\Model\Quote $quote, 
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        if ($this->_config->isModuleEnabled() && $this->discount > 0) {
            return [
                'code' => 'recommendation_discount',
                'title' => $this->getLabel(),
                'value' => '-' . $total->getSubtotal() * ($this->discount/100)
            ];
        }
        return [];  
    }

    public function getLabel()
    {
        return __('Recommendations Discount');
    }
}