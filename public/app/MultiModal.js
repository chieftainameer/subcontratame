// // PLUGINS MULTIMODAL
    
// var MultiModal = function(element) {
//     this.$element = $(element);
//     this.modalCount = 0;
// };

// MultiModal.BASE_ZINDEX = 1040;

// MultiModal.prototype.show = function(target) {
//     var that = this;
//     var $target = $(target);
//     var modalIndex = that.modalCount++;

//     $target.css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20) + 10);

//     // Bootstrap triggers the show event at the beginning of the show function and before
//     // the modal backdrop element has been created. The timeout here allows the modal
//     // show function to complete, after which the modal backdrop will have been created
//     // and appended to the DOM.
//     window.setTimeout(function() {
//         // we only want one backdrop; hide any extras
//         if(modalIndex > 0)
//             $('.modal-backdrop').not(':first').addClass('hidden');

//         that.adjustBackdrop();
//     });
// };

// MultiModal.prototype.hidden = function(target) {
//     this.modalCount--;

//     if(this.modalCount) {
//        this.adjustBackdrop();

//         // bootstrap removes the modal-open class when a modal is closed; add it back
//         $('body').addClass('modal-open');
//     }
// };

// MultiModal.prototype.adjustBackdrop = function() {
//     var modalIndex = this.modalCount - 1;
//     $('.modal-backdrop:first').css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20));
// };

// function Plugin(method, target) {
//     return this.each(function() {
//         var $this = $(this);
//         var data = $this.data('multi-modal-plugin');

//         if(!data)
//             $this.data('multi-modal-plugin', (data = new MultiModal(this)));

//         if(method)
//             data[method](target);
//     });
// }

// $.fn.multiModal = Plugin;
// $.fn.multiModal.Constructor = MultiModal;

// $(document).on('show.bs.modal', function(e) {
//     $(document).multiModal('show', e.target);
// });

// $(document).on('hidden.bs.modal', function(e) {
//     $(document).multiModal('hidden', e.target);
// });



// (function($, window) {
//     'use strict';

    var MultiModal = function(element) {
        this.$element = $(element);
        this.modalCount = 0;
    };

    MultiModal.BASE_ZINDEX = 1040;

    MultiModal.prototype.show = function(target) {
        var that = this;
        var $target = $(target);
        var modalIndex = that.modalCount++;

        $target.css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20) + 10);

        // Bootstrap triggers the show event at the beginning of the show function and before
        // the modal backdrop element has been created. The timeout here allows the modal
        // show function to complete, after which the modal backdrop will have been created
        // and appended to the DOM.
        window.setTimeout(function() {
            // we only want one backdrop; hide any extras
            if(modalIndex > 0)
                //$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
                $('.modal-backdrop').not(':first').addClass('hidden');

            that.adjustBackdrop();
        });
    };

    MultiModal.prototype.hidden = function(target) {
        this.modalCount--;

        if(this.modalCount) {
           this.adjustBackdrop();

            // bootstrap removes the modal-open class when a modal is closed; add it back
            $('body').addClass('modal-open');
        }
    };

    MultiModal.prototype.adjustBackdrop = function() {
        var modalIndex = this.modalCount - 1;
        $('.modal-backdrop:first').css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20));
    };

    function Plugins(method, target) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('multi-modal-plugin');

            if(!data){
                $this.data('multi-modal-plugin', (data = new MultiModal(this)));
            }
            
            if(method){
                data[method](target);
            }
                
        });
    }

    $.fn.multiModal = Plugins;
    $.fn.multiModal.Constructor = MultiModal;

    $(document).on('show.bs.modal', function(e) {
        $(document).multiModal('show', e.target);
    });

    $(document).on('hidden.bs.modal', function(e) {
        $(document).multiModal('hidden', e.target);
    });
// //}(jQuery, window));



// $(function() {
//     var modal = new MultiModal({
//       title: 'Multi Modal',
//       fullScreen: true
//     });
  
//     $(document).click(function(event) {
//       if ($(event.target).hasClass('js-modal-close')) {
//         modal.close();
//       }
  
//       if ($(event.target).hasClass('js-modal-close-all')) {
//         modal.closeAll();
//       }
  
//       if ($(event.target).hasClass('js-toggle-modal')) {
//         modal.new({
//           content: 'Do you want to see the action?',
//           buttons: {
//             primary: {
//               value: 'Yes!',
//               className: 'button button--primary js-another-modal'
//             },
//             secondary: {
//               value: 'No',
//               className: 'button button--secondary',
//               closeOnClick: true
//             }
//           }
//         });
//       } else if ($(event.target).hasClass('js-another-modal')) {
//         modal.new({
//           content: 'Just keep clicking',
//           buttons: {
//             primary: {
//               value: 'Okay',
//               className: 'button button--primary js-modalception'
//             }
//           }
//         });
//       } else if ($(event.target).hasClass('js-modalception')) {
//         modal.new({
//           title: 'Modalception!',
//           content: 'Find out what happens in the end',
//           buttons: {
//             primary: {
//               value: 'Find out!',
//               className: 'button button--primary js-modalception'
//             },
//             secondary: {
//               value: 'Close all',
//               className: 'button button--secondary js-modal-close-all'
//             }
//           }
//         });
//       }
//     });
//   });

