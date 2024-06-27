<template>
    <select 
      class="btn-selected" 
      :class="{ 'active': selectedValue !== null }" 
      v-model="selectedValue"
      @change="handleSelectChange"
    >
      <option v-for="option in options" :value="option.value" :key="option.value">{{ option.input }}</option>
    </select>
  </template>
  
  <script>
  export default {
    props: {
      options: {
        type: Array,
        required: true
      },
      lectureId: {
        type: [String, Number],
        required: true
      },
      OKR: {
        type: [String, Number],
        required: true
      }
    },
    data() {
      return {
        selectedValue: ''  
      };
    },
    mounted() {
      this.selectedValue = this.OKR;
    },
    watch: {
      OKR(newValue) {
        this.selectedValue = newValue;
        console.log('OKR updated:', newValue);
      }
    },
    methods: {
        handleSelectChange() {
            this.$emit('okr-changed', this.selectedValue);
        }
    }
  };
  </script>
  
  <style scoped>
  .btn-selected {
    text-align: center;
    border-radius: 50px;
    border: none;
    color: #4f6384;
    font-size: 12px;
    cursor: pointer;
    font-family: 'rubick-regular', Georgia, serif;
    -webkit-transition-duration: 0.3s; /* Safari */
    transition-duration: 0.3s;
  }
  .btn-selected.active {
    color: white;
    background-color: #4f6384;
  }
  </style>
  