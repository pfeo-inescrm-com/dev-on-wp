jQuery('document').ready( function($){
	var stepsTabs = $('.basepress-wizard-step');
	var stepsStatus = $('#basepress-wizard-steps li');
	var stepsCount = stepsTabs.length - 1;
	var currentStep = 0;

	$( '#wizard-next, #wizard-proceed' ).click( function(e){
		e.preventDefault();
		currentStep +=1;
		displayCurrentStep();
	});

	$( '#wizard-prev' ).click( function(e){
		e.preventDefault();
		currentStep -=1;
		if( currentStep < 0 ){
			currentStep = 0;
		}

		displayCurrentStep();
	});

	function displayCurrentStep(){
		stepsTabs.removeClass('active').eq(currentStep).addClass('active');
		updateStepsStatus();
		if( currentStep == stepsCount ){
			processData();
		}
		console.log( currentStep );
	}

	function updateStepsStatus(){
		stepsStatus.removeClass( 'active done' );
		stepsStatus.eq( currentStep ).addClass( 'active' );
		stepsStatus.slice( 0, currentStep ).addClass( 'done' );

		switch( currentStep ){
			case 0:
				$( '#wizard-prev' ).attr( 'disabled', 'disabled' );
				$( '#wizard-next' ).attr( 'disabled', false ).show();
				$( '#wizard-proceed' ).hide();
				break;
			case (stepsCount - 1):
				$( '#wizard-prev' ).attr( 'disabled', false );
				$( '#wizard-next' ).hide();
				$( '#wizard-proceed' ).show();
				break;
			case stepsCount:
				$( '#wizard-prev, #wizard-next, #wizard-proceed' ).remove();
				break;
			default:
				$( '#wizard-prev' ).attr( 'disabled', false );
				$( '#wizard-next' ).attr( 'disabled', false ).show();
				$( '#wizard-proceed' ).hide();
		}
	}

	function processData(){
		$('#wizard-response').html( '' );
		$( '#ajax-loader' ).show();

		$.ajax({
			type: 'POST',
			dataType : 'json',
			url: ajaxurl,
			data:{
				action:	'basepress_wizard_proceed',
				settings: $('#basepress-wizard-form').serialize()
			},

			success: function( response ){
				if( response.error ){
					console.log( response.error );
				}
				else{
					$('#wizard-response').html( response.response );
				}
			},

			error: function(  jqXHR, textStatus, errorThrown){
				console.log( errorThrown );
			},

			complete: function(){
				stepsStatus.addClass( 'done' );
				$( '#ajax-loader' ).hide();
			}
		});
	}
});