<template>
    <my-header/>
    <add-form 
      :options="valueMenu" 
      :title="title" 
      :selecters="valueSelecters" 
      @form-submitted="handleFormSubmission"
    />
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
          { label: 'Номер группы', id: 'name_group' },
          { label: 'Курс', id: 'kyrs' },
          { label: 'Аудитория', id: 'audience' },
          { label: 'Тип обучения', id: 'type_education' },
        ],
        title: 'Добавление группы',
        valueSelecters: [
          {
            label: 'Специальность',
            id: 'speciality_id',
            values: [],
          }
        ],
        formData: {}
      };
    },
    methods: {
      fetchSpecialties() {
        axios.get('/api/specialties')
          .then(response => {
            if (response.data && Array.isArray(response.data.specs)) {
              const specialties = response.data.specs.map(spec => ({
                id: spec.id,
                value: spec.full_name
              }));
              this.valueSelecters.find(sel => sel.id === 'speciality_id').values = specialties;
            } else {
              console.error('Unexpected response format:', response.data);
            }
          })
          .catch(error => {
            console.error('Error fetching specialties:', error);
          });
      },
      handleFormSubmission(formData) {
        const request = this.formData.id 
          ? axios.put(`/api/groups/${this.formData.id}`, formData) 
          : axios.post('/api/groups', formData);
        request
          .then(response => {
            console.log('Group saved:', response.data);
          })
          .catch(error => {
            console.error('Error saving group:', error);
          });
      }
    },
    mounted() {
      this.fetchSpecialties();
  
      const groupId = this.$route.params.id;
      if (groupId) {
        axios.get(`/api/groups/${groupId}`)
          .then(response => {
            if (response.data) {
              this.formData = response.data;
              this.title = 'Редактирование группы';
            } else {
              console.error('Unexpected response format:', response.data);
            }
          })
          .catch(error => {
            console.error('Error fetching group data:', error);
          });
      }
    }
  };
  </script>
  
  <style>
  /* Добавьте любые стили, которые нужны для вашего компонента */
  </style>
  