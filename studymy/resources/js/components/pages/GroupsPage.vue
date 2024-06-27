<template>
    <my-header/>
    <menu-inline :options="valueMenu"/>
    <my-footer/>
</template>

<script>
import MenuInline from "@/components/UI/MenuInline.vue";

export default {
  components: {
    MenuInline
  },
  data() {
    return {
      valueMenu: [
        { header: 'Текущие группы', value: 'Учебные группы, которые обучаются в данный момент', path: '/groups/study-groups' },
        // { header: 'Группы выпусники', value: 'Учебные группы которые выпустились', path: '/groups/graduates-groups' },
      ]
    };
  },
  mounted() {
		document.title = 'Учебные группы - beStudy';
  }
}  
</script>

<style>

</style>