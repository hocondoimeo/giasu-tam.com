//ready for loading js 
$(document).ready(function() {
    //initClickCrazyEggMetrics();
    //getNewsFeed();
    //refreshDataMetricsWithAjax();
	//initial data ready to call 
	showContentHomePageInSmallViewPort();
	showNewsInSmallViewPort();
});
//when window is resized by customer
$(window).resize(function () {
    showNewsInSmallViewPort();
    showContentHomePageInSmallViewPort();
    //initClickCrazyEggMetrics();
    //refreshDataMetricsWithAjax();
    //makeTwoDivSameHeight();
});




/**
 *@author Phuc Duong
 *@desc get ajax
 *@since 2012-11-20
 **/
function getNewsFeed(){   
    $.ajax({
        url: '/index/ajax-get-feeds',
        timeout:20000,
        success: function(data) {
            $(".wating-feeds").remove();            
            $(".news-block").append(data);
            showNewsInSmallViewPort();
            customizeLightBox();
            makeTwoDivSameHeight();
        },
        error: function(x, t, m) {
            if(t==="timeout") {
                msg = " Got timeout";
            } else {
                msg = t+" Load news";
            }
            $(".news-block").append(msg);
            makeTwoDivSameHeight();
        }
    });
}
function makeTwoDivSameHeight(){
    var bodyWidth = $(window).width(); 
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
/**
*@author Tri Van
*@desc refresh Data Metrics With Ajax
*@since Mon Now 26,2012
**/
function refreshDataMetricsWithAjax(){
    var bodyWidth = $(window).width();
    var countRefresh = $("#metrics-block").attr("load-refresh-ajax");
    if(bodyWidth > 767 && countRefresh != "1"){
        $("#metrics-block").attr("load-refresh-ajax","1");
        getCrazyEggMetric();
        getBambooMetric();
        getGoogleAnalytics();
        getNewRelicMetrics();
    }
}
/**
 *@author Tri Van
 *@desc get scrazy egg metrics
 *@since Mon Now 26,2012
 **/
function getCrazyEggMetric(){
    $.ajax({
        url: '/index/ajax-get-crazy-egg-metric/format/json',
        timeout:10000,
        success: function(data) {
            if(data){
                $(".screenshot-crazyegg-small").attr("src",data.screenShoot);
                $(".screenshot-crazyegg-large").attr("src",data.screenShoot);

                $(".heatmap-crazyegg-small").attr("src",data.heatMap);
                $(".heatmap-crazyegg-large").attr("src",data.heatMap);

                $(".modal-body").addClass("max-height-light-box");
                $(".modal-body").html($("#heat-map-images").html());
                initClickCrazyEggMetrics();
            }
        },
        error: function(x, t, m) {
            $("#block-crazy-egg-metrics").hide();
        }
    });
}
/**
 *@author Quang Nguyen
 *@desc get bamboo metrics
 *@since Thu Dec 6,2012
 **/
function getBambooMetric(){
    $.ajax({
        url: '/index/ajax-get-bamboo-metric/format/json',
        timeout:10000,
        success: function(data) {
            if (typeof data.latestBuildNumber == "number" && typeof data.averageBuildTime == "number") {
                var averageBuildTimeMinutes = data.averageBuildTime / 60,
                    averageBuildTimeMinutes = Math.floor(averageBuildTimeMinutes),
                    averageBuildTimeSeconds = data.averageBuildTime - (averageBuildTimeMinutes * 60);

                $("#block-bamboo-metrics .bamboo-build-number .number").html(data.latestBuildNumber);
                if (averageBuildTimeMinutes > 0) {
                    $("#block-bamboo-metrics .bamboo-average-build-time .number").html(averageBuildTimeMinutes + '<span class="number-type">m</span> ' + averageBuildTimeSeconds + '<span class="number-type">s</span>');
                } else {
                    $("#block-bamboo-metrics .bamboo-average-build-time .number").html(averageBuildTimeSeconds + '<span class="number-type">s</span>');
                }
            }
        },
        error: function(x, t, m) {}
    });
}
/**
 *@author Phuc Duong
 *@desc get google analytics metrics
 *@since Thu Dec 6,2012
 **/
function getGoogleAnalytics(){
    $.ajax({
        url: '/index/ajax-get-google-analytics/format/json',
        timeout:10000,
        success: function(dataResponse) {
            if(dataResponse.report.error == 0){

                // Set chart optionsvar
                data = new google.visualization.DataTable();
                data.addColumn('string', 'Topping');
                data.addColumn('number', 'Slices');
                data.addRows([
                    ['Andoird', dataResponse.report.android],
                    ['iOS',     dataResponse.report.ios],
                    ['Other',   dataResponse.report.other]
                ]);
                var options = {
                    width: 125,
                    height: 125,
                    colors: ['#999999', '#1582AB', 'white'],
                    pieSliceBorderColor : "#999999",
                    chartArea: { left: 0, top: 0, width: "100%", height: "100%"},
                    legend: {position: 'top'},
                    pieSliceText: "none"
                };
                var chart = new google.visualization.PieChart(document.getElementById('chart-google-analytics'));
                chart.draw(data, options);
            }
        },
        error: function(x, t, m) {}
    });
}
/**
 *@author Duy Ngo
 *@desc get new relic metrics by using ajax method
 *@since 2012-11-26
 **/
function getNewRelicMetrics(){
    $.ajax({
        url: '/index/ajax-get-new-relic-metrics',
        timeout:10000,
        success: function(data) {
            if(data.length > 100)
                $("#block-new-relic-metrics").html(data);
        },
        error: function(x, t, m) {
            if(t==="timeout") {
                msg = "got timeout";
            } else {
                msg = t+" load news";
            }
            //$("#block-new-relic-metrics").append(msg);
        }
    });
}

/**
 *@author Tri Van
 *@desc load modal
 *@since Tue Now 27,2012
 **/
function initClickCrazyEggMetrics() {
    customizeLightBox();
    $("#heatmap-crazyegg-small").click(function(){
        var bodyWidth = $(window).width();
        var positionCrazyEgg = $("#block-crazy-egg-metrics").position();
        $(window).scrollTop(positionCrazyEgg.top);
        var PaddingTopLightBox = positionCrazyEgg.top;
        $("#myModal").css("top",PaddingTopLightBox+"px");
        $("#myModal").modal('show');
    });
    $('#myModal').on('hidden', function () {
        $(".modal-backdrop").remove();
    });
    $('#myModal').on('hide', function () {
        $(".modal-backdrop").remove();
    });
}

function customizeLightBox(){

     var bodyWidth = $(window).width();
     if(bodyWidth>767 && bodyWidth<979){
         $(".featurette-crazy-egg-metrics-large").css("max-height","550px");
         $(".max-height-light-box").css("max-height","590px");
     }
     else{
         $(".featurette-crazy-egg-metrics-large").css("max-height","700px");
         $(".max-height-light-box").css("max-height","740px");
     }
}

function showNewsInSmallViewPort(){
    var bodyWidth = $(window).width();
    if(bodyWidth < 768){
    	//show small menu viewport
    	$(".menu-small-viewport").each(function(){
            $(this).show();
        });
      //show carousel control small viewport
    	/*$(".carousel-control").each(function(){
            $(this).show();
        });*/
    	
    	$("#myCarousel").swiperight(function() {  
    	      $("#myCarousel").carousel('prev');  
    	    });  
	   $("#myCarousel").swipeleft(function() {  
	      $("#myCarousel").carousel('next');  
	   }); 
    	   
        //hide all
    	$(".menu").each(function(){
            $(this).hide();
        });
    	$(".content-top-left").each(function(){
            $(this).hide();
        });
    	$(".content-top-right").each(function(){
            $(this).hide();
        });
    	$(".news-block").each(function(){
            $(this).hide();
        });
        $(".news-block .featurette").each(function(){
            $(this).hide();
        });
        $(".news-block .featurette-divider-social").each(function(){
            $(this).hide();
        });
        //just show 3 news
        $(".news-block .featurette").eq(0).show();
        $(".news-block .featurette").eq(1).show();
        $(".news-block .featurette").eq(2).show();
        $(".news-block .featurette-divider-social").eq(0).show();
        $(".news-block .featurette-divider-social").eq(1).show();
        //add number attribute for new-block div tag
        $(".news-block").attr('news-number', 3);
    }
    else{
    	//hide small menu viewport
    	$(".menu-small-viewport").each(function(){
            $(this).hide();
        });
    	//hide carousel control small viewport
    	/*$(".carousel-control").each(function(){
            $(this).hide();
        });*/
        //show all
    	$(".menu").each(function(){
            $(this).show();
        });
    	$(".content-top-left").each(function(){
            $(this).show();
        });
    	$(".content-top-right").each(function(){
            $(this).show();
        });
    	$(".news-block").each(function(){
            $(this).show();
        });
        $(".news-block .featurette").each(function(){
            $(this).show();
        });
        $(".news-block .featurette-divider-social").each(function(){
            $(this).show();
        });
        //add number attribute for new-block div tag
        $(".news-block").attr('news-number', 5);
    }
}

function showContentHomePageInSmallViewPort(){
    var bodyWidth = $(window).width();
    if(bodyWidth < 768){
        var widthPeople = $(".image-left").width();
        $(".content-right").css("padding-left",widthPeople);
    }
    else{
        $(".content-right").css("padding-left",0);
    }
}



