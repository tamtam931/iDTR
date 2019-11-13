/*
	Added: Function Script for FLI-Time Record
	Date: June 13, 2019
	Author: Ben Zarmaynine E. Obra
*/

setInterval(function() {
	window.location.reload();
}, 300000)

var PostConn = function(url,data,BtnElmt=null,method="POST",BodyElmt=null){

	$ajaxData = $.ajax({
	    xhr: function () {
	        var xhr = new window.XMLHttpRequest();
	        /*xhr.upload.addEventListener("progress", function (evt) {
	            if (evt.lengthComputable) {
	                var percentComplete = evt.loaded / evt.total;
	                console.log(percentComplete);
	                $('.progress').css({
	                    width: percentComplete * 100 + '%'
	                });
	                if (percentComplete === 1) {
	                    $('.progress').addClass('hide');
	                }
	            }
	        }, false);*/
	        xhr.addEventListener("progress", function (evt) {
	            if (evt.lengthComputable) {
	                var percentComplete = evt.loaded / evt.total;

	                $('.progress').removeClass('hide');
	                $('.progress').css({
	                    width: percentComplete * 100 + '%'
	                });
	                if (percentComplete === 1) {
	                    $('.progress').addClass('hide');
	                }	                
	            }
	        }, false);
	        return xhr;
	    },
		url: url,
		method: method,
		data: data,
		dataType: "HTML",
		beforeSend:function(){

			if (BtnElmt) {

				submitLoader(BtnElmt);
			}
		},
		success:function(data){

		},
		error:function(data){

			$('.msg').html('<h4 class="text-danger"><u>Error occured upon obtaining data, Refresh the page or check your connection</u></h4>');
		}

	});

	return $ajaxData;
}

function messageBox(key,target,message){

	if (key == 'error') {

		setTimeout(function(){

			$(target).fadeOut(1000);
		},30000);

	} else {

		setTimeout(function(){

			$(target).addClass('alert alert-success').html(message).fadeIn(1000);

		},500);

		setTimeout(function(){

			$(".messagebox").fadeOut(1000);

		},3000);
	}
}

function submitLoader(element){

		$("input[type=submit]").val("loading");
		$(element).removeClass('btn-block');
		$("input[type=submit]").prop('disabled', true);
		//$('<div class="loader-container"><div class="preloader_1"><span></span><span></span><span></span><span></span><span></span></div></div>').insertAfter('input[type=submit]');	
}

function bodyLoader(element){

	$(element).empty();
	$(element).html('<div class="progress"><div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 80%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div> </div>');
}


function DateConv(datetime){
	var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	

	var d = new Date(datetime);
	var date = d.getDate();
	var month = months[d.getMonth()];
	var year = d.getFullYear();
	var x = document.getElementById("time");

	//x.innerHTML = day + " " + hr + ":" + min + ampm + " " + date + " " + month + " " + year;
	NewDate = month+' '+date+', '+year;
	return 	NewDate;
}

function TimeConv(datetime){
	//var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

	var d = new Date(datetime);
	//var day = days[d.getDay()];
	var hr = d.getHours();
	var min = d.getMinutes();
	if (min < 10) {
	    min = "0" + min;
	}
	var ampm = "a.m.";
	if( hr > 12 ) {
	    hr -= 12;
	    ampm = "p.m.";
	}

	//x.innerHTML = day + " " + hr + ":" + min + ampm + " " + date + " " + month + " " + year;
	NewTime = hr+':'+min+' '+ampm;
	return 	NewTime
}
