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
/*!*********************************!*\
  !*** ./resources/js/likeCom.js ***!
  \*********************************/
__webpack_require__.r(__webpack_exports__);
$(document).ready(function () {
  $('.like').on('click', function (event) {
    event.preventDefault();
    var commentId = $(this).closest('.like-form').data('comment-id');
    var post = $(this).closest('.like-form').data('post-id');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger"
      },
      buttonsStyling: false
    });
    swalWithBootstrapButtons.fire({
      title: "Are you sure you want to like/dislike this comment?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Yes, like/dislike this comment",
      cancelButtonText: "No",
      customClass: {
        confirmButton: 'btn btn-success styled-button',
        cancelButton: 'btn btn-danger styled-button'
      },
      reverseButtons: true
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          method: "POST",
          url: "/api/posts/".concat(post, "/comments/").concat(commentId, "/like"),
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
        }).done(function (data) {
          if (data.status === 'success') {
            swalWithBootstrapButtons.fire('success!', 'this comment has been liked/unliked.', 'success').then(function () {
              location.reload();
            });
          } else {
            Swal.fire("Error", "An error occurred while liking/unliking.", "error");
          }
        }).fail(function (data) {
          Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
        });
      }
    });
  });
});
/******/ })()
;