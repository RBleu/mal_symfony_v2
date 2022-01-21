$(function(){
    new Splide('.splide', {
        type: 'loop',
        pagination: false,
        width: 760,
        padding: {
            left: 0,
            right: '80px',
        },
        perPage: 4,
    }).mount();
});