jQuery( document ).ready(function( $ ){


    $('.zm-form-fields-upload-remove-handle').on('click', function( e ){
        e.preventDefault();

        var $parent = $(this).parent('.zm-form-fields-upload-container');

        $( '.zm-form-fields-upload-attachment-id', $parent ).val( '' );
        $( '.zm-form-fields-upload-image-container', $parent ).empty();
        // $( this ).hide();
    });


    $('.zm-form-fields-media-upload-handle').on('click', function( e ){

        e.preventDefault();

        var zm_alr_pro_upload_frame;
        var zm_alr_pro_upload = {};
        var $parent = $(this).parent('.zm-form-fields-upload-container');

        // If the frame already exists, open it.
        if ( zm_alr_pro_upload_frame ) {
            zm_alr_pro_upload_frame.open();
            return;
        }

        // Create the media frame.
        zm_alr_pro_upload_frame = wp.media.frames.zm_alr_pro_upload_frame = wp.media({

            // We can pass in a custom class name to our frame.
            className: 'media-frame zm-form-fields-upload-extended-frame',

            // Frame type ('select' or 'post').
            frame: 'select',

            // Whether to allow multiple images
            multiple: false,

            // Custom frame title.
            title: zm_alr_pro_upload.title,

            // Media type allowed.
            library: {
                type: 'image'
            },

            // Custom "insert" button.
            button: {
                text:  zm_alr_pro_upload.button
            }

        });

        // Do stuff with the data when an image has been selected.
        zm_alr_pro_upload_frame.on( 'select',

            function() {

                // Construct a JSON representation of the model.
                var media_attachment = zm_alr_pro_upload_frame.state().get( 'selection' ).first().toJSON();

                $( '.zm-form-fields-upload-attachment-id', $parent ).val( media_attachment.id );

                console.log( media_attachment );
                console.log( '<img src="'+media_attachment.sizes.thumbnail.url+'" />' );

                $( '.zm-form-fields-upload-image-container', $parent ).empty();
                $( '.zm-form-fields-upload-image-container', $parent ).append('<img src="'+media_attachment.sizes.thumbnail.url+'" style="border:1px solid #ddd;"/>');
                $( '.zm-form-fields-upload-image-container', $parent ).show();

                $( '.zm-form-fields-upload-remove-handle' ).show();
            }
        );

        // Open up the frame.
        zm_alr_pro_upload_frame.open();

    });
});
