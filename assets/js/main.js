console.log('Hello Wordpress');

/*
* Swiper 
*/
var swiper = new Swiper(".trainers", {
    slidesPerView: 1,
    spaceBetween: 30,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    breakpoints: {
        900: {
            slidesPerView: 3,
        },
    },
});