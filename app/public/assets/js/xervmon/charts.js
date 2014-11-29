;pieOrDonut = function(data, selector, donut, labelType)
{
	//console.log(data);
	nv.addGraph(function() 
		{
	  		var chart = nv.models.pieChart()
	        .x(function(d) { return d.label })
	        .y(function(d) { return d.value })
	        .showLabels(true)     //Display pie labels
	        .labelThreshold(.05)  //Configure the minimum slice size for labels to show up
	        .labelType(labelType) //Configure what type of data to show in the label. Can be "key", "value" or "percent"
	        .donut(donut)          //Turn on Donut mode. Makes pie chart look tasty!
	        .donutRatio(0.5);     //Configure how big you want the donut hole size to be.
	        var left_margin=650;
	    	d3.select(selector)
	       	.datum(data)
	        .transition().duration(350)
	        .call(chart);
			d3.select(".nv-legendWrap")
			.attr('transform', 'translate(0,-30)');


			d3.select(".nv-series")
			.attr('transform', 'translate('+left_margin+',0)');
			d3.select(".nv-series:nth-child(2)")
			.attr('transform', 'translate('+left_margin+',40)');
			d3.select(".nv-series:nth-child(3)")
			.attr('transform', 'translate('+left_margin+',80)');
			d3.select(".nv-series:nth-child(4)")
			.attr('transform', 'translate('+left_margin+',120)');
			d3.select(".nv-series:nth-child(5)")
			.attr('transform', 'translate('+left_margin+',160)');
			d3.select(".nv-series:nth-child(6)")
			.attr('transform', 'translate('+left_margin+',200)');
			d3.select(".nv-series:nth-child(7)")
			.attr('transform', 'translate('+left_margin+',240)');
			d3.select(".nv-series:nth-child(8)")
			.attr('transform', 'translate('+left_margin+',280)');
			d3.select(".nv-series:nth-child(9)")
			.attr('transform', 'translate('+left_margin+',320)');
			d3.select(".nv-series:nth-child(10)")
			.attr('transform', 'translate('+left_margin+',360)');
			d3.select(".nv-series:nth-child(11)")
			.attr('transform', 'translate('+left_margin+',400)');


			d3.select(".nv-pieWrap")
			.attr('transform', 'translate(-250,-75)');

		  	return chart;

		  
		});
};

discreteBarchart = function(data, selector, donut, labelType)
{
	//console.log(data);
	nv.addGraph(function() 
	{
		var chart = nv.models.discreteBarChart()
      .x(function(d) { return d.label })    //Specify the data accessors.
      .y(function(d) { return d.value })
      .staggerLabels(true)    //Too many bars and not enough room? Try staggering labels.
      .tooltips(false)        //Don't show tooltips
      .showValues(true)       //...instead, show the bar value right on top of each bar.
      .transitionDuration(350);
      
      d3.select(selector)
	       	.datum(data)
	        .transition().duration(350)
	        .call(chart);
			d3.select(".nv-legendWrap")
			.attr('transform', 'translate(-140,-30)');
			d3.select(".nv-pieWrap")
			.attr('transform', 'translate(235,-20)');
		  	return chart;
		});
};

stackedAreaData = function(data, selector, donut, labelType)
{
	d3.json('stackedAreaData.json', function(data) {
  nv.addGraph(function() {
    var chart = nv.models.stackedAreaChart()
                  .margin({right: 100})
                  .x(function(d) { return d[0] })   //We can modify the data accessor functions...
                  .y(function(d) { return d[1] })   //...in case your data is formatted differently.
                  .useInteractiveGuideline(true)    //Tooltips which show all data points. Very nice!
                  .rightAlignYAxis(true)      //Let's move the y-axis to the right side.
                  .transitionDuration(500)
                  .showControls(true)       //Allow user to choose 'Stacked', 'Stream', 'Expanded' mode.
                  .clipEdge(true);

    //Format x-axis labels with custom function.
    chart.xAxis
        .tickFormat(function(d) { 
          return d3.time.format('%x')(new Date(d)) 
    });

    chart.yAxis
        .tickFormat(d3.format(',.2f'));

    d3.select('#chart svg')
      .datum(data)
      .call(chart);

    nv.utils.windowResize(chart.update);

    return chart;
    
 	 });
	})
};

multibar = function()
{
	nv.addGraph(function() {
    var chart = nv.models.multiBarChart()
      .transitionDuration(350)
      .reduceXTicks(true)   //If 'false', every single x-axis tick label will be rendered.
      .rotateLabels(0)      //Angle to rotate x-axis labels.
      .showControls(true)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
      .groupSpacing(0.1)    //Distance between each group of bars.
    ;

    chart.xAxis
        .tickFormat(d3.format(',f'));

    chart.yAxis
        .tickFormat(d3.format(',.1f'));

    d3.select('.chart1 svg')
        .datum(exampleData())
        .call(chart);

    nv.utils.windowResize(chart.update);

    return chart;
});

	//Generate some nice data.
	function exampleData() {
	  return stream_layers(3,10+Math.random()*100,.1).map(function(data, i) {
	    return {
	      key: 'Stream #' + i,
	      values: data
	    };
	  });
	}

}
