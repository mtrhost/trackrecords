<template lang="html">
	<section class="bdy content reset cflex">
		<div class="c100">
			<article class="blk __content-mount">
				<div class="title">
					<h1>Redesign! Rebuild! Return!</h1>
				</div>
				<h2>
					Вот и стата подъехала.
				</h2>
				<p>
					На этих страницах вы можете наблюдать результаты всех игр раздела, начиная с игры номер 66,5 (игры шипа до 67 игры), пока что так (ибо более ранние игры собрать в стату не представляется возможным). Failed игры внесены но не учитываются, игры где ведущий 
					завершил игру ничьей или победой ведущего (да были и такие) не вносятся в общую стату количества сыгранных игр и никак не влияют на винрейт и не отображаются в количестве игр в персональном профиле
					(no pts ).
				</p>
				<p>
					Для любого попадания в стату нужно в сумме больше 20 игр<br>
					для мж или актива нужно более 15 игр на этой роли<br>
					для мирного более 20 игр на мирном<br>
					для вылетов на мирном более 30 игр<br>
					для мафии более 10, здесь же условие по выживаемости на мафии<br>
					для мана более 4<br>
					для попадания в общую стату (по винрейту) нужно более 30 (здесь же условие для частоты получения ролей и винстриков)<br>
				</p>
				<p>Такие дела</p>
			</article>
		</div>
		<ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" tabindex="0" style="display: none;"></ul>
		<ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-4" tabindex="0" style="display: none;"></ul>
	</section>	
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";

export default {
	components: {
		//TopMenu // Верхнее меню
	}
};
</script>

<style>

</style>
