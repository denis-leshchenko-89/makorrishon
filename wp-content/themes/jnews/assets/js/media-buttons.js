/**
 * @media-buttons.js
 *
 * this script contains the functionality of the add video modal
 */
jQuery(function($) {

    $(document).ready(function(){

            /**
             * close the modal event
             */
            $('#ih-admin-modal-backdrop, #ih-admin-modal-close, #ih-admin-modal-cancel').click(function(e){
                $('#ih-admin-modal-backdrop').hide();
                $('#ih-admin-modal-wrap').hide();
                e.preventDefault();
                e.stopPropagation();
            });

            /**
             * submit form event
             */
            $('#ih-insert-form').click(function(e) {

                function validateFields() {
                    if(!videoId) {
                        videoIdInput.addClass('invalid');
                    } else {
                        videoIdInput.removeClass('invalid');
                    }
                    if(!videoType) {
                        videoTypeInput.addClass('invalid');
                    } else {
                        videoTypeInput.removeClass('invalid');
                    }
                    return !(!videoId || !videoType);
                }
                // get the fields data
                var videoIdInput = $('#ih-admin-modal-video-id');
                var videoTypeInput = $('#ih-admin-modal-video-type');
                var videoCreditInput = $('#ih-admin-modal-video-credit');
                var videoId = videoIdInput.val();
                var videoType = videoTypeInput.val();
                var videoCredit = videoCreditInput.val();
                var videoCreditText = videoCredit.length ? 'credit="' + videoCredit + '"': '';
                // validate the fields
                if(!validateFields()) {
                    return;
                }
                // insert the ih-video special tag with the video credentials and resetting the form
                wp.media.editor.insert('[ih-video type="' + videoType + '" id="' + videoId + '" ' + videoCreditText + ']');
                $('#ih-admin-modal-backdrop').hide();
                $('#ih-admin-modal-wrap').hide();
                videoIdInput.val('');
                e.preventDefault();
                e.stopPropagation();
            });


        /**
         * open modal event
         */
        $('.insert-brightcove').click(function(e){
            e.preventDefault();
            $('#ih-admin-modal-backdrop').show();
            $('#ih-admin-modal-wrap').show();
        });

    });

});