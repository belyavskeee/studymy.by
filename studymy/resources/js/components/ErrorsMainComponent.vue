<template>
    <div class="auth-block main-block timetable-main">
      <div style="padding-bottom: 20px; margin: 0 auto;">
        <div class="buttons journal-btns btns-error">
          <div class="name-theme date-col-error" @click="toggleSortOrder">
            <p>Дата</p>
            <span v-if="sortOrder === 'desc'">&#9660;</span>
            <span v-else>&#9650;</span>
          </div>
          <div><p>Номер</p></div>
          <div><p>id Пользователя</p></div>
          <div><p>Системная информация</p></div>
          <div><p>Описание ошибки</p></div>
          <div><p>Прикрепленные файлы</p></div>
        </div>
        <div v-for="(error, index) in sortedErrors" :key="index" class="buttons journal-btns btns-error">
          <div class="name-theme"><p>{{ formatDate(error.created_at) }}</p></div>
          <div><p>{{ error.id }}.</p></div>
          <div><p>{{ error.user_id || 'Null' }}</p></div>
          <div style="max-width: 200px;" class="descripsion-error"><p>{{ error.device_info_user || 'Null' }}</p></div>
          <div class="descripsion-error"><p>{{ error.text }}</p></div>
          <div class="error-images">
            <div v-if="error.files && error.files.length" v-for="(file, fileIndex) in error.files" :key="fileIndex">
              <p><a :href="getFilePath(file)" target="_blank">{{ getFileName(file) }}</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        errors: [],
        sortOrder: 'desc'
      };
    },
    computed: {
      sortedErrors() {
        return this.errors.sort((a, b) => {
          const dateA = new Date(a.created_at);
          const dateB = new Date(b.created_at);
          return this.sortOrder === 'desc' ? dateB - dateA : dateA - dateB;
        });
      }
    },
    methods: {
      async fetchErrors() {
        try {
          const response = await axios.get('/api/errors');
          this.errors = response.data;
        } catch (error) {
          console.error('Error fetching errors:', error);
        }
      },
      toggleSortOrder() {
        this.sortOrder = this.sortOrder === 'desc' ? 'asc' : 'desc';
      },
      formatDate(date) {
        const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        return new Date(date).toLocaleDateString('ru-RU', options);
      },
      getFilePath(file) {
        // Проверяем наличие файла и формируем правильный путь
        return `/uploads-errors/${file}`;
      },
      getFileName(path) {
        return path.split('/').pop();
      }
    },
    created() {
      this.fetchErrors();
    }
  };
  </script>
  
  <style>
  .btns-error {
    margin: 0;
  }
  .descripsion-error {
    max-width: 300px;
    max-height: fit-content !important;
  }
  .date-col-error {
    display: flex;
    align-items: center;
  }
  .error-images {
    max-width: 150px;
    overflow: hidden;
  }
  </style>
  