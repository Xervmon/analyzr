;

window.ucfirst = function (str) {
    str += '';
    var f = str.charAt(0).toUpperCase();
    return f + str.substr(1);
};
function setupTableSorterChecked(selector, displayTotalCount, pageSize, customFooterMarkup, hidePaginationToggleButton, themeOptions, sorterOptions, pagerOptions) {
    pageSize = 10;
    var $this = $($(selector || this)
        .get(0));
    setTimeout(function () {
        // Wait until it's inserted into the DOM
        if (!$this.is("table")) {
            $this = $($this.find("table")
                .get(0));
        }
        if (!$this.is("table")) {
            $this = $this.closest("table");
        }
        if (!$this.is("table")) {
            return $this;
        }
        $this = $($this);
        var id = $this.attr("id") || "setupTableSorterChecked_table_" + (Math.round(Math.random() * 100000));
        $this.attr("id", id);
        $this.wrap('<div />');
        var $parent = $this.parent()
            .addClass("dataTables_wrapper")
            .attr("id", id + '_wrapper');
        themeOptions = $.isPlainObject(themeOptions) ? themeOptions : {};
        $.extend($.tablesorter.themes.bootstrap, {
            // these classes are added to the table. To see other table classes available,
            // look here: http://twitter.github.com/bootstrap/base-css.html#tables
            table: 'table table-bordered',
            header: 'bootstrap-header', // give the header a gradient background
            footerRow: '',
            footerCells: '',
            icons: '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
            sortNone: 'bootstrap-icon-unsorted',
            sortAsc: 'icon-chevron-up',
            sortDesc: 'icon-chevron-down',
            active: '', // applied when column is sorted
            hover: '', // use custom css here - bootstrap class may not override it
            filterRow: '', // filter row class
            even: '', // odd row zebra striping
            odd: '' // even row zebra striping
        }, themeOptions);
        // Insert pagination controls
        var $pager = $this.find(".tfoot-pager");
        if ($pager.length === 0) {
            var $tfoot = $this.find("tfoot");
            if ($tfoot.length === 0) {
                $tfoot = $("<tfoot>")
                    .appendTo($this);
            }
            if (customFooterMarkup) {
                $('<tr><th class="tfoot-pager text-center" colspan="' + ($this.find("tr")
                    .first()
                    .children()
                    .length + 5) + '">' + customFooterMarkup + '</th></tr>')
                    .appendTo($tfoot);
            }
            // Insert pagination
            $tfoot = $('<tr><th class="tfoot-pager text-center" colspan="' + ($this.find("tr")
                .first()
                .children()
                .length + 5) + '"></th></tr>')
                .appendTo($tfoot);
            var $pager = $tfoot.find("th.tfoot-pager");
            var pager = '';
            /*if (!hidePaginationToggleButton) {
             pager += '<div class="clearfix showmoreBtn-wrapper"><div class="showmoreBtn"></div></div>';
             }*/
            pager += '<input type="hidden" class="pagesize" value="' + (parseInt(pageSize, 10) || 10) + '" />';
            pager += '<div class="nav nav-tabs" style="border:none;">';
            if (displayTotalCount == true) {
                pager += ('<div class="totalAmnt pull-left totalCost"><i class="cashicon    icons"></i>Total Cost of visible items:<span class="total-visible-amount">0</span></div>');
            }
            pager += ('<div class="dataTables_paginate paging_full_numbers" id="' + id + '_paginate"><button type="button" class="first paginate_button" id="' + id + '_first"><span class="glyphicon glyphicon-step-backward"></span></button>');
            pager += ('<button type="button" class="prev previous paginate_button" id="' + id + '_previous" ><span class="glyphicon glyphicon-chevron-left"></span></button>');
            pager += ('<span class="pagedisplay" ></span>');
            pager += ('<button type="button" class="next paginate_button" id="' + id + '_next"><span class="glyphicon glyphicon-chevron-right"></span></button>');
            pager += ('<button type="button" class="last paginate_button" id="' + id + '_last"><span class="glyphicon glyphicon-step-forward"></span></button></div>');
            pager += '</div>';
            $pager.html(pager);
        }
        try {
            sorterOptions = $.isPlainObject(sorterOptions) ? sorterOptions : {};
            pagerOptions = $.isPlainObject(pagerOptions) ? pagerOptions : {};
            $this.tablesorter($.extend(true, {
                // this will apply the bootstrap theme if "uitheme" widget is included
                // the widgetOptions.uitheme is no longer required to be set
                theme: "bootstrap",
                widthFixed: true,
                headerTemplate: '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
                // widget code contained in the jquery.tablesorter.widgets.js file
                // use the zebra stripe widget if you plan on hiding any rows (filter widget)
                widgets: ["uitheme", "filter", "zebra"],
                widgetOptions: {
                    // using the default zebra striping class name, so it actually isn't included in the theme variable above
                    // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
                    zebra: ["even", "odd"],
                    // reset filters button
                    filter_reset: ".reset"
                    // set the uitheme widget to use the bootstrap theme class names
                    // this is no longer required, if theme is set
                    // ,uitheme : "bootstrap"
                }
            }, sorterOptions))
                .tablesorterPager($.extend(true, {
                    // target the pager markup - see the HTML block below
                    container: $pager,
                    // target the pager page select dropdown - choose a page
                    cssGoto: ".pagenum",
                    // remove rows from the table to speed up the sort of large tables.
                    // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
                    removeRows: false,
                    // output string - default is '{page}/{totalPages}';
                    // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
                    output: '{startRow} - {endRow} / {totalRows} ({filteredRows})',
                    size: parseInt(pageSize, 10) || 5
                }, pagerOptions));
        } catch (err) {
            console.warn(err.message, err.stack);
        }
        $parent.find('.showmoreBtn')
            .toggle(function () {
                // show
                // console.log("Expand table", this);
                $this.find(".pagesize")
                    .val(100000)
                    .trigger("change");
                $(this)
                    .addClass('hideBtn');
            }, function () {
                // hide
                // console.log("Collapse table", this);
                $this.find(".pagesize")
                    .val(parseInt(pageSize, 10) || 5)
                    .trigger("change");
                $(this)
                    .removeClass('hideBtn');
            });
    }, 100);
    return $this;
};
window.setupTableSorter = function (selector) {
    return (setupTableSorterChecked(selector, true));
};

