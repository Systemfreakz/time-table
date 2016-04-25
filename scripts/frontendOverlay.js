function TTOverlayBox(triggerSelector, content) {

    var self = this;
    var boxHtml;
    var box;
    var opened = false;

    function init() {
        if (ttOverlayBoxChoose) {
            jQuery(triggerSelector).colorbox({html: content, className: 'tt-overlay-colorbox'});
        } else {
            boxHtml = '<div class="tt-overlay-box"><a href="#" class="overlay-box-close">X</a>';
            boxHtml += '<div class="overlay-box-wrapper"';
            boxHtml += 'style="background-image: url(' + ttOverlayBoxBackgroundImage + ');';
            boxHtml += 'background-color: ' + ttOverlayBoxBackgroundColor + ';">';
            boxHtml += '<div class="overlay-box-content">'
            boxHtml += content;
            boxHtml += '</div></div></div>';
            jQuery(window).ready(function() {
                jQuery(triggerSelector).click(self.open);
                jQuery(document).click(function(e) {
                    if (opened && !box.children('.overlay-box-wrapper').is(e.target)
                            && box.children('.overlay-box-wrapper').has(e.target).length === 0) {
                        self.close();
                    }
                });
            });
        }
    }

    this.open = function() {
        opened = true;
        jQuery('body').append(boxHtml);
        box = jQuery('.tt-overlay-box');
        jQuery('.overlay-box-close').click(self.close);
        box.css('display', 'block');
        return false;
    };

    this.close = function() {
        box.remove();
        return false;
    };

    init();

}