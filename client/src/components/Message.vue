<template>
	<article class='box-message' v-if="props.isUser" style="width: 50%;" :style="isError ? { 'background-color': 'gray' } : { 'background-color': '' }">
		<div class="is-flex is-align-content-center is-justify-content-end" style="word-break: break-all; " >
			<div class="" style="background: #353d9f; padding: 7px; border-radius: 5px; box-shadow: 0 0.5em 0.7em -0.125em rgba(185, 196, 212, 0.3), 0 0px 0 1px rgba(185, 196, 212, 0.1) !important">
				<p v-if="props.message.type == 'text'" style="font-size: 1rem; color: white; font-weight: 400;">
					{{ props.message.text }}
				</p>
				<p v-else-if="props.message.type == 'image'">
					<img :src="getUrlMessage()" alt="" srcset="" style="width: 100%;">
				</p>
				<p v-else-if="props.message.type == 'video'">
					<video :src="getUrlMessage()" controls style="width: 100%;">
						<source :src="getUrlMessage()" type="video/mp4">
					</video>
				</p>
				<p v-else-if="props.message.type == 'audio'">
					<audio :src="getUrlMessage()" controls style="width: 100%;"></audio>
				</p>
			</div>
			<figure class="" style="margin-left: 10px;">
				<figure class="image is-32x32">
					<img :src="props.message.userPhoto" class="is-rounded" />
				</figure>
			</figure>
		</div>
		<div class="is-flex is-justify-content-end" style="padding-right: 42px">
			<span style="font-size: 0.75rem; color: #7a7a7a; font-weight: 400">{{ props.message.userName }} {{ getDateFormatted({ date: props.message.date})  }}</span>
		</div>
		<div class="" v-if="props.isUser && thisMessageError" style="margin: 3px">
			<button class="button is-warning is-small is-fullwidth" @click="reSend">Reenviar</button>
		</div>
	</article>
	<article v-else class='box-message' style="width: 50%;" :style="isError ? { 'background-color': 'gray' } : { 'background-color': '' }">
		<div class="is-flex is-align-content-center is-justify-content-start" style="word-break: break-all; " >
			<figure class="" style="margin-right: 10px;">
				<figure class="image is-32x32">
					<img :src="props.message.userPhoto" class="is-rounded" />
				</figure>
			</figure>
			<div class="" style="background: #353d9f; padding: 7px; border-radius: 5px; box-shadow: 0 0.5em 0.7em -0.125em rgba(185, 196, 212, 0.3), 0 0px 0 1px rgba(185, 196, 212, 0.1) !important">
				<p v-if="props.message.type == 'text'" style="font-size: 1rem; color: white; font-weight: 400;">
					{{ props.message.text }}
				</p>
				<p v-else-if="props.message.type == 'image'">
					<img :src="getUrlMessage()" alt="" srcset="" style="width: 100%;">
				</p>
				<p v-else-if="props.message.type == 'video'">
					<video :src="getUrlMessage()" controls style="width: 100%;">
						<source :src="getUrlMessage()" type="video/mp4">
					</video>
				</p>
				<p v-else-if="props.message.type == 'audio'">
					<audio :src="getUrlMessage()" controls style="width: 100%;"></audio>
				</p>
			</div>
		</div>
		<div class="is-flex is-justify-content-start" style="padding-left: 42px">
			<span style="font-size: 0.75rem; color: #7a7a7a; font-weight: 400">{{ props.message.userName }} {{ getDateFormatted({ date: props.message.date})  }}</span>
		</div>
	</article>
</template>

<script setup>
import { ref } from 'vue';
const props = defineProps(['message', 'isUser', 'thisMessageError']);
const emits = defineEmits(['deleteMessage', 'reSend']);
let isError = ref(false);

function reSend(){
	emits('reSend', props.message.id);
	isError.value = false;
}

function deleteMessage(){
	emits('deleteMessage', props.message.id);
}

function getDateFormatted(message) {
    const dateOfObjectMessage = new Date(message.date);
    const today = new Date();
    const hoursOf_dateOfObjectMessage = String(
        dateOfObjectMessage.getHours()
    ).padStart(2, "0");
    const minutesOf_dateOfObjectMessage = String(
        dateOfObjectMessage.getMinutes()
    ).padStart(2, "0");
    const daysOfWeek = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
    const daysDiff = Math.floor((today - dateOfObjectMessage) / (1000 * 60 * 60 * 24));
 
    let dateString;
    if (daysDiff === 0) {
        dateString = "";
    } else if (daysDiff === 1) {
        dateString = " - " + "Ontem" ;
    } else if (daysDiff <= 6) {
        dateString = " - " + daysOfWeek[dateOfObjectMessage.getDay()];
    } else if (daysDiff <= 30) {
        dateString = " - " + `Há ${daysDiff} dias`;
    } else {
        dateString = " - " + `Há ${Math.floor(daysDiff / 7)} semanas`;
    }
 
    return (
		" "+
        hoursOf_dateOfObjectMessage +
        ":" +
        minutesOf_dateOfObjectMessage +
        dateString
    );
}

function getUrlMessage(){
	return import.meta.env.VITE_SERVER_BASE_URL_UPLOADS + props.message.url;
}
</script>

<style scoped>
.box-message {
}

@media (max-width: 768px) {
	.box-message {
		width: 75% !important;
	}
  }
</style>