function setupTableSorterCheckedSimple(options) {
    // Simpler parameter passing
    var paramKeys = ['selector', 'displayTotalCount', 'pageSize', 'customFooterMarkup', 'hidePaginationToggleButton', 'themeOptions', 'sorterOptions', 'pagerOptions'];
    var paramData = [];
    for (var i = 0, l = paramKeys.length; i < l; i++) {
        paramData.push(options[paramKeys[i]]);
    }
    //    console.log('setupTableSorterCheckedSimple', options, paramData);
    return setupTableSorterChecked.apply(this, paramData);
};
window.buildTableFromArray = function (data, excludeKeys, headings, nameChanges, classForKeyLabels, classForKeyItems, customKeyConverter) {
    "use strict";
    // Generate the markup for a table from the given data
    var i, j, len, len2, current, markup, fieldName;
    window.Object = window.Object || {};
    window.Object.keys = window.Object.keys || (function (obj) {
        return $.map(obj, function (v, i) {
            return i;
        })
    });
    data = $.makeArray(data);
    if (!$.isArray(excludeKeys)) {
        excludeKeys = [];
    }
    if (!$.isPlainObject(nameChanges)) {
        nameChanges = {};
    }
    if (!$.isArray(headings)) {
        headings = data.length > 0 ? window.Object.keys(data[0]) : [];
    }
    if (!$.isPlainObject(classForKeyLabels)) {
        classForKeyLabels = {};
    }
    if (!$.isPlainObject(classForKeyItems)) {
        classForKeyItems = {};
    }
    markup = '<table id="exportTableid" class="table table-striped table-bordered">';
    markup += '<thead><tr>';
    for (i = 0, len = headings.length; i < len; i++) {
        fieldName = headings[i];
        if ($.inArray(fieldName, excludeKeys) !== -1) {
            continue;
        }
        markup += '<th class="sorting_asc ' + (classForKeyLabels[fieldName] || "") + '">' + ucfirst(nameChanges[fieldName] || fieldName) + '</th>';
    }
    markup += '</tr></thead>';
    markup += '<tbody>';
    for (i = 0, len = data.length; i < len; i++) {
        current = data[i] || {};
        markup += '<tr>';
        for (j = 0, len2 = headings.length; j < len2; j++) {
            fieldName = headings[j];
            if ($.inArray(fieldName, excludeKeys) !== -1) {
                continue;
            }
            var fieldMarkup = (current[fieldName] || "");
            if ($.isPlainObject(current[fieldName])) {
                fieldMarkup = '';
                for (var key in current[fieldName]) {
                    if (!current[fieldName].hasOwnProperty(key)) continue;
                    fieldMarkup += '<p><b>' + current[fieldName] + ':</b> <span>' + current[fieldName][key] + '</span></p>';
                }
            } else if ($.isArray(current[fieldName])) {
                fieldMarkup = JSON.stringify(current[fieldName]);
                //[{"Key":"Name","Value":"test1"}]
                //Key":"Name","Value":"test1"
                fieldMarkup = fieldMarkup.replace('[{"Key":"', ' ');
                fieldMarkup = fieldMarkup.replace('","Value":"', ' ');
                fieldMarkup = '<b>' + fieldMarkup.replace('"}]', ' ') + '</b>';
            }
            if (customKeyConverter && $.isFunction(customKeyConverter)) {
                fieldMarkup = customKeyConverter(fieldMarkup, current[fieldName], fieldName, current);
            }
            markup += '<td class="' + (classForKeyItems[fieldName] || "") + '" data-title="' + fieldName + '">' + fieldMarkup + '</td>';
        }
        markup += '</tr>';
    }
    markup += '</tbody></table>';
    return markup;
};

