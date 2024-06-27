<template>
  <div class="block-journal">
    <div class="journal">
      <table>
        <thead>
          <tr>
            <th class="fio">Фамилия и Имя</th>
            <th 
              v-for="lecture in lectures" 
              :key="lecture.id" 
              :class="['date', { 'active-date': lecture.id == $route.params.id }]" 
              :colspan="lecture.hours == 2 && lecture.type == 'Лекция' ? 2 : 1"
              @click="selectLecture(lecture.id, lecture.type)">
              {{ formatDate(lecture.date) }}
            </th>
            <th v-if="journalType === 'Лекция'" class="info-student missed-title">Пропуски</th>
            <th v-if="journalType === 'Лекция'" class="info-student GPA-title">Ср. балл</th>
            <th v-if="journalType === 'Лекция'" class="info-student final-mark-title">Семестр</th>
            <th v-if="journalType === 'Практическая'" class="info-student completion-percentage-title">Выполнено работ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in students" :key="student.id">
            <td class="table-fio">{{ student.sname }} {{ student.name }} {{ student.patronymic }}</td>
            <template v-for="lecture in lectures" :key="lecture.id + '-' + student.id">
              <td v-for="hour in lecture.hours" :class="{ 'OKR': lecture.OKR == hour }" :key="lecture.id + '-' + student.id + '-' + hour">
                <input
                  :disabled="userPermission !== 'Студент'"
                  :class="{ 'error': markErrors.has(`${student.id}-${lecture.id}-${hour}`) }"
                  :id="`${student.id}-${lecture.id}-${hour}`"
                  type="text"
                  :value="getMark(student.id, lecture.id, hour)"
                  @change="updateMark(lecture.subject_id, student.id, lecture.id, getDisplayValue(lecture, hour), hour, $event.target.value, userId)"
                />
              </td>
            </template>
            <td v-if="journalType === 'Лекция'" class="info-student">{{ calculateMissedClasses(student.id) }}</td>
            <td v-if="journalType === 'Лекция'" class="info-student">{{ calculateGPA(student.id) }}</td>
            <td v-if="journalType === 'Лекция'" class="info-student final-mark">
              <input type="text" 
              :disabled="userPermission !== 'Студент'"
              :value="getMark(student.id, 1, 1)"
              @change="updateMark(lecture.subject_id, student.id, 1, 'Семестровая', 1, $event.target.value, userId)"
              ></td>
            <td v-if="journalType === 'Практическая'" class="info-student completion-percentage">
              <p>{{ completionPercentage(student.id) }}%</p>
              <div class="progress-bar-journal">
                <div class="progress" :style="{ width: completionPercentage(student.id) + '%' }"></div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { format } from 'date-fns';
import { ru } from 'date-fns/locale';
import axios from 'axios';
import { mapGetters } from 'vuex';

export default {
  props: {
    journalType: String,
    lectures: Array,
    group: Object,
    students: Array,
    lecture: Object
  },
  data() {
    return {
      markErrors: new Set()
    };
  },
  watch: {
    lecture: {
      deep: true,
      handler(newLecture) {
        this.updateLectureClass(newLecture);
      }
    }
  },
  methods: {
    selectLecture(lectureId, lectureType) {
      if (lectureType == "Лекция") {
        this.$emit('lecture-selected', lectureId);
      } 
    },
    formatDate(date) {
      return format(new Date(date), 'dd.MM', { locale: ru });
    },
    completionPercentage(studentId) {
      const student = this.students.find(s => s.id === studentId);
      if (!student || !student.marks) return 0;

      const totalPracticalLectures = this.lectures.filter(lecture => lecture.type === 'Практическая').length;
      if (totalPracticalLectures === 0) return 0;

      const completedPracticalMarks = student.marks.filter(m => m.type === 'Практическая' && m.mark).length;
      return Math.round((completedPracticalMarks / totalPracticalLectures) * 100);
    },
    calculateMissedClasses(studentId) {
      const student = this.students.find(s => s.id === studentId);
      if (!student || !student.marks) return '-';
      const missedClasses = student.marks.filter(m => m.mark === 'н').length;
      return missedClasses;
    },
    calculateGPA(studentId) {
      const student = this.students.find(s => s.id === studentId);
      if (!student || !student.marks) return '-';

      const marks = student.marks.filter(m => m.mark !== 'н' && m.mark !== 'зач' && m.type !== 'Семестровая' && m.type !== 'Курсовая');
      const okrMark = marks.find(m => m.type === 'ОКР');
      const otherMarks = marks.filter(m => m.type !== 'ОКР');

      const totalOtherMarks = otherMarks.reduce((sum, m) => sum + Number(m.mark), 0);
      const averageOtherMarks = otherMarks.length > 0 ? Number((totalOtherMarks / otherMarks.length).toFixed(0)) : '-';
      //console.log(averageOtherMarks);

      const averageGPA = okrMark
        ? Number(((averageOtherMarks + Number(okrMark.mark)) / 2).toFixed(2))
        : averageOtherMarks;
        //console.log(((averageOtherMarks + Number(okrMark.mark))/ 2).toFixed(2));
      return averageGPA;
    },
    getMark(studentId, lectureId, hour) {
      const student = this.students.find(s => s.id === studentId);
      if (!student || !student.marks) return '';
      const mark = student.marks.find(m => m.lecture_id === lectureId && m.hour === hour);
      return mark ? mark.mark : '';
    },
    setMark(value, studentId, lectureId, hour) {
      const student = this.students.find(s => s.id === studentId);
      if (!student) return;
      if (!student.marks) student.marks = [];
      const markIndex = student.marks.findIndex(m => m.lecture_id === lectureId && m.hour === hour);
      if (markIndex !== -1) {
        student.marks[markIndex].mark = value;
      } else {
        student.marks.push({ lecture_id: lectureId, hour, mark: value });
      }
    },
    updateMark(subjectId, studentId, lectureId, lectureType, hour, value, userId) {
      let isValid = true;
      const markData = {
        student_id: studentId,
        teacher_id: userId,
        lecture_id: lectureId,
        subject_id: subjectId,
        hour: hour,
        type: lectureType,
        mark: value || ''
      };

      if (value !== null && value !== '') {
        if (value.toString().toLowerCase() !== 'н' && value.toString().toLowerCase() !== 'зач') {
          const parsedValue = parseFloat(value);
          if (isNaN(parsedValue) || parsedValue < 0 || parsedValue > 10) {
            isValid = false;
            this.markErrors.add(`${studentId}-${lectureId}-${hour}`);
          }
        }
      }

      if (isValid) {
        axios.post('/api/marks', markData)
          .then(response => {
            console.log('Mark updated:', response.data);
            this.setMark(value, studentId, lectureId, hour);
            this.$emit('mark-updated', { ...this.students.find(s => s.id === studentId) });
            this.markErrors.delete(`${studentId}-${lectureId}-${hour}`);
          })
          .catch(error => {
            console.error('Error updating mark:', error);
          });
      } else {
        console.error('Invalid mark value:', value);
      }
    },
    updateLectureClass(newLecture) {
      this.lectures.forEach(lecture => {
        lecture.active = lecture.id === newLecture.id;
      });
    },
    getDisplayValue(lecture, hour) {
      return lecture.OKR === hour ? 'ОКР' : lecture.type;
    }
  }, 
  computed: {
    ...mapGetters(['userId'])
  },
};
</script>

