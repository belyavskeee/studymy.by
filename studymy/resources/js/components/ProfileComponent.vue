<template>
    <div class="auth-block main-block">
        <!-- <div class="profile-display-block">
          <img :src="pictureQr" alt="qr">
        </div> -->
        <div class="profile-block">
            <p class="text-default fio-profile">{{ userFullName }}</p>
            <div class="list-settings" data-aos="fade-right">
                <!-- <div class="item-settings-list">
                    <h3>Изменить личную информацию</h3>
                    <p>Смена логина и пароля, настройки личной информации, персонализация</p>
                </div> -->
                <div @click="this.$router.push({ name: 'CreateTimetable' })" v-if="userPermission == 'Модератор'" class="item-settings-list">
                    <h3>Расписание</h3>
                    <p>Создание расписания</p>
                </div>
                <div @click="this.$router.push({ name: 'Errors' })" v-if="userPermission == 'Модератор'" class="item-settings-list">
                    <h3>Ошибки</h3>
                    <p>Список ошибок пользователей</p>
                </div>
                <div @click="performLogout" class="item-settings-list">
                    <h3>Выйти</h3>
                    <p>Выйти из аккаунта</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AOS from 'aos';
import 'aos/dist/aos.css';
import { mapGetters, mapActions } from 'vuex';
import pictureQr from '/resources/assets/images/qr.svg'

export default {
    data() {
        return {
          pictureQr,
        };
    },
  methods: {
    ...mapActions(['logout']),
    async performLogout() { 
      try {
        await this.logout();
      } catch (error) {
        console.error('Logout failed:', error);
        alert('Ошибка при выходе: ' + error.message);
      }
    }
  },
  mounted() {
    AOS.init();
  },
  computed: {
    ...mapGetters(['userFullName', 'userPermission'])
  },
}

</script>

<style>
.profile-display-block {
  width: fit-content;
  background-color: #dbe6f4;
  margin: 10px 0px 10px 10px;
  border-radius: 50px;
}
.profile-display-block img{
  background-color: #e8eff9;
  border-radius: 50px;
  width: 300px;
  height: 300px;
  margin: 10px;
}
.profile-block {
    display:flex;
    flex-direction: column;
    width: 100%;
}
.fio-profile, .item-settings-list p {
    text-align: center;
    margin: 20px auto;
    color: #4E6384;
    font-size: 24px;
	font-family: 'rubick-light', 'courier new', sans-serif;
}
.item-settings-list p {
    margin: 5px 0;
    font-size: 14px;
    text-align: left;
}
</style>