convertJsonToTableSecurityGroupsDetails = function(data) {
    var pageSize = 10;

    if (data.length > 0) {
        var mediaClass = '';
        for (var i = 0; i < data.length; i++) {
            if(data[i]['State']=='running'){
            data[i]["State"] = '<span class="label label-success" >'+data[i]['State']+'</span>' ;
        }else if(data[i]['State']=='stop'){
            data[i]["State"] = '<span class="label label-danger" >'+data[i]['State']+'</span>' ;
        }else{
            data[i]["State"] = '<span class="label label-warning" >'+data[i]['State']+'</span>' ;
        }

        }

        mediaClass = buildTableFromArray(data || [], ["services_with_info,links"], null, null, {
             "name" : " filter-select filter-exact ", 
        }), $table = $(mediaClass);
        mediaClass += setupTableSorterChecked($table, false, pageSize);
        $table.find('td[data-title="id"]').each(function() {
            var $td = $(this);
            var $parent = $td.parent();
            //$td = $this.parent();
            $td.addClass("btn-link").on("click", function(e) {
                e.preventDefault();

                var $selectedLink = this.dataset.title;

            });
        })

        return $table;
    } else {
        return '<div class="no_data"><span class="label label-primary">No Data</span></div>';

    }

};

convertJsonToTableSecurityGroups = function(data) {
    var pageSize = 10;
    for (var i = 0; i < data.length; i++) {
        if(data[i]["Safe Ports"] || data[i]["Danger Ports"] || data[i]["Instance"] || data[i]["Warning Ports"] )
        {    
                data[i]["Safe Ports"] = '<span class="label label-success" >'+data[i]['Safe Ports']+'</span>' ;
                data[i]["Danger Ports"] = '<span class="label label-danger" >'+data[i]['Danger Ports']+'</span>' ;
                if(data[i]["Instance"]!="" && data[i]["Warning Ports"]!="")
                    data[i]["Instance"] = '<span class="label label-danger" >'+data[i]['Instance']+'</span>' ;
                data[i]["Warning Ports"] = '<span class="label label-warning" >'+data[i]['Warning Ports']+'</span>' ;
        }   
    }


    if (data.length > 0) {
        var mediaClass = '';
        mediaClass = buildTableFromArray(data || [], ["services_with_info,links"], null, null, {
             "name" : " filter-select filter-exact ",
             "ResourceType" : " filter-select filter-exact ",
             "Key" : " filter-select filter-exact ",
             "UsageType" : " filter-select filter-exact ",
             "Operation" : " filter-select filter-exact ",
             "ResourceId" : " filter-select filter-exact ",
             "ReservedInstance" : " filter-select filter-exact ",
             "AvailabilityZone" : " filter-select filter-exact "
        }), $table = $(mediaClass);
        mediaClass += setupTableSorterChecked($table, false, pageSize);
        $table.find('td[data-title="id"]').each(function() {
            var $td = $(this);
            var $parent = $td.parent();
            //$td = $this.parent();
            $td.addClass("btn-link").on("click", function(e) {
                e.preventDefault();

                var $selectedLink = this.dataset.title;

            });
        })

        return $table;
    } else {
        return '<div class="no_data"><span class="label label-primary">No Data</span></div>';

    }

};


convertJsonToTableTags = function(data) {
    var pageSize = 10;
    if (data.length > 0) {
        var mediaClass = '';
        for (var i = 0; i < data.length; i++) 
        {
        	data[i]["actions"] = '<a href="'+data[i]['url']+'" class="viewTaggedcost" id="viewTaggedcost" name="viewTaggedcost">View Current Tagged cost</a>';
        	delete data[i]['url'];
        	delete data[i]['id'];
        }
        
        mediaClass = buildTableFromArray(data || [], ["services_with_info,links"], null, null, {
             "name" : " filter-select filter-exact ",
             "ResourceType" : " filter-select filter-exact ",
             "Key" : " filter-select filter-exact "
        }), $table = $(mediaClass);
        mediaClass += setupTableSorterChecked($table, false, pageSize);
        $table.find('td[data-title="id"]').each(function() {
            var $td = $(this);
            var $parent = $td.parent();
            //$td = $this.parent();
            $td.addClass("btn-link").on("click", function(e) {
                e.preventDefault();

                var $selectedLink = this.dataset.title;

            });
        })

        return $table;
    } else {
        return '<div class="no_data"><span class="label label-primary">No Data</span></div>';

    }

};


