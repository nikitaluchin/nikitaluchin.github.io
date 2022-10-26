$(document).ready(function() {
    $('.slider').slick({
        dots: true,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [{
            breakpoint: 960,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
            }
        }],
    });
});