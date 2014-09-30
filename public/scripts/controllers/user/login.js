var windowSizeArray = [ "width=300,height=400",
                            "width=300,height=400", "width=300,height=400" ];
 
    $(document).ready(function(){
        $('.newWindow').click(function (event){
        	var win;
            var checkConnect;
            
            var url = $(this).attr("href");
            var windowName = "popUp";//$(this).attr("name");
            var windowSize = windowSizeArray[ $(this).attr("rel") ];
 
            win=  window.open(url, windowName, windowSize);
 
            checkConnect = setInterval(function() {
                if (!win || !win.closed) return;
                clearInterval(checkConnect);
                window.location.reload();
            }, 100);
            
            event.preventDefault();
 
        });
    });