<style>
/* Ваши стили */
.journal {
	padding: 0;
	margin: 0;
	width: 100%;
	overflow: auto;
}
.journal table {
	border-collapse: separate;
	border-spacing: 8px;
	empty-cells: show;
	font-size: 12px;
	color: #617492;
	font-size: 11px;
	font-family: 'rubick-regular', Georgia, serif;
}
.journal td {
	background-color: #dbe6f4;
	width: fit-content;
	border-radius: 50px;	
	min-width: 27px;
	height: 27px;
}
.journal th {
    font-size: 10px;
	background-color: #dbe6f4;
	width: fit-content;
	border-radius: 50px;	
	min-width: 27px;
	height: 27px;
    font-weight: lighter;
}
.journal .fio {
    font-size: 12px;
    font-weight: bold;
    cursor: default;
}
.table-fio {
	border-spacing: 0px;
	min-width: 150px !important;
	width: fit-content !important;
	padding: 0 15px;
	text-align: left;
}
.journal input {
	outline: none;
	width: 27px;
    height: 27px;
	border-radius: 50px;
	border: none;
    padding: 0;
    text-align: center;
    background-color: #dbe6f4;
    color: #617492;
	font-size: 11px;
	font-family: 'rubick-regular', Georgia, serif;
	cursor: pointer;
	-webkit-transition-duration: 0.3s; /* Safari */
	transition-duration: 0.3s;
}
.journal input:hover {
	background-color: #c8cfdd;
}
.journal input:focus {
	outline: none;
	box-shadow: inset 0 0 0 1.5px #617492;
}
.journal .date {
    cursor: pointer;
	-webkit-transition-duration: 0.3s; /* Safari */
	transition-duration: 0.3s;
}
.journal .date:hover {
    background-color: #c8cfdd;
}
.journal .date.active-date, .missed-title, .GPA-title, .final-mark-title, .completion-percentage-title {
    background-color: #4f6384 !important;
    color: white;
}
.journal .info-student {
    position: relative;
    text-align: center;
    justify-content: center;
    padding: 0 5px;
    cursor: default;
}
.journal .info-student p {
    margin: 0;
}
.journal .info-student.final-mark {
    width: fit-content;
    padding: 0;
}
.journal .info-student.final-mark input{
    width: 70px;
    padding: 0;
}
.journal .info-student.completion-percentage-title{
    padding: 0 10px;
}
.progress-bar-journal {
    background-color:#c8cfdd;
    position: absolute;
    width: 80%;
    height: 2px;
    margin: 2px auto auto auto;
    left: 50%;
    transform: translate(-50%);
}
.progress-bar-journal .progress {
    width: 84%; 
    height: 2.5px;
    background-color: #4f6384;
    border-radius: 50px;
}
.journal .OKR input {
  background-color: #c8cfdd;
}
.journal .error {
  box-shadow: inset 0 0 0 1.5px red;
}
</style>
