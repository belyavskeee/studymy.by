<template>
	<input type="button" id="download" class="button-def-h40 download" value="Скачать ведомость" @click="downloadReport">
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
	props: {
	  subject: {
		type: Object,
		required: true
	  },
	  group: {
		type: Object,
		required: true
	  }
	},
	methods: {
	  async downloadReport() {
		try {
		  const url = `/download-statement/${this.subject.id}/${this.group.id}`;
		  const response = await axios.get(url, { responseType: 'blob' });
		  const urlBlob = window.URL.createObjectURL(new Blob([response.data]));
		  const link = document.createElement('a');
		  link.href = urlBlob;
		  link.setAttribute('download', 'statement.xlsx');
		  document.body.appendChild(link);
		  link.click();
		  document.body.removeChild(link);
		} catch (error) {
		  // Обработка ошибки
		  console.error('Error:', error.response ? error.response.data : error.message);
		  alert('Произошла ошибка при загрузке данных. Пожалуйста, попробуйте позже.');
		}
	  }
	}
  };
  </script>
  
  <style>
  .download {
	margin: 0;
	cursor: pointer;
  }
  .download:focus {
	box-shadow: inset 0 0 0 3px #617492;
  }
  </style>
  