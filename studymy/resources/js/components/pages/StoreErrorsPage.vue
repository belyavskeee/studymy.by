<template>
    <my-header/>
    <errors-main-component/>
    <my-footer/>
</template>

<script>
import ErrorsMainComponent from "@/components/ErrorsMainComponent.vue";
export default {
    components: {
        ErrorsMainComponent
    },
    mounted() {
		document.title = 'Ошибки пользователей - beStudy';
    }
}
</script>

<style>

</style>