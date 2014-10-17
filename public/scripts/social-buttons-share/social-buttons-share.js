(function($) {
  "use strict";
  $.fn.socialButtonsShare = function(customOptions, customSocialNetworks) {
    var that = this;
    var options = customOptions || {};
    var socialNetworks = customSocialNetworks || {};
  
    that.options = {
      socialNetworks: ["facebook", "twitter", "googleplus"],
      url: "",
      text: "",
      sharelabel: true,
      counter: true,
      sharelabelText: "SHARE",
      verticalAlign: false
    };
    $.extend(that.options, options);
    if (that.options.url==="" && $("link[rel=canonical]").length) {
      that.options.url = $("link[rel=canonical]").attr('href');
    } else if (that.options.url==="" && !$("link[rel=canonical]").length && window.location.href!==undefined) {
      that.options.url = window.location.href;
    }
    if (that.options.text==="" && document.title!=="") {
      that.options.text = document.title;
    }
    that.options.url = encodeURIComponent(that.options.url);
    that.options.text = encodeURIComponent(that.options.text);
  
    that.socialNetworks = {
      facebook: {
        title: "Share on Facebook",
        cssclass: "social-facebook",
        shareurl: "https://www.facebook.com/sharer/sharer.php?u=" + that.options.url,
        height: 600,
        width: 600
      },
      twitter: {
        title: "Share on Twitter",
        cssclass: "social-twitter",
        shareurl: "http://twitter.com/share?text=" + that.options.text + "&url=" + that.options.url,
        height: 600,
        width: 600
      },
      googleplus: {
        title: "Share on Google+",
        cssclass: "social-googleplus",
        shareurl: "https://plus.google.com/share?url=" + that.options.url,
        height: 600,
        width: 600
      }
    };
    $.each(that.socialNetworks, function(index, value) {
      if (socialNetworks.hasOwnProperty(index)) {
        $.extend(that.socialNetworks[index], socialNetworks[index]);
      }
    });

    if (that.options.socialNetworks.length>0) {
      var container = $(that);
      var verticalAlign = that.options.verticalAlign;

      if (that.options.sharelabel) {
        var classVerticalLabel = (verticalAlign) ? " class=\"vertical vertical-label\"" : "";
        var htmlLabel = "<label" + classVerticalLabel + ">" + that.options.sharelabelText + "</label>";
        container.append(htmlLabel);
      }

      var classVertical = (verticalAlign) ? " class=\"vertical vertical-ul\"" : "";
      var htmlUl = "<ul" + classVertical + "></ul><div></div>";
      container.append(htmlUl);

      $.each(that.options.socialNetworks, function(index, value) {
        if (that.socialNetworks.hasOwnProperty(value)) {
          var classVertical = (verticalAlign) ? " vertical" : "";

          var liShare = "<li data-toggle='tooltip' class=\"" + that.socialNetworks[value].cssclass + classVertical + "\" title=\"" + that.socialNetworks[value].title + "\" data-original-title='' ></li>";

          var shareHeight = that.socialNetworks[value].height;
          var shareWidth = that.socialNetworks[value].width;
          var shareTop = ((screen.height/2) - (that.socialNetworks[value].height/2));
          var shareLeft = ((screen.width/2)-(that.socialNetworks[value].width/2))

          $(liShare).appendTo(container.find("ul")).on("click", function() {
            window.open(that.socialNetworks[value].shareurl, '', 'menubar=no,toolbar=no,resizeable=no,scrollbars=no,height=' + shareHeight + ',width=' + shareWidth + ',top=' + shareTop + ',left=' + shareLeft);
          });
        }
      });  
      
      if(that.options.counter){
    	  var url = '/index/ajax-get-social-count';
          $.ajax({
              url : url,
              method: 'get',
              dataType: 'json',	
              success : function(data){
                  var facebook = ''; var googleplus = ''; var twitter = '';
                  
            	  facebook = $.trim(data.facebook) != '' ? data.facebook : '0';
                  $('.social-facebook').tooltip({trigger: 'manual', placement: 'bottom'}).tooltip('hide')
					  .attr('data-original-title', facebook).tooltip('show');
					  
                  googleplus = $.trim(data.googleplus) != '' ? data.googleplus : '0';
				  $('.social-googleplus').tooltip({trigger: 'manual', placement: 'bottom'}).tooltip('hide')
				  .attr('data-original-title', googleplus).tooltip('show');
				  
                  twitter = $.trim(data.twitter) != '' ? data.twitter : '0';
				  $('.social-twitter').tooltip({trigger: 'manual', placement: 'bottom'}).tooltip('hide')
				  .attr('data-original-title', twitter).tooltip('show');
              }
          });
      }
    }
  };
})(jQuery);
