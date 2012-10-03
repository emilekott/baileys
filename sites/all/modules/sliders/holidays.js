(function($) {
  
    //add this script to module and only call it on product finder page...
    Drupal.behaviors.node_slideshow = {
        attach: function (context, settings) {
           var apply = $('input#edit-submit-holiday-lettings');
           
          
           
           
           

           
           $('.views-exposed-form select').change(function() {
                apply.click();     
           });
           
          
           
        }
    }
  
   
    
})(jQuery);//the correct way of namespacing jquery