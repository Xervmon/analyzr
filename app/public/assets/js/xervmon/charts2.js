;columnDrilldown = function (selector, columnType, data)
{
	//hs.showCredits = false;
	console.log(data.series);
	console.log(data.drilldownSeries);
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
                            enabled: true,
                            format: '{point.y:.1f}%'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
                },

                series: [{
                    name: 'Accounts',
                    colorByPoint: true,
                    data: data.series
                }],
                drilldown: {
                    series: data.drilldownSeries
                }
            });
};