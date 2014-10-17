loadJsOrCssFile('/scripts/upload/fileuploader.js', 'js');
loadJsOrCssFile('/scripts/upload/fileuploader.css', 'css');

function createUploader(){
    var uploader = new qq.FileUploader({
    	maxConnections: 1,
    	multiple: false,
        element: document.getElementById('file-uploader'),
        listElement: document.getElementById('separate-list'),
        action: '/tutors/ajax-upload'
    });
};
window.onload = createUploader;

function uploaderCallback(file, item, fileName, result) {
    $('#Avatar').val(fileName);
    $('#local-file').remove();
    $('#progress-img').parent().append('<div id="local-file" style="padding-top: 2px;">'+fileName+'</div>');
    $('#Save').attr('disabled', false);
}
var slideIframe = function (){
	var element = $(".exelikebox.large-screen");
	if ($('.slide-iframe').is(':visible')) {
		element.stop().animate({right: "-250"}, "medium");
		$('.slide-iframe').hide();
	}else {
		$('.slide-iframe').show();
		element.stop().animate({right: "0"}, "medium");
	}
};

$(document).ready(function() {
	tinymce.init({
	    selector: "textarea",
	    theme: "modern",
	    plugins: [
	        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
	        "searchreplace wordcount visualblocks visualchars code fullscreen",
	        "insertdatetime media nonbreaking save table contextmenu directionality",
	        "emoticons template paste textcolor colorpicker textpattern"
	    ],
	    doctype : "<!DOCTYPE html>",
	    inline_styles : true,
	    verify_html : false,
	});	
	
	$(".exelikebox.large-screen").hover(function() 
	{
		$('.slide-iframe').show();
		$(this).stop().animate({right: "0"}, "medium");
	}, function() {
		$(this).stop().animate({right: "-250"}, "medium");
		$('.slide-iframe').hide('slow');
	}, 500).click(function() {
        if ($('.slide-iframe').is(':visible')) {
			$(this).stop().animate({right: "-250"}, "medium");
			$('.slide-iframe').hide();
        }else {
			$('.slide-iframe').show();
            $(this).stop().animate({right: "0"}, "medium");
        }
    });
	
	var img = $('#Avatar').val();
	if($.trim(img) != '')
		$('#progress-img').parent().append('<div id="local-file" style="padding-top: 2px;">'+img+'</div>');
		
	$('#grades-modal').live('click', function(){
        var url = '/grades/ajax-show-grades';
        //$('.lastest-news-content').append('<div class="loading-news"><img src="/images/preloading.gif"/><div>');
        var grades = $('#TeachableInClass').attr('subs');
        if($.trim(grades) != '')  url += '/cgrades/'+grades;
        $.ajax({
            url : url,
            method: 'get',
            success : function(data){
                loadModal(data);
            }
        });
    });
    
    $('#subjects-modal').live('click', function(){
        var url = '/subjects/ajax-show-subjects';
        //$('.lastest-news-content').append('<div class="loading-news"><img src="/images/preloading.gif"/><div>');
        var subjects = $('#TeachableSubjects').attr('subs');
        if($.trim(subjects) != '')  url += '/csubjects/'+subjects;
        $.ajax({
            url : url,
            method: 'get',
            success : function(data){
                loadModal(data);
            }
        });
    });
    
    $('#districts-modal').live('click', function(){
        var url = '/districts/ajax-show-districts';
        //$('.lastest-news-content').append('<div class="loading-news"><img src="/images/preloading.gif"/><div>');
        var districts = $('#TeachableDistricts').attr('subs');
        if($.trim(districts) != '')  url += '/cdistricts/'+districts;
        $.ajax({
            url : url,
            method: 'get',
            success : function(data){
                loadModal(data);
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    });
    
    $("form#frmTutor").submit( function(eventObj) {
    	var grades = $.trim($('#TeachableInClass').attr('subs'));
    	var gradesText = $.trim($('#TeachableInClass').val());
    	
		var subjects = $.trim($('#TeachableSubjects').attr('subs'));
		var subjectsText = $.trim($('#TeachableSubjects').val());
		
		var districts = $.trim($('#TeachableDistricts').attr('subs'));
		var districtsText = $.trim($('#TeachableDistricts').val());
		
	      $('<input />').attr('type', 'hidden')
	          .attr('name', "TeachableInClass")
	          .attr('value', grades)
	          .appendTo('form#frmTutor');
	      
	      $('<input />').attr('type', 'hidden')
          .attr('name', "TeachableSubjects")
          .attr('value', subjects)
          .appendTo('form#frmTutor');
	      
	      $('<input />').attr('type', 'hidden')
          .attr('name', "TeachableDistricts")
          .attr('value', districts)
          .appendTo('form#frmTutor');
	      
	      $('<input />').attr('type', 'hidden')
          .attr('name', "TeachableInClassText")
          .attr('value', gradesText)
          .appendTo('form#frmTutor');
	      
	      $('<input />').attr('type', 'hidden')
          .attr('name', "TeachableSubjectsText")
          .attr('value', subjectsText)
          .appendTo('form#frmTutor');
	      
	      $('<input />').attr('type', 'hidden')
          .attr('name', "TeachableDistrictsText")
          .attr('value', districtsText)
          .appendTo('form#frmTutor');
	      
	      return true; 
	  });
});