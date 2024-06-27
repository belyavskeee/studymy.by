<template>
	<div class="auth-block main-block" style="width: fit-content;">
	  <div class="block-list-subject">
		<div class="info-subject">
		  <div class="name-subject name-subject-focus">
			{{ subject.name }}
		  </div>
		  <div>{{ group.name_group }} гр.</div>
		  <div>{{ group.kyrs }} курс</div>
		</div>
		<div class="inner-shadow">
		  <div class="list-subject">
			<div
			  v-for="lecture in lecturesOfType"
			  :key="lecture.id"
			  @click="navigateToPage(lecture.id)"
			  id="subject-lecture-id"
			>
			  <p>{{ formatDate(lecture.date) }}</p>
			  <p>{{ lecture.theme || 'Добавьте тему' }}</p>
			</div>
		  </div>
		</div>
	  </div>
	  <div class="block-graph">
		<div style="display: flex; justify-content: flex-end; margin-bottom: 20px; margin-top: 5px;">
		  <download-statement-component
		  	v-if="userPermission !== 'Студент'"
		  	:subject="subject"
			:group="group"
			></download-statement-component>
		  <div class="hours-subject">
			<p>{{ totalLectureHours }}/{{ subject.hours }} часов</p>
		  </div>
		</div>
		<div class="graph">
		  <img :src="pictureGraph" alt="График">
		</div>
	  </div>
	</div>
  </template>
  
  <script>
  import DownloadStatementComponent from "@/components/DownloadStatementComponent.vue";
  import pictureGraph from '/resources/assets/images/graph.png';
  import axios from 'axios';
  import { format } from 'date-fns';
  import { mapGetters } from 'vuex';
  
  export default {
	components: {
    	DownloadStatementComponent
  	},
	data() {
	  return {
		pictureGraph,
		subject: {},
		group: {},
		lectures: []
	  };
	},
	methods: {
	  navigateToPage(id) {
		this.$router.push({ name: 'DetailSubject', params: { id } });
	  },
	  fetchSubject() {
		const id = this.$route.params.id;
		axios.get(`/api/subjects/${id}/lectures`)
		  .then(response => {
			this.subject = response.data;
			this.lectures = response.data.lectures || [];
			return axios.get(`/api/groups/${this.subject.group_id}`);
		  })
		  .then(response => {
			this.group = response.data.group;
		  })
		  .catch(error => {
			console.error('Failed to fetch data:', error);
		  });
	  },
      formatDate(dateString) {
        return format(new Date(dateString), 'dd.MM');
      }
	},
	mounted() {
	  this.fetchSubject();
	},
	computed: {
    lecturesOfType() {
      return this.lectures.filter(l => l.type === 'Лекция');
    },
	totalLectureHours() {
      return this.lecturesOfType.reduce((total, lecture) => total + lecture.hours, 0);
    }
  },
  computed: {
    ...mapGetters(['userPermission'])
  },
  };
  </script>
  
<style>

</style>
  