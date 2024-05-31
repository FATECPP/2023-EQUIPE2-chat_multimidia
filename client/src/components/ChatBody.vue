<template>
  <section class="box-chat-body">
    <div class="chat-body columns">
      <div class="chat-body-left column is-3" v-show="props.showGroups || largura >= 768">
        <div class="user-info has-background-info-light">
          <div class="is-flex is-justify-content-center is-align-items-center is-flex-direction-column" style="height: 70%">
            <figure class="image is-96x96">
              <img :src="props.user.photo" class="is-rounded">
            </figure>
            <h1>{{ props.user.name}}</h1>
          </div>
          <div style="background: white; height: 30%" class="is-flex is-justify-content-center is-align-items-center is-flex-direction-column" >
            <h1 style="color: #353d9f; font-s: 1.25rem; font-w: 500">Últimas conversas</h1>
          </div>
        </div>
        <div>
          <Groups :groups="groups" :user="props.user"
          @currentGroupID=" groupCurrent = groups[$event]; idCurrentGroupList = $event; connectionCurrent = connections.value[$event]; emits('choiseGroup', groupCurrent)" :currentGroup="idCurrentGroupList"
          @webSockets="connections.value = $event; connectionCurrent = connections.value[0]; receberMessage(); sendConnections($event)"
          @receber_mensagem="receber_mensagem($event)"/>
        </div>
      </div>
      <div class="chat-body-right column" style="border-right: 1px solid rgb(226, 226, 226);" v-show="!props.showGroups || largura >= 768">
        <div class="body-right-messages" v-if="groupCurrent">
          <div v-for="(message, index) in groupCurrent.messages" :key="index" :style=" user.id == message.userID ? { 'justify-content': 'flex-end' } : { 'justify-content': 'flex-start' }" class="is-flex" style="margin: 2px 5px">
            <Message :message="message" :style="message.status == 'error' ? { background: 'gray', border: '3px solid red' } : {}" :isUser="user.id == message.userID" :thisMessageError="verifyErrorMessage(message.id)" @reSend="reSend($event)" @deleteMessage="deleteMessage($event)" />
          </div>
        </div>
        <!-- INPUT -->
            <!-- <input type='file'  id="file-input"> -->
        <div class="body-right-input columns is-mobile" style="border-top: 1px solid rgb(226, 226, 226);">
          <input type="file" @change="sendFileMessage($event);" id="file-input" style="display: none" accept=".mp4, .mp3, .png, .jpg, jpeg, .gif" /> 
          <div class="column is-flex is-justify-content-center is-align-items-center">
            <button class="button is-normal" style="border-radius: 0px; background: #353d9f;" @click="captureFileClick()">
              <span class="icon is-medium">
                <svg xmlns="http://www.w3.org/2000/svg" height="1.25em" viewBox="0 0 448 512"><path d="M364.2 83.8c-24.4-24.4-64-24.4-88.4 0l-184 184c-42.1 42.1-42.1 110.3 0 152.4s110.3 42.1 152.4 0l152-152c10.9-10.9 28.7-10.9 39.6 0s10.9 28.7 0 39.6l-152 152c-64 64-167.6 64-231.6 0s-64-167.6 0-231.6l184-184c46.3-46.3 121.3-46.3 167.6 0s46.3 121.3 0 167.6l-176 176c-28.6 28.6-75 28.6-103.6 0s-28.6-75 0-103.6l144-144c10.9-10.9 28.7-10.9 39.6 0s10.9 28.7 0 39.6l-144 144c-6.7 6.7-6.7 17.7 0 24.4s17.7 6.7 24.4 0l176-176c24.4-24.4 24.4-64 0-88.4z" fill="white" /></svg>
              </span>
            </button>
            <input class="input" type="text" @keypress="send" placeholder="Escrever mensagem" v-model="inputTextMessage" style="border-radius: 0px;" />
            <!-- CLIP -->
            <!-- MICROPHO -->
            <button class="button is-normal" style="border-radius: 0px; background: #353d9f;" v-if="inputTextMessage == ''" @mousedown="startRecording(); microphoneIs = false" @mouseup="stopRecording(); microphoneIs = true">
              <span class="icon is-medium" v-if="microphoneIs">
                <svg xmlns="http://www.w3.org/2000/svg" height="1.25em" viewBox="0 0 384 512"><path d="M192 0C139 0 96 43 96 96V256c0 53 43 96 96 96s96-43 96-96V96c0-53-43-96-96-96zM64 216c0-13.3-10.7-24-24-24s-24 10.7-24 24v40c0 89.1 66.2 162.7 152 174.4V464H120c-13.3 0-24 10.7-24 24s10.7 24 24 24h72 72c13.3 0 24-10.7 24-24s-10.7-24-24-24H216V430.4c85.8-11.7 152-85.3 152-174.4V216c0-13.3-10.7-24-24-24s-24 10.7-24 24v40c0 70.7-57.3 128-128 128s-128-57.3-128-128V216z" fill="white"/></svg>
              </span>
              <span class="icon is-medium" v-else>
                <svg xmlns="http://www.w3.org/2000/svg" height="1.25em" viewBox="0 0 384 512"><path d="M192 0C139 0 96 43 96 96V256c0 53 43 96 96 96s96-43 96-96V96c0-53-43-96-96-96zM64 216c0-13.3-10.7-24-24-24s-24 10.7-24 24v40c0 89.1 66.2 162.7 152 174.4V464H120c-13.3 0-24 10.7-24 24s10.7 24 24 24h72 72c13.3 0 24-10.7 24-24s-10.7-24-24-24H216V430.4c85.8-11.7 152-85.3 152-174.4V216c0-13.3-10.7-24-24-24s-24 10.7-24 24v40c0 70.7-57.3 128-128 128s-128-57.3-128-128V216z" fill="red"/></svg>
              </span>
            </button>
            <!-- SEND-->
            <button class="button is-normal" style="border-radius: 0px; background: #353d9f;" @click="send('click')" v-else>
              <span class="icon is-medium">
                <svg xmlns="http://www.w3.org/2000/svg" height="1.25em" viewBox="0 0 512 512"><path d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480V396.4c0-4 1.5-7.8 4.2-10.7L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z" fill="white"/></svg>
              </span>
            </button>
          </div>
        </div>
        <!-- INPUT END -->
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';

