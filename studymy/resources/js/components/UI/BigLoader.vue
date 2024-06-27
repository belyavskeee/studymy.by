<template>
    <div class="big-loading">
        <swiper
            :allowTouchMove="true"
            :initialSlide="1"
            :autoplay="true"
            :effect="'creative'"
            :creativeEffect="{
            prev: {
                shadow: true,
                translate: ['-100%', 0, -1],
            },
            next: {
                shadow: true,
                translate: ['100%', 0, 0],
            },
            }"
            :modules="modules"
            class="mySwiper3"
        >
            <swiper-slide></swiper-slide>
            <swiper-slide class="main-div">
                <div>
			        <h1>beStudy.by</h1>
		        </div>
            </swiper-slide>
            <swiper-slide></swiper-slide>
        </swiper>
        <!-- <div class="transparnent-loading"></div> -->
	</div>
</template>
   
<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
// Import Swiper styles
import 'swiper/css';

import 'swiper/css/effect-creative';

// import required modules
import { EffectCreative } from 'swiper/modules';

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
    return {
      modules: [EffectCreative],
    };
  },
};
//    import AuthRegister from "@/components/AuthRegister.vue";
//    import MyFooter from "@/components/MyFooter.vue";
//    export default {
//        components: {
//            AuthRegister,
//            MyFooter,
//        },
//    }
</script>
   
<style>
     .mySwiper3 {
        width: 100%;
        height: 100%;
        top: 0;
        }
   .big-loading {
    z-index: 10;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
   }
   .big-loading .main-div {
    background-color: #4E6384;
   }

   .big-loading h1 {
    user-select: none;
    color: white;
	vertical-align: middle;
	margin: 0;
	text-align: center;
	position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
	font-size: 100px;
	font-family: 'raleway-black', 'courier new', sans-serif;
   }

   @media all and (max-width: 900px) {
    .big-loading h1 {
	font-size: 50px;
    }
   }
</style>
   