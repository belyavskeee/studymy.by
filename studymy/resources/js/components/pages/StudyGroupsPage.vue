<template>
    <my-header />
    <menu-inline :options="valueMenu" />
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
      valueMenu: []
    };
  },
  methods: {
    fetchStudyGroups() {
      axios.get('/api/groups')
        .then(response => {
          this.valueMenu = response.data.groups.map(group => ({
            header: group.name_group,
            path: `/groups/study-groups/${group.id}`
          }));
          this.valueMenu.push({
              header: 'Добавить группу',
              path: '/add-group'
            });
        })
        .catch(error => {
          console.error('Error fetching study groups:', error);
        });
    }
  },
  mounted() {
    document.title = 'Учебные группы - beStudy';
    this.fetchStudyGroups();
  }
}
</script>

<style>
/* Ваши стили */
</style>
