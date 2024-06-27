<template>
    <my-header/>
    <menu-inline :options="valueMenu" :title="title"/>
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
        { header: 'Список обучающихся', path: '/groups/study-groups/' + this.$route.params.id + '/students' },
        { header: 'Учебные предметы', path: '/groups/study-groups/'+ this.$route.params.id +'/subjects' },
        // { header: 'Ведомость группы по предметам', path: '/groups/study-groups' },
        { header: 'Изменить', path: '/groups/study-groups/' + this.$route.params.id + '/edit' },
        { header: 'Удалить', path: '/groups/study-groups/'  + this.$route.params.id + '/delete'},
        // { header: 'Группы выпусники', value: 'Учебные группы которые выпустились', path: '/groups/graduates-groups' },
      ],
      title: 'Учебная группа',
    };
  },
  mounted() {
		document.title = 'Учебные группы - beStudy';
  }
}  
</script>

<style>

</style>