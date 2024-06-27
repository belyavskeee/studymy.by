<template>
  <div class="auth-block main-block add-main-form" style="overflow: hidden;">
    <div class="list-settings form-input" data-aos="fade-right">
      <h1 v-if="title" class="title-menu">{{ title }}</h1>
      <form class="add-form" @submit.prevent="submitForm">
        <template v-for="option in options" :key="option.id">
          <label>{{ option.label }}</label>
          <input type="text" v-model="formData[option.id]" :id="option.id">
        </template>
        <template v-for="select in selecters" :key="select.label">
          <label>{{ select.label }}</label>
          <select v-model="formData[select.id.toLowerCase()]">
            <option v-for="option in select.values" :key="option.id" :value="option.id">
              {{ option.value }}
            </option>
          </select>
        </template>
        <input style="background-color: #c5d2e2; margin-bottom: 20px;" type="submit" value="Отправить">
      </form>
    </div>
  </div>
</template>

<script>
import AOS from 'aos';
import 'aos/dist/aos.css';

export default {
  props: {
    options: {
      type: Array,
      required: true
    },
    title: {
      type: String
    },
    selecters: {
      type: Array
    },
    formData: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      formData: {}
    };
  },
  methods: {
    initializeFormData() {
      const initialFormData = {};
      this.options.forEach(option => {
        initialFormData[option.id] = '';
      });
      this.selecters.forEach(select => {
        initialFormData[select.id.toLowerCase()] = '';
      });
      this.formData = { ...initialFormData, ...this.formData };
    },
    submitForm() {
      this.$emit('form-submitted', this.formData);
    }
  },
  mounted() {
    AOS.init();
    this.initializeFormData();
  }
};
</script>

<style>
.title-menu {
  font-family: 'rubick-regular', Georgia, serif;
  text-align: center;
  font-size: 16px;
  padding: 10px 0;
  color: #4f6384;
}
.add-form {
  display: flex;
  flex-direction: column;
  width: fit-content;
  margin: 0 auto;
}
.add-form input {
  background-color: #DBE6F4;
}
.add-form label {
  width: fit-content;
  margin-left: 15px;
  padding-top: 10px; 
}
.add-main-form {
  width: fit-content;
  padding: 20px 50px;
}
</style>
