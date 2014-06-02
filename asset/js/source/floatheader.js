

function updateHeaders() {
  $('.fancytable').each(function() {
    var jqObj = $(this);
    var offset = jqObj.offset();
    var scrollTop = $(window).scrollTop();
    var floatingHeader = $('.keepheader', this);

    if ((scrollTop > offset.top) && (scrollTop < offset.top + jqObj.height())) {
      if (!floatingHeader.hasClass('floatheader')) {
        var width = floatingHeader.css('width');
        //var width = floatingHeader.outerWidth();
        var widtharr = [];
        floatingHeader.find('th').each(function() {
          widtharr.push($(this).css('width'));
        });
        floatingHeader.addClass('floatheader');
        floatingHeader.css('width', width);
        var secondrow = floatingHeader.next();
        var idx = 0;
        floatingHeader.find('th').each(function() {
          var targetWidth = widtharr[idx++];

          $(this).css('width', (parseInt(targetWidth.replace('px', ''))+1)+'px');
          var headerIndex = $(this).index();

          if (secondrow.length > 0) {
            secondrow.find('td:nth-child(' + (headerIndex + 1) + ')').css(
                'width', targetWidth);
          }
        });
        var tdidx = 0;
      }

      floatingHeader.css({
        'visibility': 'visible'
      });

    } else if (scrollTop < offset.top) {
      if (floatingHeader.hasClass('floatheader')) {
        floatingHeader.removeClass('floatheader');
      }
      floatingHeader.css({
        'visibility': 'visible'
      });
    } else {
      floatingHeader.css({
        'visibility': 'hidden'
      });
    }
  });
}


$(function() {
  $('.fancytable tr:nth-child(1)').addClass('keepheader');
  $(window)
    .scroll(updateHeaders)
    .trigger('scroll');
});