convertJsonToTableInstances = function(data) {
    var pageSize = 10;
    if (data.length > 0) {
        var mediaClass = '';
        var form_text = '';
       
       for (var i = 0; i < data.length; i++) 
        {
           if(data[i]["State"]=='running')
           {
                     data[i]["actions"] = '<a href="'+data[i]['url']+'" class="push_button red" id="viewTaggedcost" name="viewTaggedcost"><i class="fa fa-power-off"></i></a>';
                     data[i]["State"] = '<span class="label label-success" >'+data[i]['State']+'</span>' ;
           }
           else if(data[i]["State"]=='stopped')
           {
                     data[i]["actions"] = '<a href="'+data[i]['url']+'" class="push_button blue" id="viewTaggedcost" name="viewTaggedcost"><i class="fa fa-play"></i></a>';
                     data[i]["State"] = '<span class="label label-danger" >'+data[i]['State']+'</span>' ;
           } 
           else
           {
                     //data[i]["actions"] = '<a href="'+data[i]['url']+'" class="push_button blue" id="viewTaggedcost" name="viewTaggedcost"><i class="fa fa-play"></i></a>';
                     data[i]["State"] = '<span class="label label-warning" >'+data[i]['State']+'</span>' ;
           }
            delete data[i]['url'];
        }
        
         mediaClass = buildTableFromArray(data || [], ["services_with_info,links"], null, null, {
             "name" : " filter-select filter-exact ",
             "ResourceType" : " filter-select filter-exact ",
             "Key" : " filter-select filter-exact "
        }), $table = $(mediaClass);
        mediaClass += setupTableSorterChecked($table, false, pageSize);
        $table.find('td[data-title="id"]').each(function() {
            var $td = $(this);
            var $parent = $td.parent();
            //$td = $this.parent();
            $td.addClass("btn-link").on("click", function(e) {
                e.preventDefault();

                var $selectedLink = this.dataset.title;

            });
        })

        return $table;
    } else {
        return '<div class="no_data"><span class="label label-primary">No Data</span></div>';

    }

};

convertJsonToTableAuditReports = function(data) {
    var pageSize = 10;
    if (data.length > 0) {
        var mediaClass = '';
        for (var i = 0; i < data.length; i++) 
        {
        	data[i]["actions"] = '<div id="audit_reports'+i+'">' + '<a href class="viewAuditReport" id="viewAuditReport" onclick="viewAuditReport(\'' + data[i]['report'] + '\', \'' + data[i]['accountId'] + '\', \'' + data[i]['oid'] + '\', \'' + i + '\'); return false;" name="viewAuditReport">View Audit Report</a></div>';
            delete data[i]['accountId'];
     		delete data[i]['oid'];
     		delete data[i]['report'];
        }
        mediaClass = buildTableFromArray(data || [], ["services_with_info,links"], null, null, {
             "name" : " filter-select filter-exact "
        }), $table = $(mediaClass);
        mediaClass += setupTableSorterChecked($table, false, pageSize);
        $table.find('td[data-title="id"]').each(function() {
            var $td = $(this);
            var $parent = $td.parent();
            //$td = $this.parent();
            $td.addClass("btn-link").on("click", function(e) {
                e.preventDefault();

                var $selectedLink = this.dataset.title;

            });
        })
        return $table;
    } else {
        return '<div class="no_data">No Data</div>';
    }
};

viewAuditReport = function (url, accountId, oid , i)
{
   var jqxhr= $.ajax({
    url :url,
    data:{'accountId':accountId,'oid' : oid},
    success:function(response){
                 $('#audit_reports'+i).html(response);
              }
          });
};


// function timeAgo(time){
//   var units = [
//     { name: "second", limit: 60, in_seconds: 1 },
//     { name: "minute", limit: 3600, in_seconds: 60 },
//     { name: "hour", limit: 86400, in_seconds: 3600  },
//     { name: "day", limit: 604800, in_seconds: 86400 },
//     { name: "week", limit: 2629743, in_seconds: 604800  },
//     { name: "month", limit: 31556926, in_seconds: 2629743 },
//     { name: "year", limit: null, in_seconds: 31556926 }
//   ];
//   var diff = (new Date() - new Date(time*1000)) / 1000;
//   if (diff < 5) return "now";
  
//   var i = 0;
//   while (unit = units[i++]) {
//     if (diff < unit.limit || !unit.limit){
//       var diff =  Math.floor(diff / unit.in_seconds);
//       return diff + " " + unit.name + (diff>1 ? "s ago" : " ago");
//     }
//   };
// }
