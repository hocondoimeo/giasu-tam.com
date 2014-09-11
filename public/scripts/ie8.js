
function showFooterInSmallViewPortIE8(){
    var bodyWidth = $(window).width();

    if(bodyWidth < 767){
            $("#social-link-footer").attr("style","width:100% !important");
            $("#let-talk-footer").attr("style","width:100% !important");
    }
    else{
            $("#social-link-footer").attr("style","");
            $("#let-talk-footer").attr("style","");
    }
}

$(window).resize(function () {
	showFooterInSmallViewPortIE8();
});

showFooterInSmallViewPortIE8();