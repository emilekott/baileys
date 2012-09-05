(function($) {
    Drupal.behaviors.node_slideshow = {
        attach: function (context, settings) {
            
            //$("#edit-field-board-type-tid-wrapper").hide();
            if ($("#edit-water-state-wrapper input").attr('checked')){
                var checked = $("#edit-water-state-wrapper input:checked").val();
                var checked2 = $("input[name='water_state']").val();
                alert(checked2);
                if (checked == 'waves'){             
                    $("#edit-field-board-type-tid-wrapper").show();
                    $("#edit-field-board-type-tid-wrapper .form-item").hide();
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-38').show(); //waveboard
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-33').show(); //fsw
                }
                else if (checked == 'flat'){
                    $("#edit-field-board-type-tid-wrapper").show();
                    $("#edit-field-board-type-tid-wrapper .form-item").hide();
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-49').show(); //beginner
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-40').show(); //kids
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-33').show(); //fsw
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-34').show(); //fs
                    $('#edit-field-board-type-tid-wrapper .edit-field-board-type-tid-35').show(); //fr
                }
                
            }
            else{
               $("#edit-field-board-type-tid-wrapper").hide(); 
            }
            
        }
    }
   
})(jQuery);//the correct way of namespacing jquery