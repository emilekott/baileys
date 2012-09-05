(function($) {
  
    //add this script to module and only call it on product finder page...
    Drupal.behaviors.node_slideshow = {
        attach: function (context, settings) {

            var prod = $('input#edit-field-product-code-value');
            
    
            if (!prod.length) {
                // No prod type elements on this page
                return;
            }
    
            // Set default values or use those passed into the form
            var init_prod = ('' == prod.val()) ? "" : prod.val();
            ; //all products
            

            // Set initial values of the slider 
            prod.val(init_prod);
            
            // Insert the slider before the min/max input elements 
            if ($('#power-slider').length){
                return; //quit out if power slider already added
            }
            
            //min.before('<label for="edit-field-power-value-min">Manoeuvrability</label>');
            //$('.form-item-field-power-value-max label').text("Stability");
            //prod.attr('readonly', true);
            
            prod.parents('div.views-widget').before(
                $('<div id="power-slider"></div>').slider({
                    range: false,
                    min: 0,     // Adjust slider min and max to the range 
                    max: 4,    // of the exposed filter.
                    values: [init_prod],
                    slide: function(event, ui){
                        // Update the form input elements with the new values when the slider moves
                        prod.val(ui.values[0]);
                    },
                    
                    stop: function(event, ui) { 
                        //$(this).parents('form').submit();
                         if ($("input#edit-field-product-code-value").val()==0){
                            //alert($("input#edit-field-product-code-value").val());
                            $("input#edit-field-product-code-value").val("");
  
                        }
                       
                        $('#edit-submit-sail-finder').click();
                    }
                    
                })
                );  
            $('#power-slider').after('<div id="product-icons"></div>');   
                
            $(".view-sail-finder td").hover(function(){
                $(this).children(".views-field-field-product-image").fadeTo('fast', 0.5);
            
            },
            function(){
                $(this).children(".views-field-field-product-image").fadeTo('fast', 1.0);
            });
            
            $('.view-sail-finder .view-content').hide();
            
            
            if ($("#edit-water-state-wrapper input").is(':checked')){
                //alert("check");
                var checked = $("#edit-water-state-wrapper input:checked").val();
                var checked2 = $("input[name='water_state']").val();
                //alert(checked);
                //disable inputs
                
                
                if (checked == 'waves'){
                    //@todo: set checked value of tid to 'All'
                    
                    $("#edit-field-board-type-tid-wrapper").show();
                    $("#edit-field-board-type-tid-wrapper .form-item").hide();
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-38').show(); //waveboard
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-33').show(); //fsw
                }
                else if (checked == 'flat'){
                    //@todo: set checked value of tid to 'All'
                    $("#edit-field-board-type-tid-wrapper").show();
                    $("#edit-field-board-type-tid-wrapper .form-item").hide();
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-49').show(); //beginner
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-40').show(); //kids
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-33').show(); //fsw
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-34').show(); //fs
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-35').show(); //fr
                }
                else if (checked == 'race'){
                    //@todo: set checked value of tid to 'All'
                    $("#edit-field-board-type-tid-wrapper").show();
                    $("#edit-field-board-type-tid-wrapper .form-item").hide();
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-41').show(); //slalom
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-42').show(); //formula
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-43').show(); //experience
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-44').show(); //raceboard
                }
                
            }
            else{
                $("#edit-field-board-type-tid-wrapper").hide(); 
            }
            
            if ($("#edit-field-board-type-tid input").is(':checked')){
                var tid = $("#edit-field-board-type-tid input:checked").val();
                if (tid != "All"){
                    $('.view-sail-finder .view-content').show("fast");
                    if (!($('.page-product-finder .view-filters #edit-field-product-code-value-wrapper').is(":visible"))){
 
                        $('.page-product-finder .view-filters #edit-field-product-code-value-wrapper').show();
                        
                    }
                }
                else{
                    $('.view-sail-finder .view-content').hide();
                    $('.page-product-finder .view-filters #edit-field-product-code-value-wrapper').hide();
                    
                }
            }   
            
            
            if ($("input#edit-field-product-code-value").val()==0){
                //alert($("input#edit-field-product-code-value").val());
                $("input#edit-field-product-code-value").val("");
  
            }   
            
            
            
            
        }
    }
  
   
    
})(jQuery);//the correct way of namespacing jquery