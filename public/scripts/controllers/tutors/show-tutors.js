$(document).ready(function() {    
    $('.read-more-news').live('click', function(){
        var currentpage = $('div.paginationControl').attr('current-page');
        currentpage = parseInt(currentpage);
        var url = '/tutors/ajax-get-tutors/page/'+(currentpage + 1);
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
    $('.lastest-news-content').html('<img src="/images/icons/preloading.gif"/>');
    $.ajax({
        url : url,
        method: 'get',
        success : function(data){
            $('.lastest-news-content').html(data);
            if(!$('div.lastest-news-content').hasClass('active-read-more-news'))
                $('div.lastest-news-content').addClass('active-read-more-news');
            //makeTwoDivSameHeight();

            if(typeof page != 'undefined' && $.isNumeric(page)){
                var currentPage = page;
                $('div.paginationControl').attr('current-page',currentPage);
            }
        }
    });
}