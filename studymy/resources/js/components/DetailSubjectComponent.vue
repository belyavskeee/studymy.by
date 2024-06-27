<template>
  <div class="auth-block main-block main-block-journal">
    <div class="buttons journal-btns">
      <input @click="goBack" id="back-arrow" class="journal-prev" type="button">
      <select class="journal-type" id="journal-type" v-model="selectedJournalType">
        <option value="Лекция">Лекции</option>
        <option value="Практическая">Практические</option>
        <option value="Курсовая">Курсовые работы</option>
      </select>

      <template v-if="selectedJournalType != 'Курсовая'">
        <input id="journal-name-date" type="button" :value="formattedDate">
        <input id="journal-name-group" type="button" :value="groupName">
        <div class="name-theme button-def-h40" id="name-theme">
          <textarea class="textarea-inner" :disabled="userPermission !== 'Студент'" v-model="lectureTheme" rows="1" placeholder="Добавьте тему занятия"></textarea>
        </div>
        <input id="journal-hours" type="button" :value="lectureHours">
        <click-button-component 
          :disabled="userPermission !== 'Студент'"
          :options="valueButton"
          :lectureId="lecture.id"
          :OKR="lecture.OKR"
          @okr-changed="updateLectureOKR"
        />
      </template>
      <template v-if="selectedJournalType == 'Курсовая'">
        <input id="journal-name-group" type="button" :value="subjectName">
        <input id="journal-name-group" type="button" :value="groupName">
        <input type="date" v-model="courseworkDate" :disabled="issetKyrsovaya || userPermission !== 'Студент'">
        <input @click="actionsCoursework" class="name-theme" type="button" :disabled="userPermission !== 'Студент'" :value="issetKyrsovaya ? 'Удалить' : 'Добавить'">
      </template>
    </div>
    <div v-if="selectedJournalType != 'Курсовая'" class="buttons journal-btns" style="margin-top: 10px; margin-bottom: 5px; margin-left: auto; margin-right: auto;">
      <input id="journal-name-group" type="button" :value="subjectName">
      <div class="journal-homework" id="homework">
        <p style="margin: auto 0;">Домашнее задание:</p>
        <textarea class="textarea-inner textarea-homework" :disabled="userPermission !== 'Студент'" v-model="lectureHomework" cols="30" rows="1" placeholder="Отсутствует"></textarea>
      </div>
      <!-- <label id="customFileUpload" for="fileToUpload" class="custom-file-upload input-files-journal">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <p>Прикрепить файл</p>
      </label> -->
      <section class="lists-with-screenshots lists-with-files-journal"></section>
    </div>
    <journal-component 
      :journalType="selectedJournalType" 
      :lectures="filteredLectures" 
      :group="group" 
      :students="group.students"
      :lecture="lecture"
      @lecture-selected="handleLectureSelected"
    />
  </div>
</template>

<script>
import JournalComponent from "@/components/JournalComponent.vue";
import ClickButtonComponent from "@/components/UI/ClickButtonComponent.vue";
import axios from 'axios';
import { format } from 'date-fns';
import { ru } from 'date-fns/locale';

