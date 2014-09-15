
jQuery(function(){    
    jQuery('ul.sf-menu').superfish({
        delay:0
    });
    /*
     * @author thu.nguyen
     * @desc hide sub menu when click on main menu
     * */
    $(".menu-device").bind('click',function() {
        if ($(this).hasClass('sfHover')){
           $(this).hideSuperfishUl();
        }else{
           $(this).showSuperfishUl();
        }
    });

    //    $('.sf-sub-indicator').parent().next('ul').find('li:last-child').corner('bottom');
    //    $('div[class^=pitch]').corner();
    // active the top menu
    if (typeof activeMenu != "undefined") 
    	activeMenuFunction(activeMenu);
    // active the sub menu
    if (typeof activeSubMenu != "undefined")
    	activeMenuFunction(activeSubMenu);
    
    /**show menu small**/    
    $(".dark-yellow-menu.news").children().eq(0).addClass("border-bottom-menu-small");
  
    $(".menu-small-viewport").click(function(){
        if($(".sf-menu").hasClass("display-menu")){
            $(".sf-menu").removeClass("display-menu");
        	$(".menu").each(function(){
                $(this).show();
            });            
        }else{
            $(".sf-menu").addClass("display-menu");
        	$(".menu").each(function(){
                $(this).hide();
            });
        }
        
        if(!$(".left-nav-triangle").hasClass("collapsed")){
            $(".left-nav-triangle").removeClass("expanded");
            $(".right-nav-triangle").removeClass("expanded");
            $(".left-nav-triangle").addClass("collapsed");
            $(".right-nav-triangle").addClass("collapsed");
        }else{
            $(".left-nav-triangle").removeClass("collapsed");
            $(".right-nav-triangle").removeClass("collapsed");
            $(".left-nav-triangle").addClass("expanded");
            $(".right-nav-triangle").addClass("expanded");
        }
        
    });    
});

function activeMenuFunction(activeMenu) {
    var menu = $('.navigation li li.' + activeMenu);
    if (menu.size() > 0) {
        menu.addClass('active');
        menu.parent().parent().addClass('active');
    } else {
        var menu = $('.navigation li.' + activeMenu).addClass('active');
    }
}
var heightDocument = 0;

$(document).ready(function(){      
    heightDocument = $(document).height();
    resizeHeight(0);
    $(window).resize(function () {       
        resizeHeight(1);
    });
    hideDeviceTablet();
});

function resizeHeight(resize){
    if (isMobile.any()){
        return false;
    }
    heightBrowser = $(window).height();
    bannerHeight = $(".banner").height();
    contentHeight = $(".content").height();
    metricHeight = $(".metrics").height();
    footerHeight = $(".footer").height();
    peopleBanner = $(".people-header").height();
    temp = 0;
    if(heightBrowser == heightDocument && resize == 0){  
        $(".content").css("min-height", heightDocument - ( temp + peopleBanner + bannerHeight + metricHeight + footerHeight));
        $(".people-content .container").css("min-height", heightBrowser - ( temp + peopleBanner + bannerHeight + metricHeight + footerHeight));
    }
    if(resize == 1){
        $(".content").css("min-height", heightBrowser - ( temp + peopleBanner + bannerHeight + metricHeight + footerHeight));
        $(".people-content .container").css("min-height", heightBrowser - ( temp + peopleBanner + bannerHeight + metricHeight + footerHeight));
    }
    
}
var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

function hideDeviceTablet(){
	var isInFrame = (window != top) ? true : false; // check page in frame or not
	 if (isMobile.any() || isInFrame){
		 $(".menu-device .tablet").remove();
	 }
}