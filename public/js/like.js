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
  $('.like-form').on('submit', function (event) {
    event.preventDefault();
    var form = $(this);
    var postId = form.data('post-id');
    var likeButton = form.find('.like-button');
    $.ajax({
      method: 'POST',
      url: '/posts/list/' + postId,
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (data) {
      if (data.status === 'success') {
        var likesCountElement = likeButton.find('.likes-count');
        if (likesCountElement) {
          likesCountElement.text(data.likesCount + ' likes');
        }
      } else {
        alert("Wystąpił błąd podczas polubienia/odlubienia.");
      }
    }).fail(function (error) {
      alert("Wystąpił błąd podczas polubienia/odlubienia.");
    }).always(function () {
      location.reload();
    });
  });
});
/******/ })()
;