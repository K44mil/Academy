<?php
namespace Bulbulatory\Recomendations\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $inlineTranslation;
    protected $escaper;
    protected $transportBuilder;
    protected $logger;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
    }

    public function sendRecommendationEmail($email, $templateVars)
    { 
        $this->inlineTranslation->suspend();
        $sender = [
            'name' => $this->escaper->escapeHtml('Bulbulatory.test'),
            'email' => $this->escaper->escapeHtml('no-reply@bulbulatory.test'),
        ];
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('recommendations_email_email_template')
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(
                // 'recommendationUrl' => $
                $templateVars
            )
            ->setFrom($sender)
            ->addTo($email)
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}