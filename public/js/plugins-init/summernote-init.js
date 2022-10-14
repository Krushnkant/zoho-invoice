jQuery(document).ready(function() {
    $(".summernote").summernote({
        toolbar: [
         
            ['view', ['fullscreen']]
          ],
        height: 350,
        minHeight: null,
        maxHeight: null,
        toolbar: false,
        focus: !1
    }), $(".inline-editor").summernote({
        airMode: true
    })
}), window.edit = function() {
    $(".click2edit").summernote()
}, window.save = function() {
    $(".click2edit").summernote("destroy")
};