<template>
  <div class="auth-block main-block" style="overflow: hidden;">
          <div class="list-settings" data-aos="fade-right">
            <h1 v-if="title" class="title-menu">{{ title }}</h1>
              <div class="item-settings-list" v-for="option in options" :key="option.header" @click="navigateToPage(option.path)">
                  <h3>{{ option.header }}</h3>
                  <p>{{ option.value }}</p>
              </div>
          </div>
  </div>
</template>

<script>
import AOS from 'aos';
import 'aos/dist/aos.css';

export default {
  props: {
    options: {
      type: Array,
      required: true
    },
    title: {
      type: String
    }
  },
  data() {
    return {
     
    };
  },
  methods: {
      navigateToPage(path) {
          this.$router.push(path);
      }
  },
mounted() {
  AOS.init();
}
};
</script>

<style>
.title-menu {
  font-family: 'rubick-regular', Georgia, serif;
  text-align: center;
  font-size: 16px;
  padding: 10px 0;
  color: #4f6384;
}
</style>
