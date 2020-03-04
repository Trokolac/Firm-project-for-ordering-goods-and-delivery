function hideAlerts() {
    $('.alert').slideUp();
  }
  
  setTimeout(hideAlerts, 2000);
      
  jQuery(document).ready(function($) {
    var alterClass = function() {
    var ww = document.body.clientWidth;
      if (ww < 768) {
        $('.collapse').removeClass('show');
        $('.btn-sm').addClass('mt-2');
      } else if (ww >= 768) {
        $('.collapse').addClass('show');
        $('.btn-sm').removeClass('mt-2');
      };
    };
    $(window).resize(function(){
      alterClass();
    });
    //Fire it when the page first loads:
      alterClass();
    });

    jQuery(document).ready(function($) {
      var alterClass = function() {
      var ww = document.body.clientWidth;
        if (ww <= 360) {
          $('.btn-sm').addClass('mt-2');
        } else if (ww >= 360) {
          $('.btn-sm').removeClass('mt-2');
        };
      };
      $(window).resize(function(){
        alterClass();
      });
      //Fire it when the page first loads:
        alterClass();
      });