import Groups from "./Groups.vue";
import Message from "./Message.vue";

const props = defineProps(["groups", "user", "closeConnection", "showGroups"]);
const emits = defineEmits(["sendConnections", 'choiseGroup']);
const microphoneIs = ref(true);
const inputTextMessage = ref('');
const groupCurrent = ref(null);
const idCurrentGroupList = ref(0);
const connections = ref([]);
const connectionCurrent = ref(null);
const idMessageError = ref([]);
const boxForMore = ref(false);
const largura = ref(document.documentElement.clientWidth);

let mediaRecorder = '';
let recordedChunks = [];

async function startRecording() {
  const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

  mediaRecorder = new MediaRecorder(stream);

  mediaRecorder.ondataavailable = (event) => {
    if (event.data.size > 0) {
      recordedChunks.push(event.data);
    }
  };

  mediaRecorder.onstop = () => {
    const blob = new Blob(recordedChunks, { type: 'audio/mpeg' });
    const files = [
        new File([blob], 'gravacao_audio.mp3', { type: 'audio/mpeg' })
    ];
    const dataTransfer = new DataTransfer();
    files.forEach(file => {
        dataTransfer.items.add(file);
    });
    const audioFileInput = document.getElementById('file-input');
    audioFileInput.files = dataTransfer.files;
    console.log(audioFileInput.files)
    console.log(dataTransfer.files)
    const changeEvent = new Event('change', { bubbles: true });
    audioFileInput.dispatchEvent(changeEvent);
  };
  recordedChunks = [];
  mediaRecorder.start();
}

