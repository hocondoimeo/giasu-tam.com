$(window).load(function() {
	//Check to see if the window is top if not then display button
	var p = $( ".scrollToTop" );
	$(window).scroll(function(){
		toogleScrollToTop($(this).scrollTop());
	});
	var offset = p.offset();
	toogleScrollToTop(offset.top);
	changeSocialTooltip();	
	makeTwoDivSameHeight();
	showTooltipSmallScreen();
});
//ready for loading js 
$(document).ready(function() {
	showNewsInSmallViewPort();
	$('.scrollToTop').live('click', function(){
		$('html,body').animate({scrollTop: $("body").offset().top}, 1200);
	});
});
//when window is resized by customer
$(window).resize(function () {
    showNewsInSmallViewPort();
	changeSocialTooltip();
	makeTwoDivSameHeight();
	showTooltipSmallScreen();
});

jQuery(function(){    
	var menu = $("#menus");
	menu.find('ul.sf-menu').superfish({
        	delay: 800, 
        	speed: 'fast',
        	autoArrows:  true,
            dropShadows:   false
    });
    $("#menu-small-viewport, .my-close").live('click', function(e){
		$(".sf-menu").toggleClass("display-menu");
		$(".menu").toggle();
		$(".left-nav-triangle > i").toggleClass("icon-chevron-down icon-chevron-up");
		$(".right-nav-triangle > i").toggleClass("icon-chevron-down icon-chevron-up");        
		$(".my-close").hide();
    });
	menu.bind('scroll', function()
	  {
		if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
		{
		  $(".my-close").show();
		}
	  });
});

function makeTwoDivSameHeight(){
    maxHeight = 0;
    $("#home-page .content-block").css('min-height',maxHeight);
    $("#home-page .news-block").css('min-height',maxHeight);
    if ($('.menu-small-viewport').css('display') != 'block'){
       maxHeight = ($("#home-page .content-block").height() > $("#home-page .news-block").height()) ? $("#home-page .content-block").height(): $("#home-page .news-block").height();
       maxHeight +=20;
       $("#home-page .content-block").css('min-height',maxHeight);
       $("#home-page .news-block").css('min-height',maxHeight);
    }
}

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

function hideDeviceTablet(){
	var isInFrame = (window != top) ? true : false; // check page in frame or not
	 if (isMobile.any() || isInFrame){
		 $(".menu-device .tablet").remove();
	 }
}

function changeSocialTooltip(){
	var bodyWidth = $(window).width();
	if(bodyWidth <= 1200 && bodyWidth > 767){
		$("#social li").addClass('vertical');
		$("#social li").attr('style', 'margin-bottom:10px; margin-left: 5px !important');
		$('.social-facebook').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'right' }).tooltip('show');
		$('.social-googleplus').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'right' }).tooltip('show');
		$('.social-twitter').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'right' }).tooltip('show');
	}else{
		$("#social li").removeClass('vertical');	
		$("#social li").attr('style', 'margin-bottom:0px;margin-left: 10px !important');
		$('.social-facebook').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'bottom' }).tooltip('show');
		$('.social-googleplus').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'bottom' }).tooltip('show');
		$('.social-twitter').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'bottom' }).tooltip('show');
	}
}

function showTooltipSmallScreen(){
	var bodyWidth = $(window).width();
    if(bodyWidth <= 767){    	
		if(!$('.share-box #social').length){
			$('.share-box').append($('#social'));
			var topTooltip = $('.detail-content').scrollTop() + $(window).height() + $('.detail-content').innerHeight();
			$("#social li").removeClass('vertical');	
			$("#social li").attr('style', 'margin-bottom:0px;margin-left: 10px !important');
			$('.social-facebook').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'bottom' }).tooltip('show');
			$('.social-googleplus').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'bottom' }).tooltip('show');
			$('.social-twitter').tooltip('destroy').tooltip({ trigger: 'manual', placement : 'bottom' }).tooltip('show');
			if($(window).height() < 380){
				topTooltip += $(window).height()+10;
			}
			topTooltip -= 64;
			$('.social-facebook').next().attr('style', 'top:'+topTooltip+'px !important; left: 26px !important; width: 40px;');
			$('.social-googleplus').next().attr('style', 'top:'+topTooltip+'px !important; left: 76px !important; width: 40px;');
			$('.social-twitter').next().attr('style', 'top:'+topTooltip+'px !important; left: 126px !important; width: 40px;');
			$('.share-box').show();
		} 
	}else{
		$('.share-box').hide();
	} 
}

