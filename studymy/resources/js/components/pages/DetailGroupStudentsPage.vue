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
    fetchStudyStudents() {
      axios.get(`/api/groups/${this.$route.params.id}`)
        .then(response => {
          this.group = response.data.group;
          this.title = `Учебная группа - ${this.group.name_group}`;
          return axios.get(`/api/groups/${this.$route.params.id}/students`);
        })
        .then(studentsResponse => {
          this.valueMenu = studentsResponse.data.students.map(student => ({
            header: `${student.sname || ''} ${student.name || ''} ${student.patronymic ? student.patronymic || '' : ''}`,
            path: `/profile/${student.id}`
          }));
          this.valueMenu.push({
            header: 'Добавить',
            path: '/add-user'
            });
        })
        .catch(error => {
          console.error('Error fetching study group or students:', error);
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
