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
      valueMenu: [
        //{ header: 'Список обучающихся', path: '/groups/study-groups/' + this.$route.params.id + '/students' },
        //{ header: 'Учебные предметы', path: '/groups/study-groups/'+ this.$route.params.id +'/subjects' },
        // { header: 'Ведомость группы по предметам', path: '/groups/study-groups' },
        { header: 'Изменить', path: '/user/' + this.$route.params.id + '/edit' },
        { header: 'Удалить', path: '/user/'  + this.$route.params.id + '/delete'},
      ],
      title: ''
    };
  },
  methods: {
    fetchStudyStudents() {
      axios.get(`/api/user/${this.$route.params.id}`)
        .then(response => {
          this.user = response.data.user;
          this.title = `${this.user.sname} ${this.user.name} ${this.user.patronymic} - ${this.user.permission}`;
        if (this.user.group_id) {
            return axios.get(`/api/groups/${this.user.group_id}`);
          }
          return Promise.resolve({ data: [] });
        })
         .then(response => {
            this.group = response.data.group;
            console.log(this.group);
            this.title += ` - ${this.group.name_group}`;
        })
        .catch(error => {
          console.error('Error fetching study group or students:', error);
        });
    }
  },
  mounted() {
    document.title = 'Пользователь - beStudy';
    this.fetchStudyStudents();
  }
}
</script>

<style>
</style>
