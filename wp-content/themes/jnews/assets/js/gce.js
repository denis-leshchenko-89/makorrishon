  (function($)

    {
      var cx = 'partner-pub-9315736241484204:5053112175';
      var gcse = document.createElement('script');
      gcse.type = 'text/javascript';
      gcse.async = true;
      gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(gcse, s);

      function getParameterByName(name, url) {
          if (!url) url = window.location.href;
          name = name.replace(/[\[\]]/g, "\\$&");
          var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
              results = regex.exec(url);
          if (!results) return null;
          if (!results[2]) return '';
          return decodeURIComponent(results[2].replace(/\+/g, " "));
      }

      $(document).ready(function(){
        $('[name="q"]').val(getParameterByName('q', window.location.href));
        if(getParameterByName('type', window.location.href) === 'google')  $('[name="type"][value="google"]').attr('checked', true);
      });
    }
  )(jQuery);
