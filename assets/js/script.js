let postContent = document.querySelector( '#post_content' );

if( postContent ) {
    tinymce.init({
        selector: '#post_content',
        toolbar: 'undo redo | bold italic underline | numlist bullist |  fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen | link anchor'
    });
}

let mainContent = document.querySelector( '#main_content' );

if( mainContent ) {
    tinymce.init({
        selector: '#main_content',
        toolbar: 'undo redo | bold italic underline | numlist bullist |  fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen | link anchor'
    });
}
