/*
	Overriding JS file for FLI-Time Record
	As Of: July 03, 2019
	Dedicated file for Override and New functions for the system, Date and name of the author must be added upon adding codes	
*/
function TimeConv(datetime){

	var d = new Date(datetime);

	// fetch
	var hours = Number(d.getHours());
	var minutes = Number(d.getMinutes());

	// calculate
	var timeValue;

	if (hours > 0 && hours <= 12) {
	  timeValue= "" + hours;

	} else if (hours > 12) {

	  timeValue= "" + (hours - 12);

	} else if (hours == 0) {

	  timeValue = "12";
	}
	 
	timeValue += (minutes < 10) ? ":0" + minutes : ":" + minutes;  // get minutes
	timeValue += (hours >= 12) ? " P.M." : " A.M.";  // get AM/PM
	
	return timeValue;


}

/**
	View Source IP Device
	Author: Ben Zarmaynine E. Obra
	Date: August 6, 2019
*/

function loadTimeRecord(url,form_data,page=1){

	paginate_url = url+page;
	var DTR = PostConn(paginate_url,form_data,'','GET');
	output = '';
	if (DTR) {

		DTR.done(function(data){
			
			res = JSON.parse(data);
			$('.pagination_link').html(res.pagination_link);		
			$.each(res.result,function(data,value){

				output+=`<tr><td>`+value["BiometricID"]+`</td><td><p class="font-weight-bold" style="margin-bottom: 0">`+DateConv(value["DateTime"])+`</p><p style="margin-top: 0">`+TimeConv(value["DateTime"])+`</p></td><td>`+value["Status"]+`</td></tr>`;

			});

			setTimeout(function(){
				$('.data-res').html(output);
			},450);
		});

		DTR.fail(function(data){

			console.log(data);
			alert("Server Error Occured, Contact the IT Department now for concern");
			
		});
	}	
}

//End

/**
	Added Clear Cache Ajax for error response
	Author: Ben Zarmaynine E. Obra
	Date: September 9, 2019
*/

var PostConn = function(url,data,BtnElmt=null,method="POST",BodyElmt=null){

	$ajaxData = $.ajax({
	    xhr: function () {
	        var xhr = new window.XMLHttpRequest();
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
		cache: false,
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

/*
	Added Moment JS version of Timeconv and DateConv
	
*/
function TimeConv(datetime){

	momentTime = 'No Time has beed found';

	if (datetime) {

		let momentDate = moment(datetime);

		momentTime = momentDate.format('LT');

	}

	return momentTime;	

}

function DateConv(datetime){

	var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	var momentDate = 'No Date has been found';

	if (datetime) {

		let momentDateTime = moment(datetime);

		var parseDate = momentDateTime.parsingFlags();

		var date = parseDate.parsedDateParts[2];
		var month = months[parseDate.parsedDateParts[1]];
		var year = parseDate.parsedDateParts[0];

		momentDate = month+' '+date+', '+year;
	}

	return momentDate;
}

//End