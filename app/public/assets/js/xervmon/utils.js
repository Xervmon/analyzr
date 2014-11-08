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
            pager += ('<div class="dataTables_paginate paging_full_numbers" id="' + id + '_paginate"><button type="button" class="first paginate_button" id="' + id + '_first"></button>');
            pager += ('<button type="button" class="prev previous paginate_button" id="' + id + '_previous" ></button>');
            pager += ('<span class="pagedisplay" ></span>');
            pager += ('<button type="button" class="next paginate_button" id="' + id + '_next"></button>');
            pager += ('<button type="button" class="last paginate_button" id="' + id + '_last"></button></div>');
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

convertJsonToTableSecurityGroups = function(data) {
    var pageSize = 10;
    //alert(cloudAccountId);
    if (data.length > 0) {
        var mediaClass = '';
        for (var i = 0; i < data.length; i++) {
            //data[i]["actions"] = '<div>' + '<span style="padding-right:8px; cursor: pointer;" title="View Details of the Security Group">' + getSGViewDetail(cloudAccountId, data[i]) + '</span>' + '<span style="padding-right:8px; cursor: pointer;" title="Delete Security Group">' + getDeleteSG(cloudAccountId, data[i]) + '</span>' + '</div>';
            //delete data[i]['IpPermissions'];
            //delete data[i]['IpPermissionsEgress'];
            //delete data[i]['OwnerId'];
        }

        mediaClass = buildTableFromArray(data || [], ["services_with_info,links"], null, null, {
            // "State" : " filter-select filter-exact "
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