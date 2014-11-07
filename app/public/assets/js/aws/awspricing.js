;;
!(function ($) {
    "use strict";

    function flattenData(data) {
        console.log('flattenData', data);
        var flatData = [];
        for (var i = 0, l = data.regions.length; i < l; i++) {
            var region = data.regions[i];
            // console.log("Region", region.region);
            for (var j = 0, k = region.instanceTypes.length; j < k; j++) {
                var instance = region.instanceTypes[j],
                    thisData = {
                        "Region": region.region,
                        "OS": instance.os,
                        "Type": instance.type
                    };
                if ($.isPlainObject(instance.prices)) {
                    // Reserved
                    thisData["Utilization"] = instance.utilization;
                    thisData['1 Year Hourly (' + data.config.currency + ')'] = instance.prices['1year'].hourly;
                    thisData['1 Year Upfront (' + data.config.currency + ')'] = instance.prices['1year'].upfront;
                    thisData['3 Years Hourly (' + data.config.currency + ')'] = instance.prices['3year'].hourly;
                    thisData['3 Years Upfront (' + data.config.currency + ')'] = instance.prices['3year'].upfront;
                } else {
                    // On Demand
                    thisData['Price (' + data.config.currency + ')'] = instance.price;
                }
                // console.log("instanceData", thisData);
                flatData.push(thisData);
            }
        }
        console.log("flattened data", flatData);
        return flatData;
    }
    $(function () {
        setTimeout(function () {
            setupTableSorterChecked($("#AWSPricing_reserved_holder")
                .html(buildTableFromArray(flattenData(window.reserved_instance_prices), false, false, false, {
                    'Region': 'filter-select filter-match',
                    'OS': 'filter-select filter-match',
                    'Utilization': 'filter-select filter-match',
                    'Type': 'filter-select filter-match'
                })), false);
        }, 50);
        setTimeout(function () {
            setupTableSorterChecked($("#AWSPricing_ondemand_holder")
                .html(buildTableFromArray(flattenData(window.ondemand_instance_prices), false, false, false, {
                    'Region': 'filter-select filter-match',
                    'OS': 'filter-select filter-match',
                    'Type': 'filter-select filter-match'
                })), false);
        }, 50);
    });
})(jQuery);