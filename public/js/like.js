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
/*!******************************!*\
  !*** ./resources/js/like.js ***!
  \******************************/
__webpack_require__.r(__webpack_exports__);
$(document).ready(function () {
  $('.like-form').on('submit', function (e) {
    e.preventDefault();
    var form = $(this);
    var postId = form.data('post-id');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      method: "POST",
      url: '/api/posts/like/' + postId,
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      }
    }).done(function (data) {
      if (data.status === 'success') {
        var likesCountElement = form.find('.likes-count');
        var currentLikesCount = parseInt(likesCountElement.text().split(' ')[0]);
        likesCountElement.text(currentLikesCount + 1 + ' likes');
      } else {
        Swal.fire("Error", "Wystąpił błąd podczas dodawania polubienia.", "error");
      }
    }).fail(function (data) {
      Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
    });
  });
});
/******/ })()
;