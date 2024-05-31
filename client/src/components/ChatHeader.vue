<template>
	<div class="box-chat-header">
		<section class="chat-header columns is-hidden-mobile is-flex is-justify-content-start is-align-content-center is-align-items-center">
			<div class="column" v-if="user == null">
				<h1 style="color: #4a4a4a; font-s: 1rem; font-w: 400;">Chat</h1>
			</div>
			<div class="column is-flex is-align-items-center" v-else>
				<h1 style="color: #4a4a4a; font-s: 1rem; font-w: 400;" v-if="props.currentGroupName"> {{ props.currentGroupName.name}} </h1>
			</div>
			<div class="column is-flex is-align-content-center is-align-items-center is-justify-content-end" v-if="!props.showP"> 
				<button class="button is-info is-small " style="background: #353d9f; margin-right: 10px" v-if="user != null" @click="showAllPeoples()">
					<span class="icon is-small">
						<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" fill="white" /></svg>
					</span>
					<span>
						Participantes
					</span>
				</button>
				<button class="delete" @click="close()"></button>
			</div>
			<div class="column is-flex is-align-content-center is-align-items-center is-justify-content-end" v-else> 
				<button class="button is-info is-small " style="background: white; margin-right: 10px; color: #4751c2; border: 1px solid #e2e2e2;" v-if="user != null" @click="showAllPeoples()">
					<span class="icon is-small">
						<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" fill="#4751c2" /></svg>
					</span>
					<span>
						Participantes
					</span>
				</button>
			</div>
		</section>
		<section class="chat-header is-hidden-tablet columns is-mobile is-flex is-justify-content-start is-align-content-center is-align-items-center">
			<div class="column" v-if="user == null">
				<h1 style="color: #4a4a4a; font-s: 1rem; font-w: 400;">Chat</h1>
			</div>
			<div class="column is-flex is-align-items-center" v-else>
				<figure class="image is-32x32">
					<img :src="props.user.photo" class="is-rounded" />
				</figure>
				<h1 style="margin-left: 10px;color: #4a4a4a; font-s: 1rem; font-w: 400;" v-if="props.currentGroupName"> {{ props.currentGroupName.name }} </h1>
			</div>
			<div class="column is-flex is-align-content-center is-align-items-center is-justify-content-end">
				<button class="button is-info header-bottom" v-if="user != null">
					<figure class="image is-32x32">
						<img src="../assets/svg/groups.svg" @click="showGroups()"/>
					</figure>
				</button>
				<button class="delete" @click="close()"></button>
			</div>
		</section>
	</div>
</template>

<script setup>

const props = defineProps(['user', 'numbersUsers', 'showGroups', 'currentGroupName', 'showP']);
const emits = defineEmits(['close', 'showAllPeoples']);

function close()
{
	emits('close');
}

function showGroups(){
	emits('showGroups', !props.showGroups);
}
function showAllPeoples(){
	emits('showAllPeoples', !props.showP);
}
</script>

<style>
.box-chat-header {
	border: 1px solid rgb(226, 226, 226);
	height: 10%;
	margin: 0px;
	width: 100%;
}

.header-bottom{
	margin-right: 20px;
}
.box-chat-header .chat-header {
	margin: 0px;
	height: 100%;
}
</style>
