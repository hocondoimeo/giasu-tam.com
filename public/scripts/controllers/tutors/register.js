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

$(document).ready(function() {
	var img = $('#Avatar').val();
	if($.trim(img) != '')
		$('#progress-img').parent().append('<div id="local-file" style="padding-top: 2px;">'+img+'</div>');
	
	//var bootstrapButton = $.fn.button.noConflict(); // return $.fn.button to previously assigned value
	//$.fn.bootstrapBtn = bootstrapButton; 
	
	$('#tutors-modal').live('click', function(){
        var url = '/tutors/ajax-show-tutors';
        //$('.lastest-news-content').append('<div class="loading-news"><img src="/images/preloading.gif"/><div>');
        var tutors = $('#ClassTutors').val();
        if($.trim(tutors) != '')  url += '/ctutors/'+tutors;
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
        var subjects = $('#ClassSubjects').attr('subs');
        if($.trim(subjects) != '')  url += '/csubjects/'+subjects;
        $.ajax({
            url : url,
            method: 'get',
            success : function(data){
                loadModal(data);
            }
        });
    });
    
    $('#districts-modal1').live('click', function(){
        var url = '/districts/ajax-show-districts';
        //$('.lastest-news-content').append('<div class="loading-news"><img src="/images/preloading.gif"/><div>');
        var districts = $('#TeachableDistricts').val();
        if($.trim(districts) != '')  url += '/cdistricts/'+districts;
        $.ajax({
            url : url,
            method: 'get',
            success : function(data){
                //loadModal(data);
            	/*$("#myModal").css("display","block");
            	 $("#myModal").css("top","500px");
            	$('#myModal').on('hidden', function () {
                    $(".modal-backdrop").remove();
                });
                $('#myModal').on('hide', function () {
                    $(".modal-backdrop").remove();
                });
                $(".modal-body").addClass("max-height-light-box");
                $(".modal-body").html('test');*/
            	//$('#myModal').modal('show');
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    });
    

    
    
    $("form#frmClass").submit( function(eventObj) {
		var tutors = $.trim($('#ClassTutors').val());
		var subjects = $.trim($('#ClassSubjects').attr('subs'));
		
	      $('<input />').attr('type', 'hidden')
	          .attr('name', "ClassTutors")
	          .attr('value', tutors)
	          .appendTo('form#frmClass');
	      
	      $('<input />').attr('type', 'hidden')
          .attr('name', "ClassSubjects")
          .attr('value', subjects)
          .appendTo('form#frmClass');
	      return true; loadPage(url, page);
	  });
});