(function($) {
  
    //add this script to module and only call it on product finder page...
    Drupal.behaviors.node_slideshow = {
        attach: function (context, settings) {
            var min = $('input#edit-field-price-value-min');
            var max = $('input#edit-field-price-value-max');
    
            if (!min.length || !max.length) {
                // No min/max elements on this page
                return;
            }
    
            // Set default values or use those passed into the form
            var init_min = ('' == min.val()) ? 0 : min.val();
            var init_max = ('' == max.val()) ? 2000000 : max.val();

            // Set initial values of the slider 
            min.val(init_min);
            max.val(init_max);
        
            // $(min).before('<label for="edit-field-power-value-min">Between</label>');

            // Insert the slider before the min/max input elements 
            if ($('#power-slider').length){
                return; //quit out if power slider already added
            }
            
            
            min.attr('readonly', true);
            max.attr('readonly', true);
            
            min.parents('div.views-widget').before(
                $('<div id="power-slider"></div>').slider({
                    range: true,
                    min: 0,     // Adjust slider min and max to the range 
                    max: 2000000,    // of the exposed filter.
                    values: [init_min, init_max],
                    slide: function(event, ui){
                        // Update the form input elements with the new values when the slider moves
                        min.val(ui.values[0]);
                        max.val(ui.values[1]); 
                    },
                    
                    stop: function(event, ui) { 
                        //$(this).parents('form').submit();
                        $('#edit-submit-property-search').click();
                    }
                    
                })
                );
            
        }
    }
  
   
    
})(jQuery);//the correct way of namespacing jquery