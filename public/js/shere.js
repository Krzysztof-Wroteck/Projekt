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
$(function () {
  $('.shere').on('click', function (e) {
    e.preventDefault();
    var postId = $(this).data('id');
    var $button = $(this);
    $.ajax({
      method: 'POST',
      url: '/shere/' + postId,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (data) {
      if (data.action === 'shared') {
        $button.addClass('shared');
        $button.find('.fa').removeClass('fa-regular').addClass('fa-solid');
      } else {
        $button.removeClass('shared');
        $button.find('.fa').removeClass('fa-solid').addClass('fa-regular');
      }
      $button.find('.shere-count').text(data.sheresCount);
    }).fail(function (data) {
      console.error(data);
    });
  });
});
/******/ })()
;