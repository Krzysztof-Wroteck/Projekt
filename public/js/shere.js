/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
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
/*!*******************************!*\
  !*** ./resources/js/shere.js ***!
  \*******************************/
__webpack_require__.r(__webpack_exports__);
$(document).ready(function () {
  $('.shere').on('click', function (event) {
    event.preventDefault();
    var postId = $(this).data('id');
    var shereButton = $(this);
    var currentScroll = $(window).scrollTop();
    $.ajax({
      method: 'POST',
      url: '/posts/list/' + postId,
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (data) {
      if (data.status === 'success') {
        var sheresCountElement = shereButton.find('.sheres-count');
        if (sheresCountElement) {
          sheresCountElement.text(data.sheresCount + ' sheres');
        }
      } else {
        Swal.fire("Error", "Wystąpił błąd podczas sherowania.", "error");
      }
    }).fail(function (error) {
      Swal.fire("Error", "Wystąpił błąd podczas sherowania.", "error");
    }).always(function () {
      window.scrollTo(0, currentScroll);
      location.reload();
    });
  });
});
/******/ })()
;