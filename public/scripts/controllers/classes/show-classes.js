$(document).ready(function() {    
    $('.read-more-news').live('click', function(){
        var currentpage = $('div.paginationControl').attr('current-page');
        currentpage = parseInt(currentpage);
        var url = '/classes/ajax-get-classes/page/'+(currentpage + 1);
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
    
    $('select').bind('onChange',function(){$(this).blur()});
    
    $("#classes-select").change(function () {
    	//var currentpage = $('div.paginationControl').attr('current-page');
    	var url = '/classes/ajax-get-classes';
    	
        var subject = $('#subjects-select :selected').val();
        if($.trim(subject) != '')
        	url += '/subject/'+subject;
        
        var district = $('#districts-select :selected').val();
        if($.trim(district) != '')
        	url += '/district/'+district;
        
    	if($.trim($(this).val()) != '')
    		url += '/class/'+$(this).val();
    	
    	loadPage(url);
    });
    
    $("#districts-select").change(function () {    	
    	var url = '/classes/ajax-get-classes';
    	
        var subject = $('#subjects-select :selected').val();
        if($.trim(subject) != '')
        	url += '/subject/'+subject;

    	var classSelect = $('#classes-select :selected').val();
    	if($.trim(classSelect) != '')
    		url += '/class/'+classSelect;
        
        if($.trim($(this).val()) != '')
        	url += '/district/'+$(this).val();
    	
    	loadPage(url);
    });
    
    $("#subjects-select").change(function () {    	
    	var url = '/classes/ajax-get-classes';
    	
    	var district = $('#districts-select :selected').val();
        if($.trim(district) != '')
        	url += '/district/'+district;

    	var classSelect = $('#classes-select :selected').val();
    	if($.trim(classSelect) != '')
    		url += '/class/'+classSelect;
        
        if($.trim($(this).val()) != '')
        	url += '/subject/'+$(this).val();
        
    	loadPage(url);
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