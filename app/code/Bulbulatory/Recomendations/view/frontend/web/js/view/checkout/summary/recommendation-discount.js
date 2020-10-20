define(
    [
       'jquery',
       'Magento_Checkout/js/view/summary/abstract-total',
       'Magento_Checkout/js/model/quote',
       'Magento_Checkout/js/model/totals',
       'Magento_Catalog/js/price-utils'
    ],
    function ($,Component,quote,totals,priceUtils) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Bulbulatory_Recomendations/checkout/summary/recommendation-discount'
            },
            totals: quote.getTotals(),
            isDisplayedRecommendationDiscountTotal : function () {
                var recommendationDiscount = totals.getSegment('recommendation_discount');
                if (recommendationDiscount)
                    return true;
                return false;
            },
            getRecommendationDiscountTotal : function () {
                var price = totals.getSegment('recommendation_discount').value;
                return this.getFormattedPrice(price);
            }
         });
    }
);