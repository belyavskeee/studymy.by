<template>
	<div style="margin-top: 5px;" class="inline-block">
	  <div style="height: fit-content; display: flex;">
		<div @click="goBack" id="back-arrow" class="arrow-back-error-page">
		  <img :src="arrowError" alt="Стрелка">
		</div>
		<div class="auth-block main-block block-report" style="overflow: hidden;">
		  <div class="error-report-block" data-aos="fade-right">
			<div>
			  <textarea v-model="description" style="margin: 0 auto;" class="textarea-default" name="description" id="myTextarea" cols="40" rows="9" placeholder="Опишите вашу проблему" maxlength="400"></textarea>
			</div>
			<div class="input-screenshots-report">
			  <label for="fileToUpload" class="custom-file-upload" @dragover.prevent="handleDragOver" @dragleave="handleDragLeave" @drop.prevent="handleDrop">
				<input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" multiple @change="handleFileChange">
				Перетащите или загрузите любые файлы,<br> 
				которые помогут решить проблему.
			  </label>
			</div>
			<div class="lits-with-screenshots">
			  <div v-for="(file, index) in files" :key="index" class="item-screenshot">
				<img :src="iconDocument" alt="Скриншот">
				<p>{{ file.name }}</p>
			  </div>
			</div>
			<div style="margin: 0 auto;">
			  <input class="custom-checkbox" type="checkbox" name="checkbox-allow-report" id="myCheckbox" v-model="systemInfoAllowed">
			  <label for="myCheckbox" class="label-default">Предоставить доступ к системной информации</label>
			</div>
			<button class="button-defualt btn-default-dark" @click="submitReport">Отправить</button>
		  </div>
		</div>
		<div class="helper-block-report"></div>
	  </div>
	</div>
  </template>
  
  <script>
  import axios from 'axios';
  import arrowError from '/resources/assets/images/arrow-big.svg';
  import iconDocument from '/resources/assets/images/dock-image.svg';
  import AOS from 'aos';
  import 'aos/dist/aos.css';
  
  export default {
	data() {
	  return {
		arrowError,
		iconDocument,
		description: '',
		files: [],
		systemInfoAllowed: false
	  };
	},
	methods: {
	  goBack() {
		this.$router.go(-1);
	  },
	  handleFileChange(event) {
		const files = event.target.files;
		this.handleFiles(files);
	  },
	  handleDragOver(event) {
		event.preventDefault();
		event.currentTarget.classList.add('drag-over');
	  },
	  handleDragLeave(event) {
		event.currentTarget.classList.remove('drag-over');
	  },
	  handleDrop(event) {
		event.preventDefault();
		event.currentTarget.classList.remove('drag-over');
		const files = event.dataTransfer.files;
		this.handleFiles(files);
	  },
	  handleFiles(files) {
		files = Array.from(files);
		if (this.files.length + files.length > 5) {
		  alert('Максимальное количество файлов: 5');
		  return;
		}
		this.files.push(...files);
	  },
	  async submitReport() {
		const formData = new FormData();
		formData.append('description', this.description);
		formData.append('systemInfoAllowed', this.systemInfoAllowed);
		this.files.forEach((file, index) => {
		  formData.append(`files[${index}]`, file);
		});
  
		try {
		  const response = await axios.post('/api/errors', formData, {
			headers: {
			  'Content-Type': 'multipart/form-data'
			}
		  });
		  console.log('Отчет успешно отправлен:', response.data);
		} catch (error) {
		  console.error('Ошибка при отправке отчета:', error);
		}
	  }
	},
	mounted() {
	  AOS.init();
	}
  };
  </script>
  
  <style>
  .btns-error {
	margin: 0;
  }
  .descripsion-error {
	max-width: 300px;
	max-height: fit-content !important;
  }
  </style>
  