function search_message_id_index(nameKey, myArray){
    for (var i=0; i < myArray.length; i++) {
        if (myArray[i].ID == nameKey) {
            return i;
        }
    }
}

function test(){
	console.log("hello");
}

var Pagination = function (pages_el, table_el)
{
	var sort_cols = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

	// PRIVATE DATA AND FUNCTIONS
	var total_pages;
	var rows_per_page = 10; // default 10 rows per page
	var pagination_data;
	var sort_type = 'asc';
	var sort_col;
	var start_row_num;
	var current_page = 1;
 
	var pagination = pages_el instanceof Object ? pages_el : $('#' + pages_el);
	var $table = (table_el instanceof Object) ? table_el : $('#' + table_el);
	var $thead = $table.find('thead');

	var $tbody = $table.find('tbody');
	var $tfooter = $table.find('tfooter');

	var MUSTACHE_TEMPLATE = $tbody.find('script').html();
	var DATA = {};
	var data_prop;

	if (sort_cols) {
		$thead.find('th').addClass('sort_column sort_default');
		$thead.find('th').on('click', function() {
			$(this).siblings().removeClass().addClass('sort_column sort_default');
			if ($(this).hasClass('sort_default') || $(this).hasClass('sort_desc')) {
				sort_type = 'asc';
			}
			else {
				sort_type = 'desc';
			}
			$(this).removeClass().addClass('sort_column sort_' + sort_type);
			pagination.bootpag({page: 1});
			sort_col = $(this).data('col');
			//console.log(sort_col);
			sort_rows(sort_col, sort_type);
			
		});
	}

	var render = function () // default to zero because rs objects start with an index of 0
	{
		start_row_num = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;

		DATA[data_prop] = get_rows(start_row_num);

		$tbody.html(Mustache.render(MUSTACHE_TEMPLATE, DATA));
		//console.log("LENGTH : " + pagination_data.length);
		//var debug = JSON.stringify(DATA[data_prop], null, 4); 
		//console.log("DATA : " + debug);
	}

	var init = function (data) {
		pagination_data = data;
		total_pages = Math.ceil(data.length / rows_per_page);

		if(total_pages === 0)
			total_pages = 1;
		
		pagination.bootpag({
			total: total_pages
		}).on('page', function (event, page_no) {
			start_row_num = rows_per_page * (page_no - 1);
			current_page = page_no;
			render(start_row_num);
		});
	}

	var get_rows = function (start_row_num) {
		var page_rows = [];
		var row_increment = 0;

		if (pagination_data.length > 0)
		{
			// params: el, index, array
			pagination_data.forEach(function (row, row_num, rs) {
				if (row_increment++ < rows_per_page && rs[start_row_num] !== undefined) {
					page_rows.push(rs[start_row_num++]);
				}
			});
		}

		return page_rows;
	}
	
	
	var sort_rows = function (column, sort_type) {
		
		//jay debug
		//console.log("LENGTH : " + pagination_data.length);
		//var debug = JSON.stringify(pagination_data[0], null, 4); 
		//console.log("DATA : " + debug);
		
		if(pagination_data.length > 0)
		{
			pagination_data.sort(function(row1, row2)
			{
				var row1_data = (row1[column]) ? row1[column].toUpperCase() : ''; // ignore upper and lowercase
				var row2_data = (row2[column]) ? row2[column].toUpperCase() : ''; // ignore upper and lowercase

				if (sort_type == 'asc') {
					if (row1_data < row2_data) {
						return -1;
					}
					if (row1_data > row2_data) {
						return 1;
					}
				}
				else if (sort_type == 'desc') {
					if (row1_data > row2_data) {
						return -1;
					}
					if (row1_data < row2_data) {
						return 1;
					}
				}

			  // data must be equal
			  return 0;
			});
			
		}

		render(start_row_num);
	}

	// PUBLIC FUNCTIONS
	this.create = function (resultset, prop_name) {
		data_prop = prop_name;
		var rows_per_page = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

		if (rows_per_page !== null) {
			rows_per_page = rows_per_page;
		}

		init(resultset);
	};

	this.get_sort_type = function () {
		return sort_type;
	};

	this.current_page_num = function () {
		return current_page;
	};

	this.get_sort_column = function () {
		return sort_col;
	};
	
	//Jay
	//Update READ STATUS value in array of object.
	this.update_read_status = function(message_id){
		//test();
		//console.log("MESSAGE_ID : "  + message_id);
		//Search Message ID 
		var index = search_message_id_index(message_id, pagination_data);
		//console.log("RESULT = " + pagination_data[index].ID);
		pagination_data[index].STATUS = "mail_read";
		pagination_data[index].IS_READ = "";
	}
	this.sort_rows = sort_rows;
	this.render = render;
};