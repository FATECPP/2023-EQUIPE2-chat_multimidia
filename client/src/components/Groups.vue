<template>
	<div>
		<div v-for="(group, index) in props.groups" :key="index" class="container class-group" @click="changeCurrentGroup(index)" :style="index == currentGroup ? { 'background-color': 'gainsboro' } : {}">
			<div class="media" style="margin: 0px;" :style="index == currentGroup ? { color: 'black !important' } : {}">
				<figure class="media-left">
					<figure class="image is-32x32">
						<img :src="group.messages[0].userPhoto" class="is-rounded" v-if="group.messages[0]"/>
					</figure>
				</figure>
				<div class="media-content">
					<div class="content">
						<div class="is-flex is-justify-content-space-between">
							<span style="font-weight: 500; font-size: 1rem; color: #363636;">{{ group.name }}</span>
							<span style="font-weight: 500; font-size: 0.75rem; color: #5163f7;" v-if="group.messages[0]">
								{{ getDateFormatted({date: group.messages[0].date }) }}
							</span>
						</div>
						<div v-if="group.messages[0]" style="line-height: 0.85; word-break: break-all; height: 1rem; overflow: hidden;">
							<span style="width: 50;font-size: 0.75rem; color: #7a7a7a; font-weight: 400;">{{ group.messages[0].text }}</span>
						</div>
					</div>
				</div>
			</div>
			<!-- <div v-if="group.numbers != null" :style="index == currentGroup ? { color: 'black !important' } : {}" class="has-text-black">
				Onlines: {{ group.numbers }} 
			</div> -->
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
const connectionIsOK = ref([])
const webSocketsConnections = ref([]);

const props = defineProps(['groups', 'currentGroup', 'user']);
const emits = defineEmits(['currentGroupID', 'webSockets', 'receber_mensagem']);
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
function connectionWebSocket(groupID, index, userID){
	const conn = new WebSocket(import.meta.env.VITE_SERVER_BASE_URL_WEB_SOCKET+`?groupID=${groupID}&userID=${userID}`);
	conn.onopen = function (e) {
		console.log("WS: CONEXÂO FOI ESTABELECIDA");
		connectionIsOK.value[index] = true;
	};
	conn.onmessage = function (event) {
		const message = JSON.parse(event.data)
		if(message.text){
			console.log(message.text)
			emits('receber_mensagem', message)
		}
		props.groups.forEach(element => {	
			if (element.id == message.group)
			{
				console.log("WS: UPDATE DOS NÚMEROS DE CONEXÕES")
				element.numbers = message.numbers;
			}
        })
	};
	conn.onerror = function (error) {
		console.error("WS: ERRO NA CONEXÃO COM SERVIDOR", error);
		connectionIsOK.value[index] = false;
		connectionWebSocket(groupID, index, userID);
	};
	webSocketsConnections.value[index] = conn;
	emits('webSockets', webSocketsConnections.value);
}

function changeCurrentGroup(index){
	emits('currentGroupID', index);
}

const onCreated = onMounted(() => {
	props.groups.forEach((element, index) => {
		connectionIsOK.value.push(false);
		connectionWebSocket(element.id, index, props.user.id);
	});
	emits('webSockets', webSocketsConnections.value);
})

</script>

<style scoped>
.class-group {
	background: #f0f0fa;
	margin: 7px 0px;
	padding: 5px;
	cursor: pointer;
	box-sizing: border-box;
}
</style>
