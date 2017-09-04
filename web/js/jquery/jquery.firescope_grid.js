/*
** FIRESCOPE
** Copyright (C) 2006-2008  Firescope Inc.
*/
/*
FireScope Grid

displays data in a tabular format
adds text filters, sorting, and pagination via ajax
server side scripting is required

<h3>Changelog</h3>
<ul>
	<li><strong>0.1.4</strong> <em>2008-12-01</em><br/>
	- renamed parm timeout to ajaxTimeout<br/>
	- added parm ajaxRefresh, to auto refresh the current grid page in X ms<br/>
	  &nbsp;&nbsp;&nbsp;&nbsp;must be > 1000 and  should be > than time to return your data<br/>
	  &nbsp;&nbsp;&nbsp;&nbsp;will not refresh till prior ajax call ends<br/>	  
	- added msgRows, defaulted to blank<br/>
	- added msgRefresh, defaulted to ' Refresh', only shown if using ajaxRefresh<br/>
	- added selectorResult, defaulted to null; usefull if your result contians more then just a table; used to to find the table<br/>
	- added cssEvenRow, cssOddRow; in case table doesnt already have its own css<br/>
	- firescope_grid_total replaced with calling id + '_total' to allow multiple grids on one page<br/>	
	- new default images and css
	- if nbr results less than nbr requested rows, assume end of results found and display correct nbr rows
	- if selectorHeader not sent or found, use first row of table as header<br/>
	- fixes for entering page number; allow backspace, del, etc<br/>
	- fixes for filtering indicator and double filter on keypress enter and then blur<br/>
	- fixes for next page and start page buttons when nbr pages not known and nav bar is set to auto<br/>	
	- fixes for showing nav buttons as disabled; other css fixes for ie7
	</li>
	
	<li><strong>0.1.3</strong> <em>2008-08-26</em><br/>
	- return error message if no results returned<br/>
	- added msgCustom to add array fo custom html elements to nav bar, if any<br/>
	- added navCustomLocation to add custom msgs, if any, to left or right of nav bar<br/>
	- added firescope_grid_offset parm to be returned on ajax calls; 
	  &nbsp;&nbsp;&nbsp;&nbsp;to save a user calculation of: (firescope_grid_page - 1) * firescope_grid_rows<br/>
	- fixed; when total not provided, display correct x rows of y instead of x of -1<br/>
	- fixed; css changes for IE<br/>
	</li>
	
	<li><strong>0.1.2</strong> <em>2008-08-18</em><br/>
	- added selectorFooter // null|*; selector used to find() the table footer, if any<br/>
	- added returnSortColName // true|false; return sorting column name; <br/>
	  &nbsp;&nbsp;&nbsp;&nbsp;useful if column name same as db field name or for custom messages<br/>
	- added returnFilterColName // true|false; return filtering column name; <br/>
	  &nbsp;&nbsp;&nbsp;&nbsp;useful if column name same as db field name or for custom messages<br/>		
	- if nbr of rows returned from table less than requested, next and last page buttons disabled<br/>
	- &lt;span id="firescope_grid_total" style="display:none"&gt;'.$total.'&lt;/span&gt; is no longer required; <br/>
	  &nbsp;&nbsp;&nbsp;&nbsp;but it is still recommended and useful if you want to display page # of pages<br/>
	</li>
	
	<li><strong>0.1.1</strong> <em>2008-07-23</em><br/>
	- added selectorHeader<br/>
	- fixed cfg.nbrPages, display of pages and records in edge cases<br/>
	</li>

	<li><strong>0.1.0</strong> <em>2008-06-09</em><br/>
	- initial version<br/>
	- inspired by flexigrid and ingrid<br/>
	- no actuall code used from flexigrid nor ingrid<br/>
	- ideas for css, images, and layoout inspired by flexigrid and ingrid<br/>

	</li>
</ul>
*/
jQuery.fn.firescope_grid = function(options) {


	//
	// config
	//
	
	// external config options
	var cfg = {
        // important options
        url: '',                 // ;url to get data from via ajax         
        rows: 8,                // #; nbr rows to show at a time; x rows = 1 page
        selectorHeader: 'tr th', // null|*; selector used to find() the table header
                
        // other options
        total: 0,                // #; total nbr rows to be shown
        height: 'auto',          // auto|#px|#%; auto - uses variable height of data; px - absolute; % - relative to parent container
        page: 1,                 // #; initial page to load; most likely 1
        
        data: {},                // ;additional parameters to send attached to url; your scripts query string perhaps?
        dataType: 'html',        // html; data format returned from ajax call; todo json|others?
		
        ajaxTimeout: 600000,     // ajax timeout in ms
				ajaxRefresh: 30000,       // null|#; ajax refresh in ms; must be > 1000 and > than time to return your data
        
        sortCols: ['auto'],      // array[#]|array['auto']; array of column numbers to allow sorting; auto - all columns (0 based index)
        sortCol: 0,              // #; initial column number to sort by (0 based index)
        sortOrder: 'asc',        // asc|desc; initial sort order of sortCol
        sortType: 'server',      // server; server - sort server side; todo client|auto
        
        filterCols: [1,3],    // array[#]|array['auto']; array of column numbers to display filter text boxes above; auto - all columns (0 based index)
        filterCol: -1,           // #; initial filter column; -1 filter ignore; (0 based index)
        filterText: '',          // ; initial filter text for filterCol
        filterType: 'auto',      // server|client|auto; server - filter server side via your code; client - filter by js; auto - try to use js if makes sense

        returnSortColName: false,   // true|false; return sorting column name; useful if column name same as db field name or for custom messages
        returnFilterColName: false, // true|false; return filtering column name; useful if column name same as db field name or for custom messages
        
        navBarShow: 'auto',        // never|always|auto; never - never show; always - always show; auto - show if more rows than total rows, hide otherwise
        navBarAlign: 'right',      // left|right|center; css text-align
        navBarLocation: 'top',     // top|bottom; where to place navigation bar
		navCustomLocation: 'left', // left|right; where to place custom msg, if any, in relation to other nav bar components
            
		cssRowEven:      null,     // null|{css:value}|'csstag'; css for every other row
		cssRowOdd:       null,     // null|{css:value}|'csstag'; css for every other row
		cssRowMouseOver: null,     // null|{css:value}|'csstag'; css for mouse over
		
        msgFilterHelp:             '<Filter>',
        msgPagesExceedMax:         'There are only {nbr_pages} pages.',
        msgPageExceedMax:          'There is only {nbr_pages} page.',
        msgEnterAValidPageNbr:     'Enter a valid page number.',
        msgURLInvalid:             'Invalid url for data. Trying current url..',
        msgLoading:                'Loading..',
        msgNetworkError:           'Retrieve data.',
        msgReloadPage:             'Reload page.',
        msgStatusDispFromToTotal:  '<span class="white_text">Displaying {from} to {to} of {total}</span>',
        msgStatusDispFromTo:       '<span class="white_text">Displaying {from} to {to}</span>',
        msgStatusDispNone:         '<span class="white_text">No results</span>',		
		msgRows:                   '', // Rows
		msgRefresh:                ' <span class="white_text">Refresh</span>',
		msgCustom:					['none'],	// ['none']|[*]; custom messages to appear in nav bar

        selectorFooter: null,     // null|*; selector used to find() the table footer, if any  
        selectorResult: null, 	  // null|*; selector used to parse result; useful if dont have control over result
		selectorIgnoreRows: null, // null|*; selector used to igonre when couting nbr rows for nav
        
        ignore: ''               // placeholder; ignore
	};
	$.extend(cfg, options);
	
	// internal config options
	cfg.nbrPages = -1; 			// number of pages; calculated
	cfg.navBarAdded = 'init';	// true|false|init; flag to indicate whether nav bar was added to the grid or not; init - first run so build
	
	cfg.sortColName = '';
	cfg.filterColName = '';	
	cfg.filterTextPrior = ''; // prior filter text; used to check if filter actually changed
	
	cfg.cssRowType = cfg.cssRowEven == null || cfg.cssRowOdd == null ? null : (typeof(cfg.cssRowEven) == 'string' && typeof(cfg.cssRowOdd) == 'string') ? 'class' : 'hash';
	cfg.cssRowOverType = cfg.cssRowMouseOver == null ? null : (typeof(cfg.cssRowMouseOver) == 'string') ? 'class' : 'hash';
	
	cfg.timeout = null; // used w/ ajaxRefresh to store the setTimout() result
	
	
	// store grid container
	var t = this;
	t.addClass('firescope_grid');
	cfg.id = t.attr('id');
	
	// 
	// (m_*) html markup; jquery objects;
	//
	
	// main table
	var m_content = $('<div />');
			
	// attached to each header column
	var m_filter_bar = $('<div />');
	var m_filter = $('<input type="text" size="10" col="0" class="filter-inactive" value="' + cfg.msgFilterHelp + '">').click( function() {
		$(this).val('');
	}).bind('keypress blur', function(e) {g.m_filter_action(e, this);});
	
	// bottom navigation bar
	var m_nav_bar = $('<div class="nav-toolbar" />');
	// rows selection box
	var m_rows = $('<select size="1" class="nav-rows"><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="250">250</option><option value="500">500</option></select>').change( function() {
		// change the size of contents depending on the number of rows
		cfg.rows = $(this).val();
		
		//alert(cfg.total);
		//$('#contents').height(cfg.total * 50 + 'px');

		g.gotoPage(1);
	});
	// go to first page
	var m_start_btn = $('<button type="button" class="nav-first"><br/></button>').click( function() {
		g.startPage();
	});	
	// go to previous page
	var m_prev_btn = $('<button type="button" class="nav-prev"><br/></button>').click( function() {
		g.prevPage();
	});
	// go to next page
	var m_next_btn = $('<button type="button" class="nav-next"><br/></button>').click( function() {
		g.nextPage();
	});
	// go to last page
	var m_last_btn = $('<button type="button" class="nav-last"><br/></button>').click( function() {
		g.lastPage();
	});		
	// go to page #; label ' of #pages' appended when nav bar built, if #pages known
	var m_page = $('<span class="nav-page white_text">Page&nbsp;&nbsp;<input type="text" class="nav-page-input" size="4" value="' + cfg.page + '" /></span>');
	m_page.find('input').bind('keypress blur', function(e) {g.m_page_action(e, this);});
	
	// divider
	var m_split = $('<span class="grid-split" />');
	// status 
	var m_reload_btn = $('<button type="button" class="nav-reload" id="firegrid_refresh_button"><br/></button>').click( function() {
		g.gotoPage(cfg.page);
	});
	var m_refresh = $('<select size="1" class="nav-refresh"><option value="0">stop</option><option value="30000">30 secs</option><option value="60000">1 min</option><option value="120000">2 min</option><option value="300000">5 min</option><option value="600000">10 min</option></select>').change( function() {
		cfg.ajaxRefresh = $(this).val();
		if (cfg.timeout != null) {
			clearTimeout(cfg.timeout);
			cfg.timeout = null;
		}
		g.refreshPage();
	});	
	var m_status = $('<span class="nav-status">status</span>');


	//
	// grid methods
	//
		
	var g = {
	
		// called on initial page load
		init: function () {

			if (cfg.height == 'auto') {
				m_content.css({});
			} else {
				m_content.css({height: cfg.height, overflow: 'auto'});
			}
			t.append(m_content);			

			// eh, overly simple url check of a sorts
			if (cfg.url.length > 0 && cfg.url.length < 4) {
				cfg.url = window.location.href;
				m_content.html(cfg.msgLoading+'<br/>'+cfg.msgURLInvalid);
			} else {
				m_content.html(cfg.msgLoading);
			}
			
			// go to initial cfg page
			g.gotoPage(cfg.page);		
		},		
		
		// the meat; load data
		gotoPage: function(page) {
			if (page > 0) {
				cfg.page = page;
			}
			m_page.find('input').val(cfg.page);
			
			// grid parms to send to url; your server script needs to do stuff with these; most likely in your sql
			var parms = {
				firescope_grid_page: cfg.page,
				firescope_grid_rows: cfg.rows,
				firescope_grid_offset: (cfg.page - 1) * cfg.rows,
				firescope_grid_sortCol: cfg.sortCol, 
				firescope_grid_sortOrder: cfg.sortOrder,
				firescope_grid_filterCol: cfg.filterCol, 
				firescope_grid_filterText: cfg.filterText				
			};
			var header = m_content.find(cfg.selectorHeader);
			if (header.length == 0) {
				header = m_content.find('table tr:first'); // default to first table row
			}
			if (cfg.returnSortColName && cfg.sortCol >= 0 && header.length > 0) {
				cfg.sortColName = $(header[cfg.sortCol]).find('span').html();
				parms.firescope_grid_sortColName = cfg.sortColName;
			}
			if (cfg.returnFilterColName && cfg.filterCol >= 0 && header.length > 0) {
				cfg.filterColName = $(header[cfg.filterCol]).find('span').html();
				parms.firescope_grid_filterColName = cfg.filterColName;
			}			
			
			// get user parms
			$.extend(parms, cfg.data);
			
			jQuery.ajax({
				url: cfg.url,
				data: parms,
				dataType: cfg.dataType,						// xml|html|script|json|jsonp|text
				type: 'POST',
				cache: false,
				timeout: cfg.ajaxTimeout,
				beforeSend: function(request) {
					// show loading
					m_nav_bar.addClass('nav-loading');
					m_reload_btn.removeClass('nav-reload');
					m_reload_btn.addClass('loading');
					
					// disable nav
					m_reload_btn.attr('disabled', 'disabled');
					m_reload_btn.addClass('nav-disabled');					
					m_next_btn.attr('disabled', 'disabled');
					m_next_btn.addClass('nav-disabled');
					m_last_btn.attr('disabled', 'disabled');
					m_last_btn.addClass('nav-disabled');
					m_prev_btn.attr('disabled', 'disabled');
					m_prev_btn.addClass('nav-disabled');					
					m_start_btn.attr('disabled', 'disabled');
					m_start_btn.addClass('nav-disabled');					
					if (!($.browser.mozilla && $.browser.version.substr(0, 3) < '1.9')) { 
						// firefox 2 (1.8) does not update select on disabled items; firefox 3 (1.9)
						m_rows.attr('disabled', 'disabled');
						m_rows.addClass('nav-disabled');
					}
					m_page.find('input').attr('disabled', 'disabled');
					
				},
				success: function(result, status){
					if (cfg.dataType == 'html') {
						if (result == '' || result == null) {
							result = 'No results returned';
						} else if (cfg.selectorResult != null) {
							result = $(result).find(cfg.selectorResult);
						}
						m_content.html(result);
					} else if (cfg.dataType == 'json') {						
					}
					
					g.updateGrid();
					
					if (cfg.timeout != null) {
						clearTimeout(cfg.timeout);
					}
					cfg.timeout = null;
					if (cfg.ajaxRefresh > 999) {
						g.refreshPage();
					}					
				},
				error: function(request, status, error){
					var msg = cfg.msgNetworkError;
					if (status != undefined && status != 'error') {
						msg += ' ' + status + '.';
					}
					if (error != undefined && error != 'error') {
						msg += ' ' + error + '.';
					}
					msg += '<br/>';
					msg += '<a href="javascript:window.location.reload();" class="action">' + cfg.msgReloadPage + '</a>';
					m_content.html(msg);
				},
				complete: function(request, status){
					// remove loading
					m_nav_bar.removeClass('nav-loading');
					m_reload_btn.removeClass('loading');
					m_reload_btn.addClass('nav-reload');
					
					// enable nav
					m_reload_btn.removeAttr('disabled');
					m_reload_btn.removeClass('nav-disabled');		
					m_rows.removeAttr('disabled');
					m_rows.removeClass('nav-disabled');
					m_page.find('input').removeAttr('disabled');
				}
			});
		
		},
		
		refreshPage: function() {
			if (cfg.timeout != null) {
				clearTimeout(cfg.timeout);
			}
			cfg.timeout = setTimeout(function() { g.gotoPage(0) }, cfg.ajaxRefresh);
		},
		
		reloadPage: function() {
			g.gotoPage(cfg.page);
		},		
		
		nextPage: function() {
			if (cfg.navBarShow == 'always' || cfg.nbrPages == -1 || cfg.page + 1 <= cfg.nbrPages) {
				cfg.page = cfg.page + 1;
				g.gotoPage(cfg.page);
			}			
		},
		prevPage: function() {
			if (cfg.page - 1 > 0) {
				cfg.page = cfg.page - 1;
				g.gotoPage(cfg.page);
			}
		},
		startPage: function() {
			if (cfg.navBarShow == 'always' || cfg.nbrPages == -1 || cfg.nbrPages > 1) {
				cfg.page = 1;
				g.gotoPage(cfg.page);
			}
		},
		lastPage: function() {
			if (cfg.navBarShow == 'always' || cfg.nbrPages > 1) {
				cfg.page = cfg.nbrPages;
				g.gotoPage(cfg.page);
			}
		},
		
		updateGrid: function() {
			g.updateTotal();

			g.updateHeader();
			
			g.updateNavBar();
			
			g.updateStatusDisplaying();
			
			g.updateCSS();			
		},
		
		// update total and nbr of pages; may have changed due to filter or user db changes
		updateTotal: function() {
			if ($('#' + cfg.id + '_total').length == 0) {
				cfg.total = -1;
				cfg.nbrPages = -1;
				return;
			}
			var total = $('#' + cfg.id + '_total').text();
			if (total == '') {
				cfg.total = -1;
				cfg.nbrPages = -1;
				return;				
			}
			cfg.total = parseInt(total);
			if (cfg.total > 0) {
				cfg.nbrPages = Math.ceil(cfg.total / cfg.rows);
			} else if (cfg.total == 0) {
				cfg.nbrPages = 1;
			} else {
				cfg.total = -1;
				cfg.nbrPages = -1;
			}
			
		},
		
		updateCSS: function() {
			if (cfg.cssRowEven == null || cfg.cssRowOdd == null) return;
		
			m_content.find('table tr').each( function(index) {
				var css = (index % 2 == 0) ? cfg.cssRowEven : cfg.cssRowOdd;
				
				if (cfg.cssRowType == 'class') {
					$(this).addClass(css);
				} else if (cfg.cssRowType == 'hash') {
					$(this).css(css);
				}

				$(this).unbind('mouseover mouseout');
				$(this).bind('mouseover', function(e) {
					if (cfg.cssRowOverType == 'class') {
						$(this).addClass(cfg.cssRowMouseOver);
					} else if (cfg.cssRowOverType == 'hash') {
						$(this).css(cfg.cssRowMouseOver);
					}			
				}).bind('mouseout', function(e) {
					if (cfg.cssRowOverType == 'class') {
						$(this).removeClass(cfg.cssRowMouseOver);
					} else if (cfg.cssRowOverType == 'hash') {
						$(this).css(css);
					}			
				});
			});			
		},
		
		findNbrRows: function() {
			var header = cfg.selectorHeader != null && m_content.find(cfg.selectorHeader).length > 0 ? 1 : 0;	
			var footer = cfg.selectorFooter != null && m_content.find(cfg.selectorFooter).length > 0 ? 1 : 0;	
			var ignore = cfg.selectorIgnoreRows != null && m_content.find(cfg.selectorIgnoreRows).length > 0 ? m_content.find(cfg.selectorIgnoreRows).length : 0;				
			if (cfg.selectorHeader != null) {
				var nbrRows = m_content.find(cfg.selectorHeader).parent('tr').siblings().length + 1 - header - footer - ignore;
			} else {
				var nbrRows = m_content.find('tr').length - header - footer - ignore;
			}
			// console.log(nbrRows, m_content.find(cfg.selectorHeader), header, footer, ignore);
			
			// adjust for the number of rows
			new_height = nbrRows * 67;
			if ( new_height > 600 )
				$('#contents').height(nbrRows * 67 + 'px');
			return(nbrRows);
		},
		updateStatus: function(status) {
			m_status.html(status+'&nbsp;&nbsp;');		
		},
		updateStatusDisplaying: function() {
			if (cfg.total > 0) {
				var disp = cfg.msgStatusDispFromToTotal;
			} else {
				var disp = cfg.msgStatusDispFromTo;
			}
			var nbrRows = g.findNbrRows();
			var from = cfg.rows * (cfg.page - 1) + 1;
			var to = cfg.rows * cfg.page;
			var tail = '';
			if (cfg.total == 0) {
				from = 0;
				to = 0;	
			} else if (nbrRows < cfg.rows) {
				to = (cfg.rows * (cfg.page - 1) + nbrRows);
				// tail = ' <span style="color:#AAA;font-size:90%;">(end)</span>';
			}
			if (to <= 0) {
				disp = cfg.msgStatusDispNone;
			} else {
				disp = disp.replace(/{from}/, from);
				disp = disp.replace(/{to}/, to);
				if (cfg.total > 0) {
					disp = disp.replace(/{total}/, cfg.total);
				}
				disp = disp + tail;
			}
			g.updateStatus(disp);
		},
		buildNavBar: function() {
			// only display nav bar if more rows than requested, or if told to
			if (cfg.navBarShow == 'always' || cfg.navBarShow == 'auto' && (cfg.total > cfg.rows || cfg.total == -1)) {
				m_rows.val(cfg.rows);
				// check if row selection has requested nbr rows; if not, add option for
				if (m_rows.val() != cfg.rows) {
					m_rows.find('option').each( function(i) {
						if (cfg.rows < $(this).attr('value')) {
							$(this).before('<option value="' + cfg.rows + '">' + cfg.rows + '</option>');
							return(false); // break
						}
					});
					m_rows.val(cfg.rows);
				}
				// check if wanting to refresh, and if refresh selection has requested time; if not, add option for
				if (cfg.ajaxRefresh != null) {
					m_refresh.val(cfg.ajaxRefresh);
					if (m_refresh.val() != cfg.ajaxRefresh) {
						m_refresh.find('option').each( function(i) {
							if (cfg.ajaxRefresh < $(this).attr('value')) {
								if (cfg.ajaxRefresh > 60000) {
									var time = (Math.floor(cfg.ajaxRefresh / 60000)) + ' mins';
								} else {
									var time = (Math.floor(cfg.ajaxRefresh / 1000)) + ' secs';
								}
								$(this).before('<option value="' + cfg.ajaxRefresh + '">' + time + '</option>');
								return(false); // break
							}
						});
						m_refresh.val(cfg.ajaxRefresh);
					}
				}
				// build nav bar
				m_nav_bar.css('text-align', cfg.navBarAlign);			
				m_nav_bar.append(m_rows).append(cfg.msgRows).append(m_split.clone());
				m_nav_bar.append(m_start_btn).append(m_prev_btn).append(m_split.clone());
				if (cfg.total > 0) {
					m_page.find('input').after('&nbsp;&nbsp;of <span>' + cfg.nbrPages + '</span>');
				}
				m_nav_bar.append(m_page).append(m_split.clone());
				m_nav_bar.append(m_next_btn).append(m_last_btn).append(m_split.clone());
				if (cfg.ajaxRefresh != null) {
					m_nav_bar.append(m_refresh).append(cfg.msgRefresh).append(m_reload_btn).append(m_split.clone());
				} else {
					m_nav_bar.append(m_reload_btn).append(m_split.clone());
				}
				m_nav_bar.append(m_status);
				
				if (cfg.msgCustom[0] != 'none') {
					if (cfg.navCustomLocation == 'left') {
						m_nav_bar.prepend('<span style="float:left;">' + cfg.msgCustom.join(' ') + '</span>');
					} else if (cfg.navCustomLocation == 'right') {
						m_nav_bar.append('<span style="float:right;">' + cfg.msgCustom.join(' ') + '</span>');
					}
				}
				
				if (cfg.navBarLocation == 'top') {
					t.prepend(m_nav_bar);
				} else {
					t.append(m_nav_bar);
				}
				cfg.navBarAdded = true;
			} else {
				cfg.navBarAdded = false;
			}
		},
		updateNavBar: function() {
			if (cfg.navBarAdded == 'init') {
				g.buildNavBar();			
			}
			if (!cfg.navBarAdded) {
				return false;
			}		
			m_page.find('span').html(cfg.nbrPages);
			
			var nbrRows = g.findNbrRows();

			if (nbrRows < cfg.rows) {
				m_next_btn.attr('disabled', 'disabled');
				m_next_btn.addClass('nav-disabled');
				m_last_btn.attr('disabled', 'disabled');
				m_last_btn.addClass('nav-disabled');
			} else if (cfg.navBarShow == 'always' && cfg.nbrPages == -1) {
				m_next_btn.removeAttr('disabled');
				m_next_btn.removeClass('nav-disabled');
				m_last_btn.attr('disabled', 'disabled');
				m_last_btn.addClass('nav-disabled');
			} else if (cfg.page + 1 > cfg.nbrPages) {
				if (cfg.nbrPages != -1) {
					m_next_btn.attr('disabled', 'disabled');
					m_next_btn.addClass('nav-disabled');
				} else {
					m_next_btn.removeAttr('disabled');
					m_next_btn.removeClass('nav-disabled');
				}
				m_last_btn.attr('disabled', 'disabled');
				m_last_btn.addClass('nav-disabled');
			} else {
				m_next_btn.removeAttr('disabled');
				m_next_btn.removeClass('nav-disabled');
				m_last_btn.removeAttr('disabled');
				m_last_btn.removeClass('nav-disabled');
			}
			if (cfg.page - 1 <= 0) {
				m_prev_btn.attr('disabled', 'disabled');
				m_prev_btn.addClass('nav-disabled');
				m_start_btn.attr('disabled', 'disabled');
				m_start_btn.addClass('nav-disabled');
			} else {
				m_prev_btn.removeAttr('disabled');
				m_prev_btn.removeClass('nav-disabled');
				m_start_btn.removeAttr('disabled');
				m_start_btn.removeClass('nav-disabled');
			}
		},
		updateHeader: function() {
			m_content.find(cfg.selectorHeader).each( function(i) {
				if ($(this).find('span').length > 0) {
					return false;
				}
				$(this).html('<span>' + $(this).html() + '</span>'); // unique wrapper for original column name
				var b_sortable = true;
				// sort indicator
				if ((cfg.sortCols[0] == 'auto' || $.inArray(i, cfg.sortCols) != -1) && i == cfg.sortCol) {
					(cfg.sortOrder == 'asc') ? $(this).addClass('sort-asc') : $(this).addClass('sort-desc');
				} else if (cfg.sortCols[0] == 'auto' || $.inArray(i, cfg.sortCols) != -1) {
					$(this).addClass('sort-none');
				} else {
					// non sortable column
					b_sortable = false;
				}
				if (b_sortable) {
					// user clicked header to change sort
					$(this).click( function(e) {
						// ignore any html form elements; like check all
						if ($(e.target).is('input') || $(e.target).is('select')) {
							return(true); // continue
						}
						if ($(this).hasClass('sort-desc') || $(this).hasClass('sort-none')) {
							cfg.sortOrder = 'asc';
							cfg.sortCol = i;
						} else if ($(this).hasClass('sort-asc')) {
							cfg.sortOrder = 'desc';
							cfg.sortCol = i;
						}
					
						if (1 || cfg.sortType == 'server') {
							// show ajax loading indicator; ajax data replaces on load
							$(this).addClass('sort-sorting');
							g.gotoPage(cfg.page);						
							
							// well, sorted server side which passes the filter text, if any
							// so now have to filter from server in case filter later removed
							cfg.filterType = 'server';
						}
					});
				}
				
				// search/filter
				//Andrei
		if (cfg.filterCols[0] == 'auto' || $.inArray(i, cfg.filterCols) != -1) {
					var filter = m_filter.clone(true);
					filter.attr('col', i);
					if (cfg.filterCol == i) {
						// restore filter
						filter.val(cfg.filterText);
						filter.addClass('filter-active');
						filter.removeClass('filter-inactive');
					} else {
						filter.addClass('filter-inactive');
						filter.removeClass('filter-active');						
					}
					// append hidden checkbox purely for formatting; some  headers have checkboxes which messes up height
					// place filter box on top; seems better
					$(this).append('<input type="checkbox" style="position:relative;z-index:-10;" disabled />').prepend('<br/>').prepend(filter);
				} else if (cfg.filterCols.length > 0) {
					// no filtering for this col, format similair to filtered cols
					$(this).append('<input type="checkbox" style="position:relative;z-index:-10;" disabled />').prepend('<br/>');
				}

			});
		},
		
		showBrowserInfo: function() {
		    $.each($.browser, function(i, val) {
		    	$("<div>" + i + " : <span>" + val + "</span>").appendTo(document.body);
			});
		},
		
		m_filter_action: function(e, elem) {
			elem = $(elem);
			if (e.type == 'keypress' && e.which == 13 || e.type == 'blur') {
				cfg.filterText = elem.val();
				
				if (cfg.filterText == '') {
					// user removed filter; reset filter to init state
					elem.addClass('filter-inactive');
					elem.removeClass('filter-active');				
					elem.val(cfg.msgFilterHelp);
				} else {
					cfg.filterCol = elem.attr('col');
					elem.addClass('filter-active');
					elem.removeClass('filter-inactive');				
				}
				
				if (cfg.filterTextPrior == cfg.filterText) {
					return true;
				}
				cfg.filterTextPrior = cfg.filterText;				
				
				// do filter server side if requested or if nav bar show; presumes nav bar shown for a reason or more rows avail than shown
				if (cfg.filterType == 'server' || cfg.filterType == 'auto' && cfg.navBarAdded) {
					if (cfg.filterCol != -1) {
						elem.addClass('filter-filtering');					
						g.gotoPage(1);
						elem.removeClass('filter-filtering');
					}
					if (cfg.filterText == '') {
						cfg.filterCol = -1;
					}
				} else {
					// client side filter
					elem.addClass('filter-filtering');
					if (cfg.dataType == 'html') {
						var rows_hidden = 0;
						t.find('tr').each( function(i) {
							// ignore filter/header
							if (i == 0) {
								return true;
							}
							// ignore header and footer of table
							if ($(this).hasClass('header') || $(this).hasClass('footer')) {
								return true;
							}
							var td = $(this).find('td');
							// make sure row visible so can search it
							td.parent().show();
							td.each( function(i) {
								var tr = $(this).parent();
								if (cfg.filterCol == i) {
									if ($(this).text().toUpperCase().indexOf(cfg.filterText.toUpperCase()) == -1) {
										tr.hide();
										rows_hidden++;
									} else {
										tr.show();
									}
								} else if (cfg.filterCol == -1) {
									tr.show();
									return false;
								}
							}); // end each td
						}); // end each tr

						// if filtered out all items, display notification .. from server
						// from server necessary since filter may be client but sort may be server
						// also nice to get custom no results message
						if (rows_hidden == cfg.rows || rows_hidden == cfg.total) {
							g.gotoPage(1);
							cfg.filterType = 'server';
						}
					} else if (cfg.dataType == 'json') {
					}
					elem.removeClass('filter-filtering');
					
				} // end filtering
				
				
			} // end if enter or blur
		},
		
		m_page_action: function(e, elem) {
			elem = $(elem);
			if (e.which >= 65 && e.which <= 90 || e.which >= 186) {
				e.preventDefault();
				return false;
			}
			if (e.type == 'keypress' && e.which == 13 || e.type == 'blur') {
				if (elem.val().length == 0) {
					elem.val(1);
				}			
				var page = parseInt(elem.val());
				if (page == cfg.page) {
					// uhm same page, so do ntohing
				} else if (page > cfg.nbrPages && cfg.nbrPages > 0) {			
					if (cfg.nbrPages == 1) {
						var disp = cfg.msgPageExceedMax;
					} else {
						var disp = cfg.msgPagesExceedMax;
					}
					disp = disp.replace(/{nbr_pages}/, cfg.nbrPages);
					g.updateStatus(disp);
					elem.val(cfg.page);
				} else if (page <= 0) {
					g.updateStatus(msgEnterAValidPageNbr);
					elem.val(cfg.page);
				} else {
					$('input[@name="grid_page"]').val(page); // update form page number, if exists; in case table has form/submit elements
					g.gotoPage(page);
				}
			}		
		},
		
		ignore: function() {
		}
	};

	g.init();
	
	return(g);
};
