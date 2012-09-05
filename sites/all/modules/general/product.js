(function($) {
    $(document).ready(function(){
        //$(".field-name-field-color-swatch a").attr("href", "");
        $(".field-name-field-color-swatch img").mouseover(function() {
            $(this).css('cursor', 'pointer');
        });  
        $(".field-name-field-color-swatch img").click(function(){
            var clickedClass = $(this).parent().attr("class");
            var fieldClass = "." + clickedClass.substr(clickedClass.length - 12);
            //use pattern match to get .field-item-x
            //alert(fieldClass);
            $(".field-name-field-product-image").children(".field-items").children(".field-item").hide("fast");
            $(".field-name-field-product-image").children(".field-items").children(fieldClass).show("fast");
        }
        ); 
            
            
            
        
        //show tooltip
        var points = $(".field-name-field-tech-marker .field-items").children().size();
        if (points>0){
            $(".field-name-field-tech-marker .field-items").after('<div class="tech-tooltip"><h2>Technology</h2>Please click a <img style="vertical-align:middle" src="'+ Drupal.settings.node_slideshow.image_path + '/demo-tech-big.png" /> to show further information.</div>');
        }
        //$(".field-name-field-tech-marker .field-items > .field-item-0 .tech-marker").addClass("active");
        $(".tech-marker").click(function(){
            $(".tech-tooltip").hide();
            $(".tech-marker").removeClass("active");
            $(this).addClass("active");
            var clickedClass = $(this).parent().parent().parent().parent().parent().parent().attr("class");
            var fieldClass = "." + clickedClass.substr(clickedClass.length - 12);
            $(".field-name-field-tech-marker .field-items > .field-item .field-name-field-technology").hide("fast");
            var fieldToShow = ".field-name-field-tech-marker .field-items >" + fieldClass + " .field-name-field-technology";
            $(fieldToShow).show("fast");
            
        });
        
        //add hover event to tech table as :hover no good for mobile
        $(".group-product-specification tr").hover(function(){
            $(".group-product-specification tr").addClass("black-out");
            $(this).addClass(":hover");
        });
        /*
        $(".group-product-specification table").mouseout(function(){
            //alert("mouse out");
            $(".group-product-specification tr").removeClass("black-out");
        });
        */
    });
    
    
})(jQuery);//the correct way of namespacing jquery