'use strict';

var Pagination = function Pagination(pagination, mustache_parent, mustache_template, data_prop) {
	var $this = this;
	this.total_pages;
	this.rows_per_page = 10; // default 10 rows per page
	this.pagination_data;

	this.pagination = pagination instanceof Object ? pagination : $('#' + pagination);
	this.mustache_parent = mustache_parent;
	this.MUSTACHE_TEMPLATE = mustache_template;
	this.DATA = {};
	this.data_prop = data_prop;
	this.DATA[data_prop] = '';

	function init(pagination_data) {
		$this.pagination_data = pagination_data;
		total_pages = Math.ceil(pagination_data.length / $this.rows_per_page);

		$this.pagination.bootpag({
			total: total_pages
		}).on('page', function (event, page_no) {
			var start_row_num = $this.rows_per_page * (page_no - 1);
			render(start_row_num);
		});
	}

	function create_pagination(resultset) {
		var rows_per_page = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

		if (rows_per_page !== null) {
			$this.rows_per_page = rows_per_page;
		}

		init(resultset);
	}

	function render() // default to zero because rs objects start with an index of 0
	{
		var start_row_num = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;

		$this.DATA[$this.data_prop] = get_rows(start_row_num);

		$this.mustache_parent.html(Mustache.render($this.MUSTACHE_TEMPLATE, $this.DATA));
	}

	function get_rows(start_row_num) {
		var page_rows = [];
		var row_increment = 0;

		// params: el, index, array
		$this.pagination_data.forEach(function (row, row_num, rs) {
			if (row_increment++ < $this.rows_per_page && rs[start_row_num] !== undefined) {
				page_rows.push(rs[start_row_num++]);
			}
		});

		return page_rows;
	}

	return {
		create: create_pagination,
		render: render
	};
};