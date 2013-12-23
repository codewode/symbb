(function($) {

    var BBCodeEditor = function(element)
    {
        var elem    = $(element);
        var obj     = this;
        var area    = $(element).find('.symbb_bbcode_editor_textarea');
        $(element).find('.symbb_bbcode_code').each(function(index, button) {
            $(button).click(function() {
                button      = $(button);
                var start   = button.data('tag');
                var end     = start;
                start       = '['+start+']';
                end         = '[/'+end+']';
                obj.insertCode(start, end, area);
            });
        });

        this.insertCode = function(start, end, element) {
            element = element[0];
            if (document.selection) {
                element.focus();
                sel = document.selection.createRange();
                sel.text = start + sel.text + end;
            } else if (element.selectionStart || element.selectionStart == '0') {
                element.focus();
                var startPos = element.selectionStart;
                var endPos = element.selectionEnd;
                element.value = element.value.substring(0, startPos) + start + element.value.substring(startPos, endPos) + end + element.value.substring(endPos, element.value.length);
            } else {
                element.value += start + end;
            }
        }
    };

    $.fn.bbcodeEditor = function()
    {
        return this.each(function()
        {
            var element = $(this);

            // Return early if this element already has a plugin instance
            if (element.data('bbcodeEditor'))
                return;

            var myplugin = new BBCodeEditor(this);

            // Store plugin object in this element's data
            element.data('bbcodeEditor', myplugin);
        });
    };

})(jQuery);


$(document).ready(function() {
    $('#symbb_bbcode_editor').bbcodeEditor();
});