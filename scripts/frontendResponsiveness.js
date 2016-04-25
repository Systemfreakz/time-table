function timetableHandleWindowSize() {
    var timetables = jQuery(".tt-frontend");
    timetables.each(function () {
        var $timetable = jQuery(this);
        if ($timetable.width() < 500) {
            $timetable.addClass("mobile");
        }else {
            $timetable.removeClass("mobile");
        }
    });
}

jQuery(window).resize(timetableHandleWindowSize);

var TimeTable = function($tt) {

    var self = this;

    var $slideLeftButton = $tt.find(".tt-timetable-slide-left");
    var $slideRightButton = $tt.find(".tt-timetable-slide-right");
    var $slideWrapper = $tt.find(".tt-timetable-columns-slider");
    var slidePage = 0;
    var columnCount = $tt.find(".tt-timetable-days-header tr").children().length;
    var maxPage = columnCount - 3;

    this.init = function() {
        $slideLeftButton.click(self.slideLeft);
        $slideRightButton.click(self.slideRight);
    };

    this.slideLeft = function() {
        if (slidePage > 0) {
            slidePage --;
            self.slideToPage(slidePage);
        }
        return false;
    };

    this.slideRight = function() {
        if (slidePage < maxPage) {
            slidePage ++;
            self.slideToPage(slidePage);
        }
        return false;
    };

    this.slideToPage = function(page) {
        $slideWrapper.animate({left: -page * ((100 / 3)) + "%"}, 500);
    };


    this.init();
};

jQuery(window).ready(function() {
   jQuery(".tt-frontend").each(function() {
       new TimeTable(jQuery(this));
   });
});