jQuery( document ).ready(function( $ ){


    $('.lumber-form-fields-upload-remove-handle').on('click', function( e ){
        e.preventDefault();

        var $parent = $(this).parent('.lumber-form-fields-upload-container');

        $( '.lumber-form-fields-upload-attachment-id', $parent ).val( '' );
        $( '.lumber-form-fields-upload-image-container', $parent ).empty();
        // $( this ).hide();
    });


    $('.lumber-form-fields-media-upload-handle').on('click', function( e ){

        e.preventDefault();

        var lumber_alr_pro_upload_frame;
        var lumber_alr_pro_upload = {};
        var $parent = $(this).parent('.lumber-form-fields-upload-container');

        // If the frame already exists, open it.
        if ( lumber_alr_pro_upload_frame ) {
            lumber_alr_pro_upload_frame.open();
            return;
        }

        // Create the media frame.
        lumber_alr_pro_upload_frame = wp.media.frames.lumber_alr_pro_upload_frame = wp.media({

            // We can pass in a custom class name to our frame.
            className: 'media-frame lumber-form-fields-upload-extended-frame',

            // Frame type ('select' or 'post').
            frame: 'select',

            // Whether to allow multiple images
            multiple: false,

            // Custom frame title.
            title: lumber_alr_pro_upload.title,

            // Media type allowed.
            // library: {
            //     type: 'image'
            // },

            // Custom "insert" button.
            button: {
                text:  lumber_alr_pro_upload.button
            }

        });

        // Do stuff with the data when an image has been selected.
        lumber_alr_pro_upload_frame.on( 'select',

            function() {

                // Construct a JSON representation of the model.
                var media_attachment = lumber_alr_pro_upload_frame.state().get( 'selection' ).first().toJSON();

                $( '.lumber-form-fields-upload-attachment-id', $parent ).val( media_attachment.id );

                if ( $.inArray( media_attachment.mime, ["image/png", "image/jpg", "image/jpeg"] ) !== -1 ){
                    thumb = media_attachment.sizes.thumbnail.url;
                } else {
                    thumb = media_attachment.icon;
                }

                $( '.lumber-form-fields-upload-image-container', $parent ).empty();
                $( '.lumber-form-fields-upload-image-container', $parent ).append('<img src="'+thumb+'" style="border:1px solid #ddd;"/>');
                $( '.lumber-form-fields-upload-image-container', $parent ).show();

                $( '.lumber-form-fields-upload-remove-handle' ).show();
            }
        );

        // Open up the frame.
        lumber_alr_pro_upload_frame.open();

    });
});
