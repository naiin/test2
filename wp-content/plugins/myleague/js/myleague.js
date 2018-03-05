(function($){
		
	
	/*
	*  Events
	*
	*  Updates acf.screen with more data and triggers the update event
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/


	$(document).ready(function () {
        $('#duels_filter_leagues').change(function (e) {
            $("#duels_filter_countries").val($("#duels_filter_countries option:first").val());
            var selected_option = $(this).val();
            var leagues = $('.my-league-row');
            if ($(this).children(":selected").attr("id") === 'reset') {
                $.each(leagues, function (index, obj) {
                    var league_name = $(obj).attr('league');
                    $(obj).css('display', 'block');
                });
            } else {
                $.each(leagues, function (index, obj) {
                    var league_name = $(obj).attr('league');
                    if (league_name === selected_option) {
                        $(obj).css('display', 'block');
                    } else {
                        $(obj).css('display', 'none');
                    }

                });
            }


        });
        $('#duels_filter_countries').change(function (e) {
            $("#duels_filter_leagues").val($("#duels_filter_leagues option:first").val());

            var selected_option = $(this).val();
            var leagues = $('.my-league-row');
            if ($(this).children(":selected").attr("id") === 'reset') {
                $.each(leagues, function (index, obj) {
                    var league_name = $(obj).attr('country');
                    $(obj).css('display', 'block');
                });
            } else {
                $.each(leagues, function (index, obj) {
                    var league_name = $(obj).attr('country');
                    if (league_name === selected_option) {
                        $(obj).css('display', 'block');
                    } else {
                        $(obj).css('display', 'none');
                    }

                });
            }

        });

    //    Filter for Dates starts Here
		$('#duels_filter_date_apply_filter').on('click',function () {
            $("#duels_filter_leagues").val($("#duels_filter_leagues option:first").val());
            $("#duels_filter_countries").val($("#duels_filter_countries option:first").val());
            var date_start = $('#duels_filter_date_from').val();
			var date_end = $('#duels_filter_date_to').val();
            var leagues = $('.my-league-row');
			if (date_start!== '' && date_end!==''){

			date_start = Date.parse(Date.parse(date_start).toString("dd-MM-yyyy"));
			date_end = Date.parse(Date.parse(date_end).toString("dd-MM-yyyy"));


                $.each(leagues, function (index, obj) {
                    var league_date = $(obj).attr('date');
                    league_date = Date.parse(Date.parse(league_date).toString("dd-MM-yyyy"));
                    console.log(league_date + date_start + date_end);
                    if (league_date >= date_start && league_date<=date_end ) {

                        $(obj).css('display', 'block');
                    } else {
                        $(obj).css('display', 'none');
                    }

                });

			}else{
                $.each(leagues, function (index, obj) {
                    $(obj).css('display', 'block');
                });
			}
        });


    });



	$(document).on('click', '.my-league-selected-answer', function(e){
		e.preventDefault();
		var selectedElement = $(this);
		var matchid = $(this).attr("matchid");
		var teamid = $(this).attr("teamid");
		var option = $(this).attr("option");
		var postid = $(this).attr("postid");
		console.log(matchid + "--" +teamid +"--"+option);
		var ajaxurl = window.location.href;

		// load style
		$.ajax({
			url			:	ajaxurl,
			data		:	{
				shortcodeAnswer	:	'my-league-answer',
				matchid	:	matchid,
				teamid : teamid,
				option : option,
				postid : postid
			},
			type		: 'post',
			dataType	: 'html',
			success		: function( result ){
			
				console.log(result); 
				var replacedElement= selectedElement.closest('.wrapper-for-answers');
				replacedElement.html('');
				result = jQuery.parseJSON(result);
				console.log(result)

				var div = '<ul class="et_pb_counters et-waypoint et_pb_module et_pb_bg_layout_light  et_pb_counters_0 et-animated">';
				div += '<li class="et_pb_counter_0">';
				div += '<span class="et_pb_counter_title">Home Team</span>';
				div += '<span class="et_pb_counter_container et-animated" style="background-color: #dddddd;">'
				div += '<span class="et_pb_counter_amount" style="background-color: rgb(51, 106, 145); width: '+result.home+'%" data-width="'+result.home+'%"><span class="et_pb_counter_amount_number">'+result.home+'%</span></span>'
				div += '</span></li>';

				div += '<li class="et_pb_counter_1">';
				div += '<span class="et_pb_counter_title">Visitor Team</span>';
				div += '<span class="et_pb_counter_container et-animated" style="background-color: #dddddd;">'
				div += '<span class="et_pb_counter_amount" style="background-color: rgb(51, 106, 145); width: '+result.away+'%" data-width="'+result.away+'%"><span class="et_pb_counter_amount_number">'+result.away+'%</span></span>';
				div += '</span></li>';

				div += '<li class="et_pb_counter_2">';
				div += '<span class="et_pb_counter_title">Draw</span>'
				div += '<span class="et_pb_counter_container et-animated" style="background-color: #dddddd;">'
				div += '<span class="et_pb_counter_amount" style="background-color: rgb(51, 106, 145); width:'+result.draw+'%" data-width="'+result.draw+'%"><span class="et_pb_counter_amount_number">'+result.draw+'%</span></span>';
				div += '</span></li></ul>';
				replacedElement.html(div);
				//location.reload(); 
				
			}
		});



	});

	
	
	
	
	
	
	
	
})(jQuery);