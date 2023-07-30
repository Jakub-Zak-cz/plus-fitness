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

 var reviews_slider = new Swiper(".reviews_slider", {
    direction: "vertical",
    slidesPerView: 1,
    spaceBetween: 30,
    mousewheel: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
});


var gallery_slider = new Swiper(".gallery-slider", {
   slidesPerView: 1,
   spaceBetween: 30,
   navigation: {
     nextEl: ".swiper-button-next",
     prevEl: ".swiper-button-prev",
   },
   breakpoints: {
     900: {
         slidesPerView: 2,
     },
   }  
 });