function showNewsInSmallViewPort(){
    var bodyWidth = $(window).width();
    if(bodyWidth <= 767){    	
        //hide all		
		jQuery('html').addClass('no-message');
    	//show small menu viewport
    	$(".menu-small-viewport").each(function(){
            $(this).show();
        });
		
		$("#myCarousel").carousel('pause');
    	
    	$("#myCarousel").swiperight(function() {  
    	      $("#myCarousel").carousel('prev');  
    	    });  
	   $("#myCarousel").swipeleft(function() {  
	      $("#myCarousel").carousel('next');  
	   }); 
	   
	   if($(".exelikebox").length && $(".exelikebox").hasClass('large-screen'))
		  $(".exelikebox").toggleClass('large-screen small-screen');
    }
    else{
		$('.carousel').carousel('cycle');
		
    	//hide small menu viewport
    	if($('#menu-small-viewport').css('display') == 'block'){
    		$("#menu-small-viewport").each(function(){
                $(this).hide();
            });  
    	}    	
        //show all
		jQuery('html').removeClass('no-message');
        if(!$('.lg-share-box #social').length) $('.lg-share-box').append($('#social'));
		if($(".exelikebox").length && $(".exelikebox").hasClass('small-screen')) 
			$(".exelikebox").toggleClass('large-screen small-screen');
    }
}

/**
 * @desc load javascript or css file
 * @param string filename: file name, string filetype: 'js' or 'css'
 * eg: loadJsOrCssFile("myscript.js", "js") //dynamically load and add this .js file
 * eg: loadJsOrCssFile("javascript.php", "js") //dynamically load "javascript.php" as a JavaScript file
 * eg: loadJsOrCssFile("mystyle.css", "css") ////dynamically load and add this .css file
 * @author duy.ngo
 */
function loadJsOrCssFile(filename, filetype){
    if (filetype=="js"){ //if filename is a external JavaScript file
        var fileref=document.createElement('script')
        fileref.setAttribute("type","text/javascript")
        fileref.setAttribute("src", filename)
    }
    else if (filetype=="css"){ //if filename is an external CSS file
        var fileref=document.createElement("link")
        fileref.setAttribute("rel", "stylesheet")
        fileref.setAttribute("type", "text/css")
        fileref.setAttribute("href", filename)
    }
    if (typeof fileref!="undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref)
}

/**
 * @desc load modal 
 * @param string content
 * @author duy.ngo
 */
function loadModal(content){
	var html = '<div id="loadModal" class="modal  hide fade modal-white" role="dialog" tabindex="-1">';
	html += '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true" title="close">x</button></div>';
	html += '<div class="modal-body"><div class="row-fluid"><center style="padding: 10px;" class="load-content">'+content+'</center></div></div>'; 
	html += '<div class="modal-footer"><button type="button" class="close" data-dismiss="modal" aria-hidden="true" title="close">x</button></div></div>';
	$('#loadModal').remove();
	$('body').append(html);
	$('#loadModal').one('show.bs.modal',  function (e) {
		$("#contentall").css("-webkit-filter", "blur(1px) grayscale(50%)");
		$("#contentall").css("-webkit-transform", "scale(1)");
    }).one('hide.bs.modal',  function (e) {
		$("#contentall").css("cssText", "transition:all 0.2s ease !important");
    }).modal('show');
}

function scrollToViewContent(){
	var bodyWidth = $(window).width();
    if(bodyWidth < 768){
    	var offset = $(".menu").offset().top + 3*$(".content-top").offset().top;
    if(offset < $(window).height())
    	offset += $(window).height()-(offset+$(".menu").offset().top+40);
    	
    	$('html, body').animate({scrollTop:offset}, 1200);
    }else{
    	var aTag = $("#home-page");
        $('html, body').animate({scrollTop: aTag.offset().top}, 1200);
    }
}

function toogleScrollToTop(offset){
	if (offset > 300) {
		$('.scrollToTop').fadeIn();
	} else {
		$('.scrollToTop').fadeOut();
	}
}