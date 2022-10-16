/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/js/main/profile.js ***!
  \**************************************/
$(document).ready(function () {
  CKEDITOR.replace('info', {
    removePlugins: 'sourcearea',
    // Define the toolbar groups as it is a more accessible solution.
    toolbarGroups: [{
      "name": "basicstyles",
      "groups": ["basicstyles"]
    }, {
      "name": "paragraph",
      "groups": ["list", "blocks"]
    }, {
      "name": "document",
      "groups": ["mode"]
    }, {
      "name": "styles",
      "groups": ["styles"]
    }, {
      "name": "about",
      "groups": ["about"]
    }],
    // Remove the redundant buttons from toolbar groups defined above.
    removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord'
  });
  $('form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);

    // formData.append('info', editor.html.get())

    $.axios.post($(this).attr('action'), formData).then(function (response) {
      if (200 === response.status) {
        for (var type in response.data.messages) {
          for (var i = 0, n = response.data.messages[type].length; i < n; i++) {
            $('.messages-wrap ul').append("<li class=\"".concat(type, "\">\n                  <div class=\"close\"><i class=\"fas fa-times-circle\"></i></div>\n                  <div class=\"message-text\">").concat(response.data.messages[type][i], "</div>\n                </li>"));
          }
        }
      }
    })["catch"](function (error) {
      if (!!error.response.data.errors) {
        for (var i = 0, n = error.response.data.errors.length; i < n; i++) {
          $('.messages-wrap ul').append("<li class=\"error\">\n                  <div class=\"close\"><i class=\"fas fa-times-circle\"></i></div>\n                  <div class=\"message-text\">".concat(error.response.data.errors[i], "</div>\n                </li>"));
        }
      }
    });
  });
});
/******/ })()
;