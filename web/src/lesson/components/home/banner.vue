<template>
  <div class="c-banner">
    <swiper :options="swiperOption" ref="mySwiper">
      <!-- slides -->
      <swiper-slide v-for="(item,index) in data.list" :key="index" class="ani" swiper-animate-effect="shutter2">
        <router-link class="go" v-if="item.profile.type === 'lesson'"
                     :to="{path:`/lesson/detail?sn=${item.profile.sn}`}">
          <img :src="item.profile.banner"></router-link>
        <router-link class="go" v-if="item.profile.type === 'series'"
                     :to="{path:`/lesson/series?sn=${item.profile.sn}`}">
          <img :src="item.profile.banner"></router-link>
      </swiper-slide>
      <!-- Optional controls -->
      <div class="swiper-pagination" slot="pagination"></div>
      <div class="swiper-button-prev btn" slot="button-prev"></div>
      <div class="swiper-button-next btn" slot="button-next"></div>
      <!--<div class="swiper-scrollbar" slot="scrollbar"></div>-->
    </swiper>
  </div>
</template>

<script>
  import 'swiper/dist/css/swiper.min.css'
  import {swiper, swiperSlide} from 'vue-awesome-swiper'

  export default {
    name: 'lesson-home-banner',
    props: ['data'],
    components: {swiper, swiperSlide},
    data() {
      return {
        swiperOption: {
          on: {
            slideChange: function () {
              // alert(this.realIndex)
            }
          },
          notNextTick: true,
          simulateTouch: true, //  禁止鼠标模拟
          // swiper configs 所有的配置同swiper官方api配置
          autoplay: {
            delay: 7000,
            stopOnLastSlide: false,
            disableOnInteraction: false
          },
          onlyExternal: true,
          centeredSlides: true,
          spaceBetween: '2.7%',
          slidesPerView: 'auto',
          loop: true,
          effect: '"slide"',
          direction: 'horizontal',
          pagination: {
            el: '.swiper-pagination'
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
          }
        }
      }
    },
    computed: {
      swiper() {
        return this.$refs.mySwiper.swiper
      }
    },
    created() {
      // console.log(this.data)
    },
    mounted() {
      // current swiper instance
    }
  }
</script>

<style scoped>
  div {
    color: #333
  }

  .c-home .info {
    color: #f00
  }

  .c-home p {
    color: #0f0
  }

  .span {
    color: #00f
  }

  .bottom {
    width: 7rem;
    height: .3rem;
    margin: .1rem auto;
    background: #fff;
    text-align: center;
    color: #0FC8C3;
  }

  .more {
    height: 4.86rem;
    overflow: hidden;
  }

  .tab {
    z-index: 1;
  }

  .live {
    z-index: 0;
  }

  img {
    width: 100%;
    height: 100%;
    border-top-left-radius: .08rem;
    border-top-right-radius: .08rem;
  }

  .swiper-container {
    height: 3.12rem;
    padding-top: .3rem;
    background: linear-gradient(0deg, #999, #E5E5E5);
  }

  .swiper-slide {
    width: 6.3rem !important;
    height: 2.7rem;
  }

  .swiper-slide-next {
    /*animation: change 1.5s ease-in 2s;*/
    /*transition: transform 2s linear;*/
  }

  .swiper-pagination {
    height: .42rem;
    line-height: .42rem;
    background: white;
    bottom: 0 !important;
  }

  .swiper-button-next, .swiper-button-prev {
    background-image: none;
    width: .4rem;
    height: 100%;
    top: 0;
  }

  .swiper-button-prev {
    left: 0;
  }

  .swiper-button-next {
    right: 0;
  }

  @-webkit-keyframes shutter2 {
    from {
      top: 100%;
    }
    to {
      top: 0;
    }
  }

  .shutter2 {
    -webkit-animation: shutter2 0.5s forwards;
    animation: shutter2 0.5s forwards;
  }

  .go {
    display: inline-block;
    width: 100%;
    height: 100%;
  }
</style>
