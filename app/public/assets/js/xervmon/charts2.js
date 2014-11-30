;columnDrilldown = function (selector, columnType, data)
{
    console.log(data);
	            var brandsData = [];
	            $.each(data.series, function (name, y) {
                brandsData.push({
                    name: name,
                    y: y,
                    drilldown: name //versions[name] ? name : null
                });
            });
 
	$(selector).highcharts({
                chart: {
                    type: columnType
                },
                credits: {
			 		enabled : false
				},
                title: {
                    text: data.titleText
                },
                subtitle: {
                    text: data.subtitleText
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: data.yAxisTitle
                    }
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            format: '{point.y:.1f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
                },

                series: [{
                    name: data.account,
                    colorByPoint: true,
                    data: brandsData
                }],
                drilldown: {
                    series: data.drilldownSeries
                }
            });
};



/*;columnDrilldown = function (selector, columnType, data)
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
};*/


barchart=function(selector, columnType, data){

    $(selector).highcharts({
        chart: {
            type: columnType
        },
        credits:{
            enabled : false
        },
        title: {
            text: data.titleText
        },
        xAxis: {
            type: 'category',
            title: {
            text: data.xAxisTitle
                   }
                },
        yAxis: {
            min: 0,
            title: {
                text: data.yAxisTitle,
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' USD'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -50,
            y: 200,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        series: data.drilldownSeries
    });
}
