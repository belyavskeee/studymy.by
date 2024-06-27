<template>
  <div class="auth-block main-block">
    <div class="account">
      <h3>{{ userFullName }}</h3>
      <div class="timetable">
        <div class="block-timetable">
          <input id="today-date-timetable" class="day-of-week-timetbl" type="button" :value="todayDate" disabled>
          <div v-for="(subject, idx) in todayTimetable" :key="idx" class="block-timetable-subject"  @click="navigateToPageSubject(subject.subject_id)">
            <div class="block-timetable-subject-inform">
              <p>{{ subject.time }}<br>{{ subject.name }}<br>{{ subject.teacher1 }}<br>{{ subject.teacher2 }}</p>
            </div>
            <div class="information-sub-timetable">
              <div>{{ subject.room }}</div>
              <div v-if="userPermission !== 'Студент'">{{ subject.group }}</div>
              <div>{{ subject.type }}</div>
            </div>
          </div>
        </div>
        <!-- следующий день -->
        <div class="block-timetable block-timetable-second">
          <input id="tomorrow-date-timetable" class="day-of-week-timetbl" type="button" :value="tomorrowDate" disabled>
          <div v-for="(subject, idx) in tomorrowTimetable" :key="idx"  class="block-timetable-subject"  @click="navigateToPageSubject(subject.subject_id)">
            <div class="block-timetable-subject-inform">
              <p>{{ subject.time }}<br>{{ subject.name }}<br>{{ subject.teacher1 }}<br>{{ subject.teacher2 }}</p>
            </div>
            <div class="information-sub-timetable">
              <div>{{ subject.room }}</div>
              <div v-if="userPermission !== 'Студент'">{{ subject.group }}</div>
              <div>{{ subject.type }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="datepicker">
      <div id='calendar'></div>
      <input class="button-defualt btn-calendar" type="button" value="Смотреть расписание" @click="viewTimetable">
    </div>
  </div>
</template>

<script>
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import { mapGetters } from 'vuex';

export default {
  data() {
    return {
      todayDate: '',
      tomorrowDate: '',
      todayTimetable: [],
      tomorrowTimetable: [],
      selectedDate: null,
      calendar: null,
    };
  },
  methods: {
    formatDate(date) {
      const options = { weekday: 'long', day: 'numeric', month: 'long' };
      return date.toLocaleDateString('ru-RU', options);
    },
    initializeCalendar() {
      if (this.calendar) {
        this.calendar.destroy();
      }
      var calendarEl = document.getElementById('calendar');
      this.calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: 'ru',
        firstDay: 1,
        headerToolbar: {
          start: 'prev',
          center: 'title',
          end: 'next',
        },
        dateClick: (info) => {
          var prevSelectedDay = document.querySelector('.selected-day');
          if (prevSelectedDay) {
            prevSelectedDay.classList.remove('selected-day');
          }
          info.dayEl.classList.add('selected-day');
          this.selectedDate = info.date;
        }
      });
      this.calendar.render();
    },
    viewTimetable() {
      if (!this.selectedDate) {
        alert('Пожалуйста, выберите дату в календаре.');
        return;
      }

      const selectedDateStr = this.selectedDate.toISOString().split('T')[0];
      const nextDayDate = new Date(this.selectedDate);
      nextDayDate.setDate(this.selectedDate.getDate() + 1);
      const nextDayDateStr = nextDayDate.toISOString().split('T')[0];

      this.todayDate = this.formatDate(this.selectedDate);
      this.tomorrowDate = this.formatDate(nextDayDate);

      this.fetchTimetable(selectedDateStr, nextDayDateStr);
    },
    fetchTimetable(today, tomorrow) {
      const userId = this.$store.getters.userId;
      const userPermission = this.$store.getters.userPermission;

      let todayApiUrl, tomorrowApiUrl;
      if (userPermission === 'Преподаватель' || userPermission === 'Модератор') {
        todayApiUrl = `/api/schedule/teacher/${userId}/date/${today}`;
        tomorrowApiUrl = `/api/schedule/teacher/${userId}/date/${tomorrow}`;
      } else if (userPermission === 'Студент') {
        const groupId = this.$store.getters.userGroup;
        console.log(groupId);
        todayApiUrl = `/api/schedule/group/${groupId}/date/${today}`;
        tomorrowApiUrl = `/api/schedule/group/${groupId}/date/${tomorrow}`;
      }

      Promise.all([
        fetch(todayApiUrl).then(res => res.json()),
        fetch(tomorrowApiUrl).then(res => res.json())
      ])
      .then(([todayTimetable, tomorrowTimetable]) => {
        this.todayTimetable = Array.isArray(todayTimetable) ? todayTimetable.map(this.transformSchedule) : [];
        this.tomorrowTimetable = Array.isArray(tomorrowTimetable) ? tomorrowTimetable.map(this.transformSchedule) : [];
      })
      .catch(error => {
        console.error('Error fetching timetable:', error);
      });
    },
    transformSchedule(schedule) {
      console.log(schedule);

      return {
        time: schedule.time,
        subject_id: schedule.subject_id,
        id: schedule.id,
        name: schedule.subject.name,
        teacher1: `${schedule.teacher1.sname} ${schedule.teacher1.name.charAt(0)}. ${schedule.teacher1.patronymic.charAt(0)}.`,
        teacher2: schedule.teacher2 ? `${schedule.teacher2.sname} ${schedule.teacher2.name.charAt(0)}. ${schedule.teacher2.patronymic.charAt(0)}.` : '',
        room: `${schedule.class_1}${schedule.class_2 ? `, ${schedule.class_2}` : ''}`,
        group: schedule.group.name_group,
        type: schedule.type
      };
    },
    updateInitialDates() {
      const currentDate = new Date();
      const nextDayDate = new Date();
      nextDayDate.setDate(currentDate.getDate() + 1);

      this.todayDate = this.formatDate(currentDate);
      this.tomorrowDate = this.formatDate(nextDayDate);

      // Инициализация расписания на текущий день при загрузке страницы
      this.selectedDate = currentDate;
      this.viewTimetable();
    },
    navigateToPageSubject(id) {
      console.log(id);
      this.$router.push({ name: 'Subject', params: { id } });
    },
  },
  mounted() {
    this.initializeCalendar();
    this.updateInitialDates();
  },
  watch: {
    '$route'() {
      this.$nextTick(() => {
        this.initializeCalendar();
      });
    }
  },
  computed: {
    ...mapGetters(['userFullName', 'userPermission', 'userId', 'userGroup'])
  },
};
</script>
