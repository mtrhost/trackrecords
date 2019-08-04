<template lang="html">
	<section class="bdy content reset cflex">
		<div class="c100 blk __content-mount">
			<b-row mb="4" class="page__header-row">
                <div class="col-4 mx-auto">
                    <h1 class="mr-auto">Логин</h1>
                    <div class="alert alert-danger" v-if="error">
                        <p>Ошибка при логине с этими доступами</p>
                    </div>
                    <form autocomplete="off" @submit.prevent="Login" method="post">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" id="password" class="form-control" v-model="password" required>
                        </div>
                        <button type="submit" class="btn btn-default">Логин</button>
                    </form>
                </div>
            </b-row>
		</div>
	</section>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";

export default {
    data () {
      return {
          csrfToken: ''
      }
	},
    methods: {
        
	},
	beforeMount () {
		HTTP.post(Routes.getCsrfToken)
			.then(response => {
                this.csrfToken = response.data;
                console.log(this.csrfToken);
			})
			.catch(error => {
				console.error(error)
			})
	},
};
</script>

<style lang="scss" scoped>

</style>
