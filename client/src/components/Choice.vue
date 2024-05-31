<template>
<div class="box-choice columns" v-if="dataLoader">
	<div class="column">
		<h1 class="title is-flex is-justify-content-center" style="font-size: 1.25rem;">Usuários Disponíveis</h1>
		<article v-for="(user, index) in users" :key="index" class="media">
			<figure class="image is-32x32 media-left">
				<img :src="user.photo" class="is-rounded" @click="choiseUser(user)" />
			</figure>
			<div class="media-content">
				<h1 style="font-size: 1rem;"> {{ user.name}} <span class="tag is-success" style="background-color: #178524;" v-if="user.userType == 'professor'"> Professor </span></h1>
				
				<label style="font-size: 0.75rem;" v-for="(group, index) in user.groups" :key="index">{{ user.groups[index + 1] != null ? `${group},` : `${group}.` }}</label>
			</div>
		</article>
	</div>
</div>
</template>

<script setup>
import Chat from "../utils/Chat";
import { ref, onMounted, reactive } from "vue";

const emits = defineEmits(['userGroups', 'user'])
let users = reactive([]);
let dataLoader = ref(false);

const getUsers = onMounted( async () => {
	Chat.getUsers().then((messages) => {
			users = messages;
			dataLoader.value = true;
		}).catch((error) => {
			console.error(error);
		});
});

function choiseUser(user)
{
	emits('userGroups', user.id)
	emits('user', user)
}
</script>

<style scoped>
.box-choice {
	height: 90%;
	overflow: auto;
	margin: 0px;
}

.box-choice .column {
	padding: 0px;
}
</style>
