/*
	PURPOSE:
	cache anything to be accessible anywhere
	HOW TO USE:
	use var name and functions provided
	e.g.
		cache.set('<cache-name>', <cache_data>);
		cache.get('<cache-name>');
*/
let cache = (function()
{
	let cached_elements = {};

	// returns the element be cached so this could be used in initializing statements e.g. var $element = cache.set('myEl', $myEl);
	function set_cache(el, el_obj)
	{
		cached_elements[el] = el_obj;

		return el_obj;
	}

	// gets the stored cache us
	function get_cache(el = null)
	{
		return cached_elements[el];
	}

	return {
		set: set_cache,
		get: get_cache
	}
})();