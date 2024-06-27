<template>
    <my-header/>
    <main-component/>
    <my-footer/>
</template>

<script>
import MainComponent from "@/components/MainComponent.vue";
export default {
    components: {
        MainComponent
    },
    mounted() {
		document.title = 'Главная - beStudy';
    }
}

</script>

<style>

</style>