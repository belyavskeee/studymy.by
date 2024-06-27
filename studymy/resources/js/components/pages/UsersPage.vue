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
      title: 'Преподаватели и модераторы'
    };
  },
  methods: {
    fetchStudyStudents() {
      axios.get(`/api/teachers`)
        .then(UsersResponse => {
          console.log(UsersResponse);
          this.valueMenu = UsersResponse.data.map(User => ({
            header: `${User.sname || ''} ${User.name || ''} ${User.patronymic ? User.patronymic || '' : ''}`,
            value: `${User.permission}`,
            path: `/profile/${User.id}`
          }));
          this.valueMenu.push({
            header: 'Добавить',
            path: '/add-user'
            });
        })
        .catch(error => {
          console.error('Error fetching users:', error);
        });
    }
  },
  mounted() {
    document.title = 'Учащиеся группы - beStudy';
    this.fetchStudyStudents();
  }
}
</script>

<style>
</style>
