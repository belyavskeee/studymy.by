<template>
  <my-header/>
  <add-form :options="valueMenu" :title="title" :selecters="valueSelecters" @form-submitted="handleFormSubmission"/>
  <my-footer/>
</template>

<script>
import AddForm from "@/components/UI/AddForm.vue";
import axios from 'axios';

export default {
  components: {
    AddForm
  },
  data() {
    return {
      valueMenu: [
        { label: 'Фамилия', id: 'sname' },
        { label: 'Имя', id: 'name' },
        { label: 'Отчество', id: 'patronymic' },
        { label: 'Одноразовый код', id: 'user_password' },
      ],
      title: 'Добавление пользователя',
      valueSelecters: [
        {
          label: 'Права',
          id: 'permission',
          values: [
            { id: 'Студент', value: 'Студент' },
            { id: 'Преподаватель', value: 'Преподаватель' },
            { id: 'Модератор', value: 'Модератор' },
          ],
        },
        {
          label: 'Учебная группа',
          id: 'group_id',
          values: []
        },
      ]
    };
  },
  methods: {
    fetchStudyGroups() {
      axios.get('/api/groups')
        .then(response => {
          console.log('Response from server:', response.data.groups); // Добавьте эту строку для отладки
          if (Array.isArray(response.data.groups)) {
            const groups = response.data.groups.map(group => ({
              id: group.id,
              value: group.name_group
            }));
            this.valueSelecters[1].values = [{ id: '', value: 'Нету' }, ...groups];
          } else {
            console.error('Unexpected response format:', response.data.groups);
          }
        })
        .catch(error => {
          console.error('Error fetching study groups:', error);
        });
    },
    handleFormSubmission(formData) {
      axios.post('/api/users', formData)
        .then(response => {
          console.log('User created:', response.data);
        })
        .catch(error => {
          console.error('Error creating user:', error);
        });
    }
  },
  mounted() {
    document.title = 'Добавление пользователя - beStudy';
    this.fetchStudyGroups();
  }
};
</script>

<style>
</style>
