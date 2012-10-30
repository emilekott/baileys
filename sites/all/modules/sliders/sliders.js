(function($) {
  
    //add this script to module and only call it on product finder page...
    Drupal.behaviors.node_slideshow = {
        attach: function (context, settings) {
           var apply;
           
           if ($('.views-exposed-widget input#edit-submit-property-search').length>0){
               //sales
               apply = $('.views-exposed-widget input#edit-submit-property-search');
           }
           else{
               //lettings
               apply = $('.views-exposed-widget input#edit-submit-property-search-rentals');
           }
           
           
           
           apply.hide();

           
           $('.views-exposed-form select').change(function() {
                apply.click();     
           });
           
           //checkboxes
           $('.form-item-field-features-tid input[type="checkbox"]').change(function(){
              $('#edit-update-features-wrapper').show();
           });
           
           $('.form-item-field-town-tid input[type="checkbox"]').change(function(){
              $('#edit-update-town-wrapper').show();
           });
           
           //text fields - budget
           /*
           $('#edit-field-price-value').focus(function(){
              $('#edit-update-budget-wrapper').show(); 
           });
           $('#edit-field-price-value-1').focus(function(){
              $('#edit-update-budget-wrapper').show(); 
           });
           */
           
           
           //text fields - bedrooms
           /*
           $('#edit-field-bedrooms-value').focus(function(){
              $('#edit-update-bedrooms-wrapper').show(); 
           });
           $('#edit-field-bedrooms-value-1').focus(function(){
              $('#edit-update-bedrooms-wrapper').show(); 
           });
           */
           
        }
    }
  
   
    
})(jQuery);//the correct way of namespacing jquery