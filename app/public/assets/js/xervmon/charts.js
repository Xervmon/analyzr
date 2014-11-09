;pieOrDonut = function(url, selector, donut, labelType)
{
	$.ajax({
		url:  url,
		cache: false
	}).done(function( response ) {
		console.log(response);
		if (!$.isArray(response)) {
        	response = JSON.parse(response);
        }
		
		nv.addGraph(function() 
		{
	  		var chart = nv.models.pieChart()
	        .x(function(d) { return d.label })
	        .y(function(d) { return d.value })
	        .showLabels(true)     //Display pie labels
	        .labelThreshold(.05)  //Configure the minimum slice size for labels to show up
	        .labelType(labelType) //Configure what type of data to show in the label. Can be "key", "value" or "percent"
	        .donut(donut)          //Turn on Donut mode. Makes pie chart look tasty!
	        .donutRatio(0.35);     //Configure how big you want the donut hole size to be.

	    	d3.select(selector)
	       	.datum(response)
	        .transition().duration(350)
	        .call(chart);
		  	return chart;
		});
	});
}