require(['jquery', 'jquery/ui', 'slick'], function ($) {
    $(document).ready(function () {
        $('.slick-offers')
            .on('init', function () {
                $(this).css({visibility: 'visible'});
            })
            .slick({
                dots: false,
                infinite: true,
                arrows: false,
                centerMode: true,
                centerPadding: '50px',
                pauseOnHover: true,
                autoplay: true,
                responsive: [
                    {
                        breakpoint: 480,
                        settings: {
                            centerMode: false,
                        }
                    }
                ]
            });
    });
});
