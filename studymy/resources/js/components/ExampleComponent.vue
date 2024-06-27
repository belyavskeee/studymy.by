<template>
        <div class="auth-block">
			<div class="auth-block-text">
				<h4>УО «Новопольский государственный<br>аграрно-экономический колледж»</h4>
				<p>Электронный журнал</p>
			</div>
			<div class="block-registration">
				<img src="{{ asset('images/iconnew.png') }}" data-aos="fade-down" data-aos-duration="2500" alt="svg картинка" class="aos-init aos-animate">
				
				<div class="form-auth">
					<div class="swiper swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
						<div class="image-slider__wrapper swiper-wrapper" id="swiper-wrapper-cea10037cae6c6bf5" aria-live="polite">
							<div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 2" style="width: 280px;">
								<form class="form-input auth-slide">
									<input type="text" name="login" placeholder="Логин"><br>
									<input type="password" name="password" placeholder="Пароль"><br>
									<input class=" form-button-go" type="button" value="Войти"><br>
									<input class=" form-button-reg btn-next" type="button" value="Регистрация">
									<a href="#" class="a-href">Забыли пароль?</a>
								</form>
							</div>
							<div class="swiper-slide swiper-slide-next" role="group" aria-label="2 / 2" style="width: 280px;">
								<form class="form-input">
									<input type="text" name="login" placeholder="Введите код" required=""><br>	
									<a href="#" style="margin: 20px 15px 0 15px;">Регистрируясь, вы соглашаетесть с условиями Пользовательского соглашения и Политики конфиденциальности</a>
									<input class="form-button-go" type="button" value="Регистрация"><br>
									<input class="form-button-reg btn-prev" type="button" value="У меня есть аккаунт">
								</form>
							</div>
						</div>
					<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
				</div>
			</div>
		</div>         
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
