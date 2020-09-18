<?php
namespace Bulbulatory\Recomendations\Block\Recommendations;

class Index extends \Magento\Framework\View\Element\Template
{
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getFormAction()
    {
        return '/bulbulatory_recomendations/recommendations/saveRecommendation';
    }
}