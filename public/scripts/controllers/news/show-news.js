$(document).ready(function() {    
    $('.read-more-news').live('click', function(){

        var cate = $('div.paginationControl').attr('cate-id');
        var currentpage = $('div.paginationControl').attr('current-page');
        currentpage = parseInt(currentpage);
        var url = '/news/ajax-get-lastest-news/page/'+(currentpage + 1)+cate;
        $(".loadmorenews").hide();

        $('.lastest-news-content').append('<div class="loading-news"><img src="/images/preloading.gif"/><div>');
        $.ajax({
            url : url,
            method: 'get',
            success : function(data){
                $(".loading-news").hide();
                var dataCustom = "<div class='more-news-append'>"+data+"</div>";
                $('.lastest-news-content').append(dataCustom);
                currentpage++;
                $('div.paginationControl').attr('current-page',currentpage);
            }
        });

    });

});


function loadPage(url, page){
    $('.lastest-news-content').html('<img src="/images/preloading.gif"/>');

    $.ajax({
        url : url,
        method: 'get',
        success : function(data){
            $('.lastest-news-content').html(data);
            if(!$('div.lastest-news-content').hasClass('active-read-more-news'))
                $('div.lastest-news-content').addClass('active-read-more-news');
            makeTwoDivSameHeight();

            if(typeof page != 'undefined' && $.isNumeric(page)){
                var currentPage = page;
                $('div.paginationControl').attr('current-page',currentPage);
            }
        }
    });
}

function showLastestNewsBlock(){ 
    $('h6.first-lastest-news').removeClass('small-vertical-seperator');
    $('h6.first-lastest-news').text('');
    $('#lastest-news-btn-back').show();
    $('.only-popular').hide();
    $('.load-more-news').show();
}

function resizeWindow(){
    var bodyWidth = $(window).width();
    // small view port
    if ($('.menu-small-viewport').css('display') == 'block'){
        //show all news have append in small viewport
        $(".more-news-append").show();

        $('.all-popular-block').hide();
        $('div.medium-image').find('div.medium-news').each(function(){
            $(this).removeClass('span6');
            $('div.medium-image').next().removeClass('span4');
        });
        if($.trim($('#news-categories-dropdown').val()) == 'Popular News'){
            $('.only-popular').hide();
            $('.round-border').hide();
            $('.all-popular-block').show();
        }else if(!$('div.lastest-news-content').hasClass('active-read-more-news')){
                $('h6.first-lastest-news').addClass('small-vertical-seperator');
        }else{
            var page = $('div.paginationControl').attr('page');
            if(page > 1) showLastestNewsBlock();
        }
        if ($.browser.msie) {
            if($.browser.version == '8.0')
                $(".popular-wrapper").attr('style', 'margin-top: 20px !important');
            else
                $(".popular-wrapper").attr('style', 'margin-top: 10px !important');
        }
    }else{
        if(!$('div').hasClass('list-category-news'))
            $('#people-block.landing-page').css('padding-top', '0px');
        //show all news have append in small viewport
        $(".more-news-append").hide();

        $('.all-popular-block').show();
        $('.only-popular').show();
        $('.round-border').show();
        $('#lastest-news-btn-back').hide();
        $('div.medium-image').find('div.medium-news').each(function(){
            $(this).addClass('span6');
            $('div.medium-image').next().addClass('span4');
        })
        if ($.browser.msie) {
            var top = $(".popular-wrapper").css('margin-top');
            if(top != '0px') $(".popular-wrapper").attr('style', 'margin-top: 0px !important');
        }
        if(bodyWidth < 979){
            $('div.all-popular-block').find('div.news-desc').each(function(){
                var flag = false; var newText = '';
                var text = $(this).text();
                var arrText = text.split(' ');
                for(i=0; i < arrText.length; i++){
                    var splitText = splitLongWord(arrText[i], 10);
                    if(arrText[i].length < splitText.length){
                        newText += splitText+' ';
                        flag = true;
                        $(this).attr('original-text', text);
                    }else newText += arrText[i]+' ';
                }
                if(flag) $(this).text(newText);
            });
        }else{
            $('div.all-popular-block').find('div.news-desc').each(function(){
                var originalText = $(this).attr('original-text');
                if(originalText != '') $(this).text(originalText);
            });
        }
    }
}
function makeTwoDivSameHeight(){
    maxHeight = 0;
    $(".all-popular-block").css('min-height',maxHeight);
    $(".load-more-news").css('min-height',maxHeight);
    // in large and midium viewport
    if ($('.menu-small-viewport').css('display') != 'block'){
       maxHeight = ($(".all-popular-block").height() > $(".load-more-news").height()) ? $(".all-popular-block").height(): $(".load-more-news").height();
       $(".all-popular-block").css('min-height',maxHeight);
       $(".load-more-news").css('min-height',maxHeight);
       // fix for chrome
       if ($(".all-popular-block").height() > $(".load-more-news").height()) $(".load-more-news").css('min-height',maxHeight+20);
    }
}

function splitLongWord(string, strLength){
    var newStr = '';
    var seperator = string.split('-');
    if(string.length >= strLength && seperator.length < 2){
        newStr += string.substring(0,9)+'-';
        newStr += string.substring(9,string.length);
    }else newStr = string;
    return newStr;
}
