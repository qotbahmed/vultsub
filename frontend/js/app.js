//Import Js files
import "./jquery-1.9.1.min.js";
import "../../node_modules/@popperjs/core/dist/cjs/popper.js";
import "../../node_modules/bootstrap/dist/js/bootstrap.min.js";


import "./easyCoverflowCarousel.js"
//Import Style files
import "../scss/style.scss";


//Fix for jquery conflicts
var jQuery_1_9_1  = $.noConflict(true);
(function($) {
	//easyCoverflowCarousel - Home page product slider
    $(function(){
        Carousel.init($("#carousel"));
        $("#carousel").init();
    });
})(jQuery_1_9_1);


var lazyLoadInstance = new LazyLoad({
    // Your custom settings go here
});

