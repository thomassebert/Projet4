 $(document).ready(function(){
      $('.carousel.carousel-slider').carousel({fullWidth: true, duration : 200});
      setInterval(function() {
	    $('.carousel').carousel('next');
	  }, 4000);
    });