function stopRecording() {
    console.log("CLICK STOP")
    console.log(document.getElementById('file-input').files)
   if (mediaRecorder && mediaRecorder.state !== 'inactive') {
     mediaRecorder.stop();
   }
}
const created = onMounted(() => {
  groupCurrent.value = props.groups[0];
  emits('choiseGroup', props.groups[0])
});
function onWindowResize() {
  largura.value = document.documentElement.clientWidth;
}

window.addEventListener('resize', onWindowResize);
function sendConnections(values) {
  emits("sendConnections", values)
}

function generateRandomId(length)
{
  const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$";
  const charactersLength = characters.length;
  let id = "";
  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * charactersLength);
    id += characters.charAt(randomIndex);
  }
  return id;
}
function choiseGroup(){
  console.log("BODY here")
  emits("choiseGroup", true)
}
function receberMessage()
{
  connections.value.forEach((element) => {
    element.onmessage = function (event) {
      const message = JSON.parse(event.data);
      if (message.status == "error") 
      {
        groupCurrent.value.messages.unshift(message);
        idMessageError.value.push(message.messageID);
      }else if (message.status == "success" && message.message == 'updateNumbers')
      {
        props.groups.forEach(element => {
          if (element.id == message.group)
          {
            element.numbers = message.numbers;
          }
        })

      }else {
        groupCurrent.value.messages.unshift(message);
      }
    };
  });
}

function receber_mensagem(message){
  let indexCapture = null;
  props.groups.map((obj, index) => {
    if(obj.id == message.groupID){
      indexCapture = index;
    }
  })
  console.log("CAIU NO RECEBER MENSAGEM")
  props.groups[indexCapture].messages.unshift(message);
}

function verifyErrorMessage(id) 
{
  const array = idMessageError.value;
  if (array.includes(id)) 
  {
    return true;
  }
  return false;
}

function captureFileClick() {
  const fileInput = document.getElementById("file-input");
  fileInput.click();
}
function sendFileMessage(event) {
  let selectedFile = event.target.files[0];
console.log("trocou")
  console.log(event.target.files[0])
  if (isFileValid(selectedFile)) 
  {
    console.log("OK")
    let message = {
      id: generateRandomId(31),
      date: new Date(),
      groupID: groupCurrent.value.id,
      userID: props.user.id,
      userName: props.user.name,
      userPhoto: props.user.photo,
      type: "text",
    };

    if (selectedFile.type.indexOf("image") > -1) {
      message.text = "Imagem";
      message.type = "image";
      message.ext = selectedFile.type.split("/")[1];
    }

    if (selectedFile.type.indexOf("video") > -1) {
      message.text = "Video";
      message.type = "video";
    }

    if (selectedFile.type.indexOf("audio") > -1) {
      message.text = "Audio";
      message.type = "audio";
    }
    const reader = new FileReader();
    reader.onload = (e) => {
        const imageBase64 = e.target.result;
        message.file = imageBase64;
        connectionCurrent.value.send(JSON.stringify(message));
        console.log('Imagem enviada para o servidor.');
    };
    reader.readAsDataURL(selectedFile);
  }
}
function isFileValid(file) {
  switch (file.type) {
    case "image/png":
    case "image/jpeg":
    case "image/jpg":
    case "image/gif":
    case "video/mp4":
    case "audio/mpeg":
      return true;
    default:
      return false;
  }
}
function reSend(id) 
{
  const array = groupCurrent.value.messages;
  let index = null;
  for (let i = 0; i < array.length; i++) {
    if (array[i]["id"] === id) {
      index = i;
    }
  }
  let message = JSON.parse(JSON.stringify(array[index]));
  message.date = new Date();
  idMessageError.value = idMessageError.value.filter((item) => item !== id);
  if(connectionCurrent.value.readyState != 0){
    connectionCurrent.value.send(JSON.stringify(message));
    console.log("SUCESS: MENSAGEM FOI REENVIDA")
  }else {
    idMessageError.value.push(message.id);
    console.log("ERROR: MENSAGEM NÃO FOI REENVIADA")
  }
}

