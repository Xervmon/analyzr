;columnDrilldown = function (selector, columnType, data)
{
	 $('.chart1').highcharts({
	        chart: {
	            type: columnType
	        },
	        title: {
	            text: data.titleText
	        },
	        xAxis: {
	            type: 'category'
	        },

	        legend: {
	            enabled: false
	        },

	        plotOptions: {
	            series: {
	                borderWidth: 0,
	                dataLabels: {
	                    enabled: true
	                }
	            }
	        },

	        series: [{
	            name: 'Things',
	            colorByPoint: true,
	            data: [{
	                name: 'Amazon Account',
	                y: json.series["Amazon Account-ReadOnly Profile"],
	                drilldown: 'Amazon Account-ReadOnly Profile'
	            }]
	        }],
	        drilldown: {
	            series: [{
	                id: json.drilldownSeries[0].id,
	                data: json.drilldownSeries[0].data
						 
						
	            }]
	        }
	    });
};