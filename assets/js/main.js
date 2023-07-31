console.log('Hello Wordpress');

function togglePriceList(event, priceListId) {
  event.preventDefault();

  const priceList = document.getElementById(priceListId);
  const toggleButtons = document.getElementsByClassName("price-list__toggle");

  for (let i = 0; i < toggleButtons.length; i++) {
    toggleButtons[i].classList.remove("active");
  }

  event.target.classList.add("active");

  const allPriceLists = document.getElementsByClassName("price-list__content");
  for (let i = 0; i < allPriceLists.length; i++) {
    allPriceLists[i].classList.remove("active");
  }

  priceList.classList.add("active");
}

document.addEventListener("DOMContentLoaded", function() {

  // Získáme tlačítka a ceníky
  const toggleAdult = document.getElementById("toggle-adult");
  const toggleStudent = document.getElementById("toggle-student");
  const adultPriceList = document.getElementById("adult-price-list");
  const studentPriceList = document.getElementById("student-price-list");

  // Při kliknutí na tlačítko pro dospělé
  toggleAdult.addEventListener("click", function() {
    // Skryjeme ceník pro studenty a zobrazíme ceník pro dospělé
    studentPriceList.classList.remove("active");
    adultPriceList.classList.add("active");
    toggleStudent.classList.remove("active");
    toggleAdult.classList.add("active");
  });

  // Při kliknutí na tlačítko pro studenty
  toggleStudent.addEventListener("click", function() {
    // Skryjeme ceník pro dospělé a zobrazíme ceník pro studenty
    adultPriceList.classList.remove("active");
    studentPriceList.classList.add("active");
    toggleAdult.classList.remove("active");
    toggleStudent.classList.add("active");
  });
});


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