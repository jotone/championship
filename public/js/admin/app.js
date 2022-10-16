/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/admin/libs/notifications.js":
/*!**************************************************!*\
  !*** ./resources/js/admin/libs/notifications.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Notifications": () => (/* binding */ Notifications)
/* harmony export */ });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
var Notifications = /*#__PURE__*/function () {
  function Notifications() {
    _classCallCheck(this, Notifications);
  }
  _createClass(Notifications, null, [{
    key: "push",
    value:
    /**
     * Add message to session
     *
     * @param msg
     * @param type
     * @returns {Notifications}
     */
    function push() {
      var msg = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
      var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'success';
      // Get messages list
      var messages = Object.keys(localStorage).indexOf('notificationMessages') >= 0 ? JSON.parse(localStorage.getItem('notificationMessages')) : {};
      // Check messages of type exist
      if (!messages.hasOwnProperty(type)) {
        messages[type] = [];
      }
      // Add message of type
      messages[type].push(msg);
      // Add messages to session
      localStorage.setItem('notificationMessages', JSON.stringify(messages));
      // Chain method
      return this;
    }

    /**
     * View messages
     */
  }, {
    key: "show",
    value: function show() {
      // Get messages list
      var messages = Object.keys(localStorage).indexOf('notificationMessages') >= 0 ? JSON.parse(localStorage.getItem('notificationMessages')) : {};
      // Check messages exist
      if (!$.isEmptyObject(messages)) {
        for (var type in messages) {
          for (var i = 0, n = messages[type].length; i < n; i++) {
            // View message
            $('.notifications-wrap').append(this.template(messages[type][i], type));
          }
        }
        $('.notifications-wrap li').each(function () {
          $(this).show(500).delay(2500).fadeOut(500, function () {
            $(this).off('click').remove();
          });
        });
        // Clear messages
        localStorage.removeItem('notificationMessages');
      }
      // Chain method
      return this;
    }
    /**
     * Message template
     *
     * @param msg
     * @param type
     * @returns {string}
     */
  }, {
    key: "template",
    value: function template() {
      var msg = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
      var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'success';
      return "\n    <li class=\"".concat(type, "\">\n      <div class=\"text-wrap\">").concat(msg, "</div>\n      <div class=\"close\"><i class=\"fas fa-times-circle\"></i></div>\n    </li>");
    }
  }]);
  return Notifications;
}();

/***/ }),

/***/ "./node_modules/debounce/index.js":
/*!****************************************!*\
  !*** ./node_modules/debounce/index.js ***!
  \****************************************/
/***/ ((module) => {

/**
 * Returns a function, that, as long as it continues to be invoked, will not
 * be triggered. The function will be called after it stops being called for
 * N milliseconds. If `immediate` is passed, trigger the function on the
 * leading edge, instead of the trailing. The function also has a property 'clear' 
 * that is a function which will clear the timer to prevent previously scheduled executions. 
 *
 * @source underscore.js
 * @see http://unscriptable.com/2009/03/20/debouncing-javascript-methods/
 * @param {Function} function to wrap
 * @param {Number} timeout in ms (`100`)
 * @param {Boolean} whether to execute at the beginning (`false`)
 * @api public
 */
function debounce(func, wait, immediate){
  var timeout, args, context, timestamp, result;
  if (null == wait) wait = 100;

  function later() {
    var last = Date.now() - timestamp;

    if (last < wait && last >= 0) {
      timeout = setTimeout(later, wait - last);
    } else {
      timeout = null;
      if (!immediate) {
        result = func.apply(context, args);
        context = args = null;
      }
    }
  };

  var debounced = function(){
    context = this;
    args = arguments;
    timestamp = Date.now();
    var callNow = immediate && !timeout;
    if (!timeout) timeout = setTimeout(later, wait);
    if (callNow) {
      result = func.apply(context, args);
      context = args = null;
    }

    return result;
  };

  debounced.clear = function() {
    if (timeout) {
      clearTimeout(timeout);
      timeout = null;
    }
  };
  
  debounced.flush = function() {
    if (timeout) {
      result = func.apply(context, args);
      context = args = null;
      
      clearTimeout(timeout);
      timeout = null;
    }
  };

  return debounced;
};

// Adds compatibility for ES modules
debounce.debounce = debounce;

module.exports = debounce;


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!***********************************!*\
  !*** ./resources/js/admin/app.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _libs_notifications__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./libs/notifications */ "./resources/js/admin/libs/notifications.js");
/* harmony import */ var debounce__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! debounce */ "./node_modules/debounce/index.js");
/* harmony import */ var debounce__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(debounce__WEBPACK_IMPORTED_MODULE_1__);
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }
function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


