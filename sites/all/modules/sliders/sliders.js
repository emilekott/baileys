(function($) {
  
    //add this script to module and only call it on product finder page...
    Drupal.behaviors.node_slideshow = {
        attach: function (context, settings) {
         
           
           //custom auto submit
           //should only work for select / radios
           //text boxes should show an "update button" - hidden on page load
           
           var apply = $('.views-exposed-widget input#edit-new-submit');
           var reset = $('.views-exposed-widget input#edit-new-reset');
           apply.hide();
           
           
           
           $('.views-exposed-form select').change(function() {
               //alert("change non-text");
                apply.click();
                apply.hide();
                reset.show();
                
           });
           
            $('.views-exposed-form input[type="checkbox"]').change(function() {
               //alert("change non-text");
                apply.show();
                
                
           });
           
           $('.views-exposed-form input[type="text"]').focus(function(){
               //alert("change text");
               apply.show();
               
           })
           
           
           /*
            *
            * @todo:
            * Should be: 
            * Update shown after updating a text field or multiselect
            * With seperate update for each one box.
            * 
            * Hide all update buttons initially with CSS and then show individual ones below
            * updated individual filters
            * 
            * Maybe a cancel button to undo each change as well.
            * 
            * Wrap the text field and the button in a div so that it can all be handled by one bit of jQuery
            * 
            * 
            */
           
        }
    }
  
   
    
})(jQuery);//the correct way of namespacing jquery