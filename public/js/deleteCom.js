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
/*!***********************************!*\
  !*** ./resources/js/deleteCom.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
$(document).ready(function () {
  $('.delete').on('click', function () {
    var postId = $(this).data('post-id');
    var commentId = $(this).data('comment-id');
    var itemType = $(this).data('type');
    var swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger"
      },
      buttonsStyling: false
    });
    var confirmationMessage = itemType === 'comment' ? 'Czy na pewno chcesz usunąć ten komentarz?' : 'Czy na pewno chcesz usunąć ten post?';
    swalWithBootstrapButtons.fire({
      title: confirmationMessage,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Tak",
      cancelButtonText: "Nie",
      reverseButtons: true
    }).then(function (result) {
      if (result.isConfirmed) {
        var url;
        if (itemType === 'comment') {
          url = "'/api/posts/".concat(postId, "/comments' + commentId");
        } else {
          url = "/api/posts/".concat(postId);
        }
        $.ajax({
          method: "DELETE",
          url: "/api/posts/".concat(postId, "/comments/").concat(commentId),
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }).done(function (data) {
          if (data.status === 'success') {
            window.location.reload();
          } else {
            Swal.fire("Error", "Wystąpił błąd podczas usuwania.", "error");
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