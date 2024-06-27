<template>
    <footer>
		<div class="block-footer">
			<a href="http://www.ngaek.by/index.php/ru/">Сайт колледжа</a>
			<p>© 2023-2024 beStudy.by</p>
			<a @click="this.$router.push('/report-error');">Сообщить об ошибке</a>
		</div>
	</footer>
</template>

<script>

</script>

<style scoped>
footer {
	margin-top: 40px;
	position: relative;
	width: 100%;
	overflow: hidden;
}
footer p{
	text-align: center;
	color: #617492;
	font-size: 12px;
	margin: 0 40px 0 80px;
	vertical-align: middle;
	font-family: 'rubick-light', Georgia, serif;
}

.block-footer {
	margin: auto;
	width: fit-content;
	display: flex;
}
.block-footer a {
	margin: 0;
	font-weight: bold;
	font-size: 14px;
	font-family: 'rubick-regular', Georgia, serif;
	text-decoration: none;
	color: #617492;
	-webkit-transition-duration: 0.3s; /* Safari */
	transition-duration: 0.3s;
	cursor: pointer;
}
.block-footer a:hover {
	color: #a3abbb;
}
</style>