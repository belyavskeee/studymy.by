<template>
    <my-header/>
    <div class="auth-block main-block" style="overflow: hidden;">
      <div class="list-settings" data-aos="fade-right">
        <h1 class="title-menu">Вы действительно хотите удалить предмет?</h1>
        <div class="item-settings-list" @click="deleteSubject">
          <h3>Да, удалить</h3>
          <p></p>
        </div>
      </div>
    </div>
    <my-footer/>
  </template>
  
  <script>
  import AOS from 'aos';
  import 'aos/dist/aos.css';
  import axios from 'axios';
  
  export default {
    data() {
      return {};
    },
    methods: {
      navigateToPage() {
        this.$router.go(-1);
      },
      deleteSubject() {
        // Вставьте ID предмета, который нужно удалить
        const subjectId = this.$route.params.id;
        axios.delete(`/api/subjects/${subjectId}`)
          .then(response => {
            console.log('Предмет удалён:', response.data);
            this.navigateToPage(); // Перенаправление на страницу успеха
          })
          .catch(error => {
            console.error('Ошибка при удалении предмета:', error);
          });
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
  