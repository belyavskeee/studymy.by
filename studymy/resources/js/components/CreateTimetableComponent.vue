<template>
    <div class="auth-block main-block timetable-main">
        <div class="buttons journal-btns">
            <div><p>Добавление расписания</p></div>
            <input type="date" v-model="selectedDate" @change="fetchSchedule">
            <input class="name-theme" type="button" value="Отправить" @click="submitSchedule">
        </div>
        <div class="timetable-groups">
            <div v-for="group in groups" :key="group.id" class="timetable-detail-group">
                <div class="info-group">
                    <p>{{ group.name_group }}</p>
                    <p>{{ group.audience }} ауд.</p>
                    <p>{{ group.specialty }}</p>
                </div>
                <div>
                    <div v-for="(lesson, index) in group.lessons" :key="index" class="timetable-class buttons journal-btns timetable-btns">
                        <div><p>{{ index + 1 }}.</p></div>
                        <select v-model="lesson.subject_id" @change="updateLessonInfo(group, lesson)">
                            <option v-for="subject in group.subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                        </select>
                        <section>
                            <section style="display: flex;">
                                <select v-model="lesson.hours">
                                    <option value="2">2 ч.</option>
                                    <option value="1">1 ч.</option>
                                </select>
                                <input type="time" v-model="lesson.start_time">
                            </section>
                            <select class="hours-and-dates" disabled>
                                <option selected>{{ lesson.completed_hours }}/{{ lesson.total_hours }} ч.</option>
                                <option v-for="date in lesson.previous_dates" :key="date" disabled>{{ date }}</option>
                            </select>
                        </section>
                        <section>
                            <section style="display: flex;">
                                <select v-model="lesson.teacher_1_id">
                                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.sname }} {{ teacher.name.charAt(0) }}. {{ teacher.patronymic.charAt(0) }}.</option>
                                    <option value="">-</option>
                                </select>
                                <select v-model="lesson.audience_1">
                                    <option v-for="audience in audiences" :key="audience" :value="audience">{{ audience }}</option>
                                    <option value="">-</option>
                                </select>
                            </section>
                            <section style="display: flex;">
                                <select v-model="lesson.teacher_2_id">
                                    <option value="">-</option>
                                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.sname }} {{ teacher.name.charAt(0) }}. {{ teacher.patronymic.charAt(0) }}.</option>
                                </select>
                                <select v-model="lesson.audience_2">
                                    <option value="">-</option>
                                    <option v-for="audience in audiences" :key="audience" :value="audience">{{ audience }}</option>
                                </select>
                            </section>
                        </section>
                    </div>
                    <div style="width: -webkit-fill-available;" class="timetable-class buttons journal-btns timetable-btns">
                        <input style="width: -webkit-fill-available;" type="button" value="Добавить занятие" @click="addLesson(group)">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            selectedDate: '',
            groups: [],
            teachers: [],
            audiences: ['31 ауд.', '16 ауд.', 'Спортзал', '33 ауд.']
        };
    },
    methods: {
        async fetchData() {
            try {
                const [groupsResponse, teachersResponse] = await Promise.all([
                    axios.get('/api/groups-empty-year'),
                    axios.get('/api/teachers')
                ]);

                this.groups = groupsResponse.data;
                this.teachers = teachersResponse.data;

                this.groups.forEach(group => {
                    group.lessons = [];
                });
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        async fetchSchedule() {
            try {
                const response = await axios.get(`/api/schedule/${this.selectedDate}`);
                const scheduleData = response.data;

                this.groups.forEach(group => {
                    const groupSchedule = scheduleData.find(schedule => schedule.group_id === group.id);
                    if (groupSchedule) {
                        group.lessons = groupSchedule.lessons;
                    } else {
                        group.lessons = [];
                    }
                });
            } catch (error) {
                console.error('Error fetching schedule:', error);
            }
        },
        updateLessonInfo(group, lesson) {
            const selectedSubject = group.subjects.find(subject => subject.id === lesson.subject_id);
            if (selectedSubject) {
                lesson.total_hours = selectedSubject.hours;
                lesson.completed_hours = selectedSubject.hours_spent;
                lesson.previous_dates = selectedSubject.previous_dates;
            }
        },
        addLesson(group) {
            group.lessons.push({
                subject_id: null,
                hours: 2,
                start_time: '',
                completed_hours: 0,
                total_hours: 0,
                previous_dates: [],
                teacher_1_id: null,
                audience_1: '',
                teacher_2_id: '',
                audience_2: ''
            });
        },
        async submitSchedule() {
            try {
                const payload = {
                    date: this.selectedDate,
                    groups: this.groups.map(group => ({
                        id: group.id,
                        lessons: group.lessons.map((lesson, index) => ({
                            ...lesson,
                            number_sub: index + 1
                        }))
                    }))
                };

                await axios.post('/api/schedule', payload);
                alert('Расписание успешно сохранено!');
            } catch (error) {
                console.error('Error submitting schedule:', error);
            }
        }
    },
    created() {
        this.fetchData();
    }
};
</script>

<style>
.timetable-main {
    padding-top: 20px;
    display: flex;  
    flex-direction: column;
}
.timetable-groups {
    padding: 20px;
}
.timetable-detail-group {
    display: flex;
    margin-top: 20px;
}
.timetable-btns {
    margin: 0;
}
.info-group {
    background-color: #4f6384;
    width: fit-content;
    border-radius: 30px;
    padding: 15px 20px;
}
.info-group p {
    padding: 0px;
    margin: 0;
    font-family: 'rubick-light', Georgia, serif;
    font-weight: bold;
    font-size: 14px;
    padding: 3px 0;
    color: white;
}
.hours-and-dates {
    width: -webkit-fill-available !important;
    text-align: center;
}
</style>
