
$(document).ready(function() {
     resizeWindow();
     $('body').waitForImages(function() {
         makeTwoDivSameHeight();
     });
     var isPopularNew = false;
     var currentCat = $("#news-categories-dropdown").val();
     $('#news-categories-dropdown').change(function(){
         var link = $(this).val();
         if(link != 'Popular News')
             location.href = link;
         else{
             showPopularNew();
             isPopularNew = true;
         }
     });
     //when window is resized by customer
     $(window).resize(function () {
         resizeWindow();
         makeTwoDivSameHeight();
     });
     /*
     * @author thu.nguyen
     * @return void 
     * */
     function showPopularNew(){
         $(".content-news").hide();
         $(".author-news").hide();
         $(".all-popular-block").show();
     }
     /*
      * @author thu.nguyen
      * @return void 
      * */
     function hidePopularNewSmallViewPort(){
         $(".content-news").show();
         $(".author-news").show();
         $(".all-popular-block").hide();
     }
     function resizeWindow(){
            // small view port
            if ($('.menu-small-viewport').css('display') == 'block'){
            	if($('div').hasClass('list-category-news')){
                    if($(".content").css('padding-top') == '20px')
                        $(".content").attr('style', 'padding-top: 10px !important');
                    if($.browser.msie && $.browser.version == '8.0'){ 
                        $(".content-news").attr('style', 'margin-top: 10px !important');
                    }
                }

                if (isPopularNew == true){
                    showPopularNew();
                 }else{
                    hidePopularNewSmallViewPort();
                 }
                
            }else{
            	if(!$('div').hasClass('list-category-news'))
                    $(".content").attr('style', 'padding-top: 20px !important');
                else
                    if($.browser.msie && $.browser.version == '8.0' && $(".content-news").css('margin-top') != '0px') 
                        $(".content-news").attr('style', 'margin-top: 0px !important');
                showNew();
                
            }
        }
     function showNew(){
            $(".content-news").show();
            $(".author-news").show();
            $(".all-popular-block").show();
        }
     /*
      * @author thu.nguyen
      * @return void 
      * */
     function makeTwoDivSameHeight(){
         // if document shorter than window
         $('.content').css('min-height',0);
         var isInFrame = (window != top) ? true : false;
         heightDocument = $(document).height();
         heightWindow = $(window).height();
         if (heightDocument == heightWindow && !isInFrame){
             minHeight = heightWindow - $('.fake-banner').height() - $('.list-category-news').height()- $('.footer').height()-40;
             $('.content-news').css('min-height', minHeight);
             $('.all-popular-block').css('min-height', minHeight - $('.author-news').outerHeight() - 40);
             return false;
         }
         if ($('.menu-small-viewport').css('display') != 'block'){ console.log('min');
             // if window have normal resolution
             $('.all-popular-block').css('min-height', 0 );
             $('.content-news').css('min-height', 0);
             col_right = $('.content-news').height();
             col_left = $('.author-news').outerHeight() + $('.all-popular-block').outerHeight() + 20;
             if (col_right > col_left){
                 height = col_right - $('.author-news').outerHeight() - 20;
                 $('.all-popular-block').css('min-height', height );
             }else{
                 $('.content-news').css('min-height', col_left);
             }
         }else{
             $('.all-popular-block').css('min-height', 0 );
             $('.content-news').css('min-height', 0);
         }
     }
});