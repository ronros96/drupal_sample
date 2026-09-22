import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const swiperLarge = new Swiper('.swiper-large', {
  modules: [Navigation, Pagination],

  slidesPerView: 1,
  spaceBetween: 20,

  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  pagination: {
    el: '.swiper-pagination',
    clickable: true,
  },
});

const swiperTeaser = new Swiper('.swiper-teaser', {
  modules: [Navigation, Pagination],

  slidesPerView: 1,
  spaceBetween: 20,
  centeredSlides: true,
  breakpoints: {
    640: {
      centeredSlides: true,
      slidesPerView: 1.5,
    },
    768: {
      centeredSlides: false,
      slidesPerView: 2,
    },
    1024:{
      centeredSlides: false,
      slidesPerView: 3,
    }
  },

  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  pagination: {
    el: '.swiper-pagination',
    clickable: true,
  },
});

const menu = document.querySelector('.menu');
const header = document.querySelector('header');
const main_content = document.querySelector('.site-main .content div');
const overlay = document.createElement('div');
let open = false;

overlay.classList.add('overlay');
main_content.append(overlay)

menu.addEventListener('click',()=>{
  open = !open;
  if(open){
    menu.classList.add('open')
    header.classList.add('open')
  }else{
    header.classList.remove('open')
    menu.classList.remove('open')
  }
})