<template>
    <my-header />
    <menu-inline :options="valueMenu" :title="title" />
    <my-footer />
  </template>
  
  <script>
  import MenuInline from "@/components/UI/MenuInline.vue";
  import axios from 'axios';
  
  export default {
    components: {
      MenuInline
    },
    data() {
      return {
        valueMenu: [],
        title: ''
      };
    },
    methods: {
      fetchStudySubjects() {
        axios.get(`/api/groups/${this.$route.params.id}/subjects`)
          .then(response => {
            this.valueMenu = response.data.map(subject => ({
              header: `${subject.name}`,
              value: `${subject.semester} семестр 20${subject.period_start} - 20${subject.period_end} года`,
              path: `/add-subject/${subject.id}`,
            }));
            axios.get(`/api/groups/${this.$route.params.id}`)
        .then(response => {
          this.group = response.data.group;
          this.title = `Учебная группа - ${this.group.name_group}`;
        })
            this.valueMenu.push({
              header: 'Добавить предмет',
              path: '/add-subject'
            });
          })
          .catch(error => {
            console.error('Error fetching subjects:', error);
          });
      }
    },
    mounted() {
      document.title = 'Учебные предметы - beStudy';
      this.fetchStudySubjects();
    }
  }
  </script>
  
  <style>
  </style>
  