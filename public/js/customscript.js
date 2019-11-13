/*
	Added: Custom Script For FLI-Time Record
	Date: June 13, 2019
	Author: Ben Zarmaynine E. Obra
*/
var FORMDATA;
$(document).ready(function(){

	$('body').on('click','.inq_tr', function(e){
		e.preventDefault();
		
		var user_parent = $(this).parent("a");
		url = user_parent.attr("href");
		form = $(this).closest("form");
		action = $(form).attr("action");

		if (form.length == 1) {

			var form_data = form.serialize();

			var inquire = PostConn(action,form_data,'.inq_tr');
			if (inquire) {

				form.attr('disabled', true);
				inquire.done(function(data){
					
					setTimeout(function(){
						
						$('.inq_tr').addClass("btn-block");
						form.attr('disabled', false);
					},350);

					$("input[type=submit]").prop('disabled', false);
					$('input[type=submit]').val("Inquire");
					//$('.loader-container').remove();
				
					try {

						var $response = $(data);
						row = $response.filter('.main-body').html();
						$('.main-body').html(row);
						$('.inq_tr').trigger('click');

					} catch (err){

						console.log("Error upon inputing data");
						validate = JSON.parse(data);
							
						if(validate.success == 'false'){

							$('.text-danger').remove();
							$.each(validate.messages, function(key,value){

								var Newelement = $('#'+ key);
								Newelement.closest('div.form-group')
								.removeClass('border-danger')
								.addClass(value.length > 0 ? 'border-danger' : 'border-success')
								.find('.text-danger').remove();
								Newelement.after(value);

							});

						}							

					}
						

				});

				inquire.fail(function(data){

					console.log(data);
					alert("Server Error Occured, Contact the IT Department now for concern");
				});	
							
			} else {

				alert("Several Connection is not established, Check your internet or contact ITBSD");
			}

		} else {

			access_no = $('.access_no').text();
			start_dt = $('.date_start').attr('data-attr');
			end_dt = $('.date_end').attr('data-attr');

			//array_data = "access_no="+access_no+"&date_start="+start_dt+"&date_end="+end_dt;
			array_data = {"access_no" : access_no, "date_start" : start_dt, "date_end" : end_dt};
			loadTimeRecord(url,array_data);

		}

	});

});

$('body').on('click','.pagination li a',function(e){
	e.preventDefault();
	
	var page = $(this).data("ci-pagination-page");
	url = window.location.protocol+"//"+window.location.hostname+window.location.pathname;

	access_no = $('.access_no').text();
	start_dt = $('.date_start').attr('data-attr');
	end_dt = $('.date_end').attr('data-attr');

	array_data = {"access_no" : access_no, "date_start" : start_dt, "date_end" : end_dt};
	$('data-res').empty();
	loadTimeRecord(url,array_data,page);

	$('.data-res').empty();
    $('html,body').animate({
    	scrollTop: 0
    }, 700);	

});

function loadTimeRecord(url,form_data,page=1){

	paginate_url = url+page;
	var DTR = PostConn(paginate_url,form_data,'','GET');
	output = '';
	if (DTR) {

		DTR.done(function(data){
			res = JSON.parse(data);
			$('.pagination_link').html(res.pagination_link);		
			$.each(res.result,function(data,value){

				output+=`<tr><td>`+value["BiometricID"]+`</td><td><p class="font-weight-bold" style="margin-bottom: 0">`+DateConv(value["DateTime"])+`</p><p style="margin-top: 0">`+TimeConv(value["DateTime"])+`</p></td><td>`+value["Status"]+`</td><td>`+value["name_device"]+`</td></tr>`;

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
