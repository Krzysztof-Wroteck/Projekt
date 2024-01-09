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
/*!********************************!*\
  !*** ./resources/js/delete.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
$(document).ready(function () {
  $('.delete').on('click', function () {
    var postId = $(this).data('id');
    var swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger"
      },
      buttonsStyling: false
    });
    swalWithBootstrapButtons.fire({
      title: "Jesteś pewny, że chcesz usunąć ten post?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Tak",
      cancelButtonText: "Nie",
      reverseButtons: true
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          method: "DELETE",
          url: 'http://127.0.0.1:8000/posts/list/' + postId,
          headers: {
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