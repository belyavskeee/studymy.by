<template>
    <my-header/>
    <study-main-component/>
    <my-footer/>
</template>

<script>
import StudyMainComponent from "@/components/StudyMainComponent.vue";
export default {
    components: {
        StudyMainComponent,
    },
    mounted() {
		document.title = 'Образование - beStudy';
    }
}
</script>

<style>

</style>