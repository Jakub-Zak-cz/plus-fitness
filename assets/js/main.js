console.log('Hello Wordpress');

/** hamburger menu */
// Získání všech tlačítek hamburger menu
const menuBtns = document.querySelectorAll('.menu-btn');

// Získání všech bočních panelů
const sidebars = document.querySelectorAll('.sidebar');

// Funkce pro otevření a zavření menu
function toggleMenu(event) {
  const menuBtn = event.currentTarget;
  const sidebar = menuBtn.nextElementSibling; // Získání příslušného bočního panelu
  sidebar.classList.toggle('open');
  menuBtn.classList.toggle('open');
}

// Při kliknutí na tlačítko hamburger zavoláme funkci toggleMenu
menuBtns.forEach(menuBtn => {
  menuBtn.addEventListener('click', toggleMenu);
});

// Při kliknutí na odkaz v menu zavřeme menu
sidebars.forEach(sidebar => {
  const links = sidebar.querySelectorAll('a');
  links.forEach(link => {
    link.addEventListener('click', toggleMenu);
  });
});



const lessons = document.querySelectorAll(".lesson");

lessons.forEach((lesson) => {
    lesson.addEventListener("click", () => {
        lesson.classList.toggle("l-active");
    });
});

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

/** credit info - btn with dropdown text (same effect is used for faqs) */
const creditBtn = document.getElementById("credit-btn");
const creditInfo = document.querySelector(".credit-info");

if (creditBtn) {
  creditBtn.addEventListener("click", () => {
    creditBtn.classList.toggle("open");
  });
}



document.addEventListener("DOMContentLoaded", function() {

  const toggleAdult = document.getElementById("toggle-adult");
  const toggleStudent = document.getElementById("toggle-student");
  const adultPriceList = document.getElementById("adult-price-list");
  const studentPriceList = document.getElementById("student-price-list");

  if (toggleAdult && toggleStudent && adultPriceList && studentPriceList) {
    toggleAdult.addEventListener("click", function() {
      studentPriceList.classList.remove("active");
      adultPriceList.classList.add("active");
      toggleStudent.classList.remove("active");
      toggleAdult.classList.add("active");
    });

    toggleStudent.addEventListener("click", function() {
      adultPriceList.classList.remove("active");
      studentPriceList.classList.add("active");
      toggleAdult.classList.remove("active");
      toggleStudent.classList.add("active");
    });
  }

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
    mousewheel: true,
    slidesPerView: 1,
    spaceBetween: 30,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
});

var review_responsive = new Swiper(".review-responsive", {
  direction: "horizontal",
  slidesPerView: 1,
  spaceBetween: 30,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
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