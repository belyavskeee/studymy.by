<template>
    <menu-Inline :options="valueMenu"/>
</template>

<script>
import MenuInline from "@/components/UI/MenuInline.vue";
import axios from 'axios';
import { format } from 'date-fns';
import { ru } from 'date-fns/locale';

export default {
  components: {
    MenuInline
  },
  data() {
    return {
      valueMenu: [
        // { header: 'Текущий семестр', value: '1 января 2024 - 8 июля 2024', path: '/semester' },
        // { header: 'Предыдущие семестры', value: '1 января 2024 - 8 июля 2024', path: '/ОКР: нет' },
        { header: 'Учебные группы', value: 'Изменение учебных групп', path: '/groups' },
        { header: 'Пользователи', value: 'Список пользователей с правами администатора и преподавателя', path: '/users' },
      ]
    };
  },
}  
</script>