$(document).ready(function () {
  // Show current notifications
  _libs_notifications__WEBPACK_IMPORTED_MODULE_0__.Notifications.show();

  // Click side-menu element
  $('.side-menu').on('click', 'li', function () {
    if ($(this).children('ul').length) {
      $(this).toggleClass('active');
    }
  });
  // Highlight active side-menu item
  var url = window.location.href;
  var urlPath = url.split('/');
  if (urlPath[urlPath.length - 1] === 'create') {
    url = urlPath.slice(0, -1).join('/');
  } else if (urlPath[urlPath.length - 1] === 'edit') {
    url = urlPath.slice(0, -2).join('/');
  }
  $(".side-menu a[href=\"".concat(url, "\"]")).parents('li').addClass('active');

  // Convert text to slug
  $('[data-slug]').each(function () {
    // Get converter options
    var _$$attr$split = $(this).attr('data-slug').split('.'),
      _$$attr$split2 = _slicedToArray(_$$attr$split, 2),
      tag = _$$attr$split2[0],
      name = _$$attr$split2[1];
    // Target element
    var target = $("".concat(tag, "[name=\"").concat(name, "\"]"));
    // Host key print event
    $(this).on('keyup', (0,debounce__WEBPACK_IMPORTED_MODULE_1__.debounce)(function () {
      if (typeof target.attr('data-changed') === 'undefined') {
        // Set ascii value to target element
        target.val(window.Helpers.toAscii($(this).val()).replace(/-+/g, '-'));
      }
    }, 250));
    // If the slug data was entered manually
    target.on('keyup', function () {
      $(this).attr('data-changed', '1');
    });
  });

  // Image uploader
  $('.image-upload-wrap')
  // Upload image trigger
  .on('click', 'button[name="upload"]', function (e) {
    e.preventDefault();
    $(this).closest('.buttons-wrap').find('input[type="file"]').trigger('click');
  })
  // Clear image
  .on('click', 'button[name="clear"]', function (e) {
    e.preventDefault();
    $(this).closest('.buttons-wrap').find('input[type="file"]').val('');
    $(this).closest('.image-upload-wrap').find('.image-upload-preview').empty();
  })
  // Image upload event
  .on('change', 'input[type="file"]', function () {
    var file = $(this).prop('files')[0];
    var wrap = $(this).closest('.image-upload-wrap').find('.image-upload-preview');
    var reader = new FileReader();
    reader.onload = function (e) {
      !wrap.find('img').length && wrap.append('<img src="" alt="No image&hellip;">');
      wrap.find('img').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
  });

  // Form submit
  $('form[data-xhr]').on('submit', function (e) {
    var _this = this;
    // Redirect to option
    var forceRedirect = typeof $(this).attr('data-redirect') !== 'undefined';
    e.preventDefault();
    // Form data
    var tempFormData = new FormData(this);
    var formData = new FormData();
    var _iterator = _createForOfIteratorHelper(tempFormData),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var pair = _step.value;
        if (typeof CKEDITOR !== 'undefined' && pair[0] in CKEDITOR.instances) {
          formData.append(pair[0], CKEDITOR.instances[pair[0]].getData());
        } else {
          formData.append(pair[0], pair[1]);
        }
      }

      // Form method
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
    var method = typeof $(this).attr('method') !== 'undefined' ? $(this).attr('method') : 'get';
    // Send request
    $.axios[method.toLowerCase()]($(this).attr('action'), formData).then(function (response) {
      if (typeof $(_this).attr('data-msg') !== 'undefined') {
        var _$$attr$split3 = $(_this).attr('data-msg').split('.'),
          _$$attr$split4 = _slicedToArray(_$$attr$split3, 2),
          entity = _$$attr$split4[0],
          field = _$$attr$split4[1];
        var message = "".concat(entity, " \"").concat(response.data[field], "\" was successfully ").concat(201 === response.status ? 'created' : 'modified', ".");
        _libs_notifications__WEBPACK_IMPORTED_MODULE_0__.Notifications.push(message);
        if (forceRedirect) {
          window.location = window.Helpers.buildUrl($(_this).attr('data-redirect'), response.data.id, 2);
        } else if (201 === response.status) {
          window.location.reload();
        } else {
          _libs_notifications__WEBPACK_IMPORTED_MODULE_0__.Notifications.show();
        }
      }
    })["catch"](function (_ref) {
      var response = _ref.response;
      if (400 <= response.status && 500 >= response.status) {
        if (response.data.hasOwnProperty('message')) {
          _libs_notifications__WEBPACK_IMPORTED_MODULE_0__.Notifications.push(response.data.message, 'error');
        }
        if (response.data.hasOwnProperty('errors')) {
          for (var field in response.data.errors) {
            var messages = response.data.errors[field];
            for (var i = 0, n = messages.length; i < n; i++) {
              _libs_notifications__WEBPACK_IMPORTED_MODULE_0__.Notifications.push(messages[i], 'error');
            }
          }
          _libs_notifications__WEBPACK_IMPORTED_MODULE_0__.Notifications.show();
        }
      }
    })["finally"](function () {
      return $('.overlay, .overlay .preload').hide();
    });
  });
});
})();

/******/ })()
;