jQuery(document).ready(function() {
   var currentValue = $('.datum').val();
   var currentDate = new Date();
  
   jQuery('.datum').datepicker({
	  'language': 'nl',
      'format': 'dd-mm-yyyy',
      'autoclose': true,  
      'changeMonth': true,
      'changeYear': true,
      'minDate': '01-01-2014',
      'showAnim': 'slideDown' });
  
  //console.log('Huidige datum is: ' + currentDate);
  //console.log('Datum in veld is: ' + currentValue);
  if (!currentValue) {
     //Set date to current date  
    jQuery('.datum').datepicker();
  }
      
   jQuery('.tijd').timepicker({
      'timeFormat': 'H:i',
      'step': 15,
      'scrollDefault': 'now',
      'show2400': true });
   
});