function send(e)
{
  if (e == "click") 
  {
    send({
      keyCode: 13
    });
    return
  }
  if (e.keyCode === 13 && inputTextMessage.value != "") 
  {
    const message = {
      id: generateRandomId(31),
      text: censurando(inputTextMessage.value),
      date: new Date(),
      groupID: groupCurrent.value.id,
      userID: props.user.id,
      userName: props.user.name,
      userPhoto: props.user.photo,
      type: "text",
    };
    console.log(connectionCurrent.value.readyState)
    if(connectionCurrent.value.readyState == 1){
      console.log("SUCESS: MENSAGEM ENVIDADA")
      connectionCurrent.value.send(JSON.stringify(message));
	  }else {
      idMessageError.value.push(message.id);
      console.log("ERROR: MENSAGEM NÃO FOI ENVIDADA")
    }
    groupCurrent.value.messages.unshift(message);
    inputTextMessage.value = "";
  }
}

function deleteMessage(id) 
{
  groupCurrent.value.messages = groupCurrent.value.messages.filter((item) => item.id !== id);
}

function censurando(text) {
    const WORDS_BLACKLIST = ["aidético", "aidética", "aleijado", "aleijada", "analfabeto", "analfabeta", "anus", "anão", "anã", "arrombado", "apenado", "apenada", "baba-ovo", "babaca", "babaovo", "bacura", "bagos", "baianada", "baitola", "barbeiro", "barraco", "beata", "bebum", "besta", "bicha", "bisca", "bixa", "boazuda", "boceta", "boco", "boiola", "bokete", "bolagato", "bolcat", "boquete", "bosseta", "bosta", "bostana", "boçal", "branquelo", "brecha", "brexa", "brioco", "bronha", "buca", "buceta", "bugre", "bunda", "bunduda", "burra", "burro", "busseta", "bárbaro", "bêbado", "bêbedo", "caceta", "cacete", "cachorra", "cachorro", "cadela", "caga", "cagado", "cagao", "cagão", "cagona", "caipira", "canalha", "canceroso", "caralho", "casseta", "cassete", "ceguinho", "checheca", "chereca", "chibumba", "chibumbo", "chifruda", "chifrudo", "chochota", "chota", "chupada", "chupado", "ciganos", "clitoris", "clitóris", "cocaina", "cocaína", "coco", "cocô", "comunista", "corna", "cornagem", "cornisse", "corno", "cornuda", "cornudo", "cornão", "corrupta", "corrupto", "coxo", "cretina", "cretino", "criolo", "crioulo", "cruz-credo", "cu", "cú", "culhao", "culhão", "curalho", "cuzao", "cuzão", "cuzuda", "cuzudo", "debil", "débil", "debiloide", "debilóide", "deficiente", "defunto", "demonio", "demônio", "denegrir", "denigrir", "detento", "difunto", "doida", "doido", "egua", "égua", "elemento", "encostado", "esclerosado", "escrota", "escroto", "esporrada", "esporrado", "esporro", "estupida", "estúpida", "estupidez", "estupido", "estúpido", "facista", "fanatico", "fanático", "fascista", "fedida", "fedido", "fedor", "fedorenta", "feia", "feio", "feiosa", "feioso", "feioza", "feiozo", "felacao", "felação", "fenda", "foda", "fodao", "fodão", "fode", "fodi", "fodida", "fodido", "fornica", "fornição", "fudendo", "fudeção", "fudida", "fudido", "furada", "furado", "furnica", "furnicar", "furo", "furona", "furão", "gai", "gaiata", "gaiato", "gay", "gilete", "goianada", "gonorrea", "gonorreia", "gonorréia", "gosmenta", "gosmento", "grelinho", "grelo", "gringo", "homo-sexual", "homosexual", "homosexualismo", "homossexual", "homossexualismo", "idiota", "idiotice", "imbecil", "inculto", "iscrota", "iscroto", "japa", "judiar", "ladra", "ladrao", "ladroeira", "ladrona", "ladrão", "lalau", "lazarento", "leprosa", "leproso", "lesbica", "lésbica", "louco", "macaca", "macaco", "machona", "macumbeiro", "malandro", "maluco", "maneta", "marginal", "masturba", "meleca", "meliante", "merda", "mija", "mijada", "mijado", "mijo", "minorias", "mocrea", "mocreia", "mocréia", "moleca", "moleque", "mondronga", "mondrongo", "mongol", "mongoloide", "mongolóide", "mulata", "mulato", "naba", "nadega", "nádega", "nazista", "negro", "nhaca", "nojeira", "nojenta", "nojento", "nojo", "olhota", "otaria", "otario", "otária", "otário", "paca", "palhaco", "palhaço", "paspalha", "paspalhao", "paspalho", "pau", "peia", "peido", "pemba", "pentelha", "pentelho", "perereca", "perneta", "peru", "peão", "pica", "picao", "picão", "pilantra", "pinel", "pinto", "pintudo", "pintão", "piranha", "piroca", "piroco", "piru", "pivete", "porra", "prega", "preso", "prequito", "priquito", "prostibulo", "prostituta", "prostituto", "punheta", "punhetao", "punhetão", "pus", "pustula", "puta", "puto", "puxa-saco", "puxasaco", "penis", "pênis", "rabao", "rabão", "rabo", "rabuda", "rabudao", "rabudão", "rabudo", "rabudona", "racha", "rachada", "rachadao", "rachadinha", "rachadinho", "rachado", "ramela", "remela", "retardada", "retardado", "ridícula", "roceiro", "rola", "rolinha", "rosca", "sacana", "safada", "safado", "sapatao", "sapatão", "sifilis", "sífilis", "siririca", "tarada", "tarado", "testuda", "tesuda", "tesudo", "tezao", "tezuda", "tezudo", "traveco", "trocha", "trolha", "troucha", "trouxa", "troxa", "tuberculoso", "tupiniquim", "turco", "vaca", "vadia", "vagal", "vagabunda", "vagabundo", "vagina", "veada", "veadao", "veado", "viada", "viadagem", "viadao", "viadão", "viado", "viadão", "víado", "xana", "xaninha", "xavasca", "xerereca", "xexeca", "xibiu", "xibumba", "xiíta", "xochota", "xota", "xoxota"];
    
    for (const word of WORDS_BLACKLIST) {
        let count = 0;
        let censura = "";
        while (count < word.length) {
            censura = censura + "*";
            count++;
        }
        text = text.replace(word, censura);
    }
    return text;
}

</script>

<style scoped>
.box-chat-body {
  height: 90%;
}

.chat-body {
  height: 100%;
  margin: 0px;
}

.box-chat-body .chat-body-left {
  height: 100%;
  padding: 0px;
}

.box-chat-body .chat-body-right {
  background: white;
  height: 100%;
  padding: 0px;
  border-left: 1px solid rgb(226, 226, 226);
}

.box-chat-body .chat-body-right .body-right-messages {
  height: 88%;
  margin: 0px;
  overflow: auto;
  display: flex;
  flex-direction: column-reverse;
  background: #f8f8f9;
}

.box-chat-body .chat-body-right .body-right-input {
  height: 12%;
  margin: 0px;
}

.box-chat-body .chat-body-right .body-right-input input {
  border-color: #e1e1e1;
border-width: 2px;
  color: black;
}

.box-chat-body .chat-body-right .body-right-input input::placeholder {
  color: #000000;
}

.user-info{
  height: 30%;
  margin: 0px;
  padding: 0px;
}
</style>
