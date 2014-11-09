;pieOrDonut = function(data, selector, donut, labelType)
{
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
}