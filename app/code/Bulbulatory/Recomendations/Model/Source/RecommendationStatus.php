<?php
namespace Bulbulatory\Recomendations\Model\Source;

class RecommendationStatus implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Nie potwierdzony')],
            ['value' => 1, 'label' => __('Potwierdzony')]
        ];
    }
}