export default {
  components: {
    JournalComponent,
    ClickButtonComponent
  },
  data() {
    return {
      lecture: {},
      lectures: [],
      subject: {},
      group: {
        students: []
      },
      selectedJournalType: 'Лекция',
      issetKyrsovaya: false,
      courseworkDate: '', // Новое поле для хранения даты курсовой работы
      lectureTheme: '',
      lectureHomework: '',
      valueButton: [
        { value: null, input: 'ОКР: нет' },
        { value: '1', input: 'ОКР: Первый час' },
        { value: '2', input: 'ОКР: Второй час' },
      ]
    };
  },
  computed: {
    formattedDate() {
      return this.lecture.date ? format(new Date(this.lecture.date), 'dd.MM', { locale: ru }) : '-';
    },
    groupName() {
      return this.group.name_group ? `гр. ${this.group.name_group}` : '-';
    },
    lectureHours() {
      return this.lecture.hours ? `${this.lecture.hours} часа` : '-';
    },
    subjectName() {
      return this.subject.short_name ? `${this.subject.short_name}` : '-';
    },
    filteredLectures() {
      return Array.isArray(this.lectures) ? this.lectures.filter(lecture => lecture.type === this.selectedJournalType) : [];
    }
  },
  methods: {
    fetchLecture(id) {
      axios.get(`/api/lectures/${id}`)
        .then(response => {
          this.lecture = response.data;
          this.subject = response.data.subject || {};

          this.lectureTheme = response.data.theme || '';
          this.lectureHomework = response.data.homework || '';

          if (this.subject.group_id) {
            return axios.get(`/api/groups/${this.subject.group_id}`);
          }
          return Promise.resolve({ data: {} });
        })
        .then(response => {
          this.group = response.data.group;
          console.log(this.group);

          if (this.group.id) {
            return axios.get(`/api/groups/${this.group.id}/students`);
          }
          return Promise.resolve({ data: [] });
        })
        .then(response => {
          this.group.students = response.data.students;

          if (this.subject.id) {
            return axios.get(`/api/subjects/${this.subject.id}/lectures`);
          }
          return Promise.resolve({ data: [] });
        })
        .then(response => {
          this.lectures = response.data.lectures;
          this.checkForCoursework(); // Проверяем наличие курсовых работ
          
          if (this.subject.id) {
            return axios.get(`/api/subjects/${this.subject.id}/marks`);
          }
          return Promise.resolve({ data: [] });
        })
        .then(response => {
          const marks = response.data.marks || [];
          this.group.students.forEach(student => {
            student.marks = marks.filter(mark => mark.student_id === student.id);
          });
          //console.log('родительский компонент ', this.group.students);
        })
        .catch(error => {
          if (error.response) {
            console.error('Error response:', error.response);
          } else if (error.request) {
            console.error('Error request:', error.request);
          } else {
            console.error('Error message:', error.message);
          }
        });
    },
    checkForCoursework() {
      const coursework = this.lectures.find(lecture => lecture.type === 'Курсовая' && lecture.subject_id === this.subject.id);
      if (coursework) {
        this.issetKyrsovaya = true;
        this.courseworkDate = coursework.date;
      } else {
        this.issetKyrsovaya = false;
        this.courseworkDate = '';
      }
    },
    updateLectureOKR(newOKR) {
      axios.put(`/api/lectures/${this.lecture.id}`, { OKR: newOKR })
        .then(response => {
          console.log('Lecture OKR updated:', response.data);
          this.lecture.OKR = newOKR;  // Обновляем локальное состояние
          // Обновляем OKR в списке лекций
          const lectureIndex = this.lectures.findIndex(lec => lec.id === this.lecture.id);
          if (lectureIndex !== -1) {
            this.lectures[lectureIndex].OKR = newOKR;
          }
        })
        .catch(error => {
          console.error('Error updating lecture OKR:', error);
        });
    },
    handleLectureSelected(lectureId) {
      this.$router.push({ name: 'DetailSubject', params: { id: lectureId } });
      this.fetchLecture(lectureId);
    },
    actionsCoursework() {
      const coursework = this.lectures.find(lecture => lecture.type === 'Курсовая' && lecture.subject_id === this.subject.id);
      if (coursework) {
        // Удаление курсовой работы
        axios.delete(`/api/lectures/${coursework.id}`)
          .then(response => {
            console.log('Курсовая работа удалена:', response.data);
            this.issetKyrsovaya = false;
            this.courseworkDate = ''; // Сброс даты курсовой работы
            this.fetchLecture(this.$route.params.id); // Обновить данные
          })
          .catch(error => {
            console.error('Ошибка при удалении курсовой работы:', error);
          });
      } else {
        // Создание новой курсовой работы
        const newLecture = {
          type: 'Курсовая',
          date: this.courseworkDate,
          subject_id: this.subject.id,
          hours: 1,
        };

        axios.post(`/api/lectures`, newLecture)
          .then(response => {
            console.log('Новая курсовая работа создана:', response.data);
            this.issetKyrsovaya = true;
            this.courseworkDate = response.data.date; // Установить дату курсовой работы
            this.lecture = response.data; // Установить созданную лекцию как текущую
            this.fetchLecture(this.$route.params.id); // Обновить данные
          })
          .catch(error => {
            console.error('Ошибка при создании курсовой работы:', error);
          });
      }
    },
    goBack() {
      this.$router.go(-1);
    },
    handleMarkUpdated(updatedStudent) {
      const studentIndex = this.group.students.findIndex(student => student.id === updatedStudent.id);
      if (studentIndex !== -1) {
        this.$set(this.group.students, studentIndex, updatedStudent);
      }
    },
    updateLectureData(updatedFields) {
      axios.put(`/api/lectures/${this.lecture.id}`, updatedFields)
        .then(response => {
          console.log('Lecture data updated:', response.data);
          Object.assign(this.lecture, updatedFields); // Обновляем локальное состояние лекции
        })
        .catch(error => {
          console.error('Error updating lecture data:', error);
        });
    }
  },
  watch: {
    lectures() {
      this.checkForCoursework();
    },
    lecture(newLecture) {
      this.lectureTheme = newLecture.theme || '';
      this.lectureHomework = newLecture.homework || '';
    },
    lectureTheme(newTheme) {
      this.updateLectureData({ theme: newTheme });
    },
    lectureHomework(newHomework) {
      this.updateLectureData({ homework: newHomework });
    }
  },
  mounted() {
    this.fetchLecture(this.$route.params.id);
  }
};
</script>

<style>
/* Ваши стили */
</style>
