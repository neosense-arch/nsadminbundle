(function($){
	$.fn.numberPicker = function(options){
		// methods
		if (typeof(options) === 'string') {
			return this;
		}


		// settings
		var settings = $.extend({
			plus:  null,
			minus: null,
			min:   null,
			max:   null
		}, options);

		// direct input filtration
		this.keyup(function(){
			this.value = filterValue(this.value, settings.min, settings.max);
		});

		// for each element in stack
		this.each(function(){
			var input = $(this);
			var plus  = resolveButton(settings.plus, input);
			var minus = resolveButton(settings.minus, input);

			plus.click(buttonClickHandler(input, 1, settings.min, settings.max));
			minus.click(buttonClickHandler(input, -1, settings.min, settings.max));
		});

		return this;
	};

	/**
	 * Filters input value
	 *
	 * @param {string|Number} value
	 * @param {Number} min
	 * @param {Number} max
	 * @returns {string|int}
	 */
	var filterValue = function(value, min, max){
		// numeric
		value = value.toString().replace(/[^0-9\.]/g,'');

		// empty value
		if (value === '') {
			return '';
		}

		// interval
		if (min !== null && value < min) {
			return min;
		}
		if (max !== null && value > max) {
			return max;
		}

		return +value;
	};

	/**
	 * Retrieves button
	 *
	 * @param {string|Function} button
	 * @param scope
	 * @returns {jQuery}
	 */
	var resolveButton = function(button, scope){
		var res;

		// button as function
		if (typeof(button) == 'function') {
			res = $($.proxy(button, scope)());
			if (res.length != 1) {
				throw "Function must return exactly one jQuery element, "+res.length+" given ("+button+")";
			}
		}

		// button as array
		if (Object.prototype.toString.call(button) === '[object Array]') {
			// checking param
			if (button.length !== 2) {
				throw "Button array descriptor must contain exactly 2 elements: parent selector and child selector, "+button.length+" given (" + button + ")";
			}

			// checking parent
			var parent = $(scope).parents(button[0]);
			if (!parent.length) {
				throw "Parent '"+button[0]+"' wasn't found";
			}
			if (parent.length > 1) {
				throw "Parent selector '"+button[0]+"'must return exactly one element, "+parent.length+" given";
			}

			// checking button
			res = parent.find(button[1]);
			if (!res.length) {
				throw "Button element "+button[0] + " -> " + button[1]+" wasn't found";
			}
			if (res.length > 1) {
				throw "Button selector '"+button[0] + " -> " + button[1]+"'must return exactly one element, "+res.length+" given";
			}
		}

		// button as a selector
		else {
			res = $(button);
			if (button && !res.length) {
				throw "Incorrect value '"+button+"': no elements found";
			}
		}

		// setting button unselectable
		setUnselectable(res);

		return $(res);
	};

	/**
	 * Creates control buttons click handler
	 *
	 * @param {HTMLElement|string|jQuery} input
	 * @param {int} inc
	 * @param {int} min
	 * @param {int} max
	 * @returns {Function}
	 */
	var buttonClickHandler = function(input, inc, min, max){
		return function(){
			var value = +input.val() + inc;

			// minimum 0
			min = min || 0;

			// interval
			if (value < min) {
				value = min;
			}
			if (max !== null && value > max) {
				value = max;
			}

			input.val(value);
			return false;
		}
	};

	/**
	 * Makes element unselectable
	 *
	 * @param {HTMLElement|string|jQuery} el
	 */
	var setUnselectable = function(el) {
		$(el).css({
			'-webkit-touch-callout': 'none',
			'-webkit-user-select':   'none',
			'-khtml-user-select':    'none',
			'-moz-user-select':      'none',
			'-ms-user-select':       'none',
			'user-select':           'none'
		});
	};
})(jQuery);