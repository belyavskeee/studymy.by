<template>
  <div class="auth-block">
    <div class="auth-block-text">
      <h4>УО «Новопольский государственный<br>аграрно-экономический колледж»</h4>
      <p>Электронный журнал</p>
    </div>
    <div class="block-registration">
      <img :src="pictureAuth" data-aos="fade-down" data-aos-duration="2500" alt="svg картинка">

      <div class="form-auth">
        <div class="swiper swiper-auth">
          <div class="image-slider__wrapper swiper-wrapper">
            <div class="swiper-slide">
              <form class="form-input auth-slide" @submit.prevent="loginUser">
                <input type="text" v-model="loginData.login" name="login" placeholder="Логин" :class="{'error': loginError}"><br>
                <input type="password" v-model="loginData.password" name="password" placeholder="Пароль" :class="{'error': loginError}"><br>
                <input class="form-button-go" type="submit" value="Войти"><br>
                <input @click="swiper.slideNext()" class="form-button-reg btn-next" type="button" value="Регистрация">
                <div class="msg__auth" :style="{ display: loginError ? 'block' : 'none' }">
                  <img class="msg__auth__img" :src="pictureAttention">
                  <p class="msg__auth__p">{{ loginError }}</p>
                </div>
                <a href="https://t.me/matve_blvsk" class="a-href">Забыли пароль?</a>
              </form>
            </div>
            <div class="swiper-slide">
              <form class="form-input auth-slide" @submit.prevent="registerCodUser">
                <input type="text" v-model="registerCodData.user_password" name="user_password" placeholder="Введите код" required><br>
                <a href="#" style="margin: 20px 15px 0 15px;">Регистрируясь, вы соглашаетесь с условиями Пользовательского соглашения и Политики конфиденциальности</a>
                <input class="form-button-go" type="submit" value="Регистрация"><br>
                <input @click="swiper.slidePrev()" class="form-button-reg btn-prev" type="button" value="У меня есть аккаунт">
                <div class="msg__auth" :style="{ display: registerCodError ? 'block' : 'none' }">
                  <img class="msg__auth__img" :src="pictureAttention">
                  <p class="msg__auth__p">{{ registerCodError }}</p>
                </div>
              </form>
            </div>
            <div class="swiper-slide">
              <form class="form-input auth-slide" @submit.prevent="registerUser">
                <!-- Полная регистрационная форма -->
                <a href="#" style="margin: 20px 15px 0 15px;">Придумайте логин и пароль</a>
                <input type="text" v-model="registerData.login" name="login" placeholder="Логин" :class="{'error': registerError}"><br>
                <input type="password" v-model="registerData.password" name="password" placeholder="Пароль" :class="{'error': registerError}"><br>
                <input class="form-button-go" type="submit" value="Регистрация"><br>
                <div class="msg__auth" :style="{ display: registerError ? 'block' : 'none' }">
                  <img class="msg__auth__img" :src="pictureAttention">
                  <p class="msg__auth__p">{{ registerError }}</p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import pictureAuth from '/resources/assets/images/1.svg'
import pictureAttention from '/resources/assets/images/attention.png'
import AOS from 'aos';
import 'aos/dist/aos.css';
import Swiper from 'swiper';
import 'swiper/css';
import axios from 'axios';

export default {
  data() {
    return {
      swiper: null,
      pictureAuth,
      pictureAttention,
      loginData: {
        login: '',
        password: ''
      },
      registerCodData: {
        user_password: ''
      },
      registerData: {
        login: '',
        password: ''
      },
      loginError: '',
      registerError: '',
      registerCodError: ''
    };
  },
  methods: {
    validateLogin() {
      this.loginError = '';
      if (!this.loginData.login) {
        this.loginError = 'Логин обязателен.';
        return false;
      }
      if (!this.loginData.password) {
        this.loginError = 'Пароль обязателен.';
        return false;
      }
      return true;
    },
    validateRegisterCod() {
      this.registerCodError = '';
      if (!this.registerCodData.user_password) {
        this.registerCodError = 'Пароль обязателен.';
        return false;
      }
      return true;
    },
    validateRegister() {
      this.registerError = '';
      if (!this.registerData.login) {
        this.registerError = 'Логин обязателен.';
        return false;
      }
      if (!this.registerData.password) {
        this.registerError = 'Пароль обязателен.';
        return false;
      }
      return true;
    },
    loginUser() {
      if (!this.validateLogin()) return;

      this.$store.dispatch('login', this.loginData)
        .then(() => {
          this.$router.push('/main');
        })
        .catch(error => {
          console.error('Login failed:', error);
          this.loginError = 'Неправильный логин или пароль.';
          this.showError('loginError');
        });
    },
    registerCodUser() {
      if (!this.validateRegisterCod()) return;

      axios.post('/api/check-user-password', this.registerCodData)
        .then(() => {
          this.swiper.slideNext();
        })
        .catch(error => {
          console.error('Registration code check failed:', error);
          this.registerCodError = 'Неправильный одноразовый код.';
          this.showError('registerCodError');
        });
    },
    registerUser() {
      if (!this.validateRegister()) return;

      const registerData = {
        login: this.registerData.login,
        password: this.registerData.password,
        user_password: this.registerCodData.user_password
      };

      axios.post('/api/update-user', registerData)
        .then(() => {
          this.loginData.login = this.registerData.login;
          this.loginData.password = this.registerData.password;
          this.loginUser();
        })
        .catch(error => {
          console.error('Registration failed:', error);
          this.registerError = 'Ошибка регистрации.';
        });
    },
    showError(errorType) {
      this.$nextTick(() => {
        const msgAuth = document.querySelector(`.${errorType}`);
        if (msgAuth) {
          msgAuth.style.display = 'block';
          setTimeout(() => {
            msgAuth.style.opacity = '1';
          }, 10);
        }
      });
    }
  },
  mounted() {
    AOS.init();
    this.swiper = new Swiper('.swiper-auth', {
      loop: false,
      allowTouchMove: false,
      spaceBetween: 0,
    });
  }
};
</script>

<style>
/* Ваши стили здесь */
</style>
