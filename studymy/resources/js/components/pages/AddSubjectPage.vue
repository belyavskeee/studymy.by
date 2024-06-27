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
        { label: 'Название', id: 'name' },
        { label: 'Короткое название', id: 'short_name' },
        { label: 'Семестр', id: 'semester' },
        { label: 'Начало учебного года', id: 'period_start' },
        { label: 'Конец учебного года', id: 'period_end' },
        { label: 'Количество часов предмета', id: 'hours' },
      ],
      title: 'Добавление предмета',
      valueSelecters: [
        {
          label: 'Первый преподаватель (Лектор):',
          id: 'teacher1',
          values: [],
        },
        {
          label: 'Второй преподаватель:',
          id: 'teacher2',
          values: [],
        },
        {
          label: 'Специальность',
          id: 'spec',
          values: [],
        },
        {
          label: 'Учебная группа',
          id: 'group_id',
          values: [],
        }
      ],
      subjectId: null,
      formData: {}
    };
  },
  methods: {
    fetchStudyGroups() {
      axios.get('/api/groups')
        .then(response => {
          if (response.data && Array.isArray(response.data.groups)) {
            const groups = response.data.groups.map(group => ({
              id: group.id,
              value: group.name_group
            }));
            this.valueSelecters.find(sel => sel.id === 'group_id').values = groups;
          } else {
            console.error('Unexpected response format:', response.data);
          }
        })
        .catch(error => {
          console.error('Error fetching study groups:', error);
        });
    },
    fetchTeachers() {
      axios.get('/api/teachers')
        .then(response => {
          console.log(response.data);
          if (response.data && Array.isArray(response.data)) {
            const teachers = response.data.map(teacher => ({
              id: teacher.id,
              value: `${teacher.sname} ${teacher.name} ${teacher.patronymic}`
            }));
            this.valueSelecters.find(sel => sel.id === 'teacher1').values = teachers;
            this.valueSelecters.find(sel => sel.id === 'teacher2').values = [{ id: '', value: 'Нету' }, ...teachers];
          } else {
            console.error('Unexpected response format:', response.data);
          }
        })
        .catch(error => {
          console.error('Error fetching teachers:', error);
        });
    },
    fetchSpecialties() {
      axios.get('/api/specialties')
        .then(response => {
          console.log(response.data);
          if (response.data && Array.isArray(response.data.specs)) {
            const specialties = response.data.specs.map(spec => ({
              id: spec.id,
              value: spec.full_name // Используйте соответствующее имя свойства, если необходимо
            }));
            this.valueSelecters.find(sel => sel.id === 'spec').values = specialties;
          } else {
            console.error('Unexpected response format:', response.data);
          }
        })
        .catch(error => {
          console.error('Error fetching specialties:', error);
        });
    },
    fetchSubjectData(id) {
      axios.get(`/api/subjects/${id}`)
        .then(response => {
          if (response.data && response.data.subject) {
            this.formData = response.data.subject;
            this.title = 'Редактирование предмета';
          } else {
            console.error('Unexpected response format:', response.data);
          }
        })
        .catch(error => {
          console.error('Error fetching subject data:', error);
        });
    },
    handleFormSubmission(formData) {
      const request = this.subjectId ? axios.put(`/api/subjects/${this.subjectId}`, formData) : axios.post('/api/subjects', formData);
      request
        .then(response => {
          console.log('Subject saved:', response.data);
        })
        .catch(error => {
          console.error('Error saving subject:', error);
        });
    }
  },
  mounted() {
    document.title = this.subjectId ? 'Редактирование предмета - beStudy' : 'Добавление предмета - beStudy';
    this.fetchStudyGroups();
    this.fetchTeachers();
    this.fetchSpecialties();

    this.subjectId = this.$route.params.id;
    if (this.subjectId) {
      this.fetchSubjectData(this.subjectId);
    }
  }
};
</script>

<style>
/* Добавьте любые стили, которые нужны для вашего компонента */
</style>