(function($) {
  
    //add this script to module and only call it on product finder page...
    Drupal.behaviors.node_slideshow = {
        attach: function (context, settings) {
            var apply = $('input#edit-submit-holiday-lettings');
            apply.hide();
          
           
            $('.form-item-field-features-holiday-tid input[type="checkbox"]').change(function(){
                $('#edit-update-features-wrapper').show();
            });
           
            $('.form-item-field-town-tid input[type="checkbox"]').change(function(){
                $('#edit-update-town-wrapper').show();
            });
           
           
            //text fields - budget
            /*
            $('#edit-field-sleeps-value').focus(function(){
                $('#edit-update-sleeps-wrapper').show(); 
            });
            $('#edit-field-sleeps-value-1').focus(function(){
                $('#edit-update-sleeps-wrapper').show(); 
            });
           */

           
            $('.views-exposed-form select').change(function() {
                apply.click();     
            });
           
          
           
        }
    }
  
   
    
})(jQuery);//the correct way of namespacing jquery