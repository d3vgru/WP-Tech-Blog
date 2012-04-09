(function($){

    $(document).ready(function(){
        $('.media-title').before('<h2>Add Images from your computer</h2>').before('Select images you want to add to your project. When uploading is fisnished, you can insert them into you project media panel. <br /><br />');

        /**
         * Thanks Rutger Laurman (lekkerduidelijk) for pointing out the uploader events
         */
        uploader.bind('UploadComplete', addImportButton);
    });

    function addImportButton() {
        $('.savebutton input[type="submit"]').after("<input type=\"button\" value=\"Add to Project\" class=\"button tagadd\" id=\"add_to_portfolio\" >");
        $('#add_to_portfolio').hide().fadeIn('slow');

        var btn = $('#add_to_portfolio').click(add_items_to_portfolio);
    }

    function add_items_to_portfolio() {
    	// construct images array..
    	var images = [];
    	jQuery('.media-item input[type=hidden][id*=type-of-]').each(function(index, value){
    		images.push(/[\d]+$/.exec(jQuery(value).attr('id')));
    	});

    	var win = window.dialogArguments || opener || parent || top;
    	win.send_media_to_metabox(images);
    }

})(jQuery);