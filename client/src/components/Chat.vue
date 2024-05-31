<template>
  <div>
    <div class="container-chat-background-gray"></div>
    <div class="container-chat-all">
      <div class="chat">
        <div style="height: 100%;" class="is-flex">
          <div :style="showPeoples && groups.length != 0 && largura >= 768 ? { 'width': '75%' } : { 'width': '100%' }">
            <ChatHeader :user="user" @close="close()" @showGroups="showGroups = $event;" :showGroups="showGroups" :currentGroupName="currentGroup" :showP="showPeoples" @showAllPeoples="showPeoples = $event"/>
            <Choice @userGroups="getGroups($event)" @user="getUser($event)" v-if="groups.length === 0" />
            <ChatBody  v-else :groups="groups" :user="user" :closeConnection="fafa"  @sendConnections="fafa = $event" :showGroups="showGroups" @choiseGroup="showGroups = false; currentGroup = $event;" :showP="showPeoples"  />
          </div>
          <div v-if="showPeoples && groups.length != 0 && largura >= 768" style="width: 25%">
            <div style="padding: 20px;">
              <div class="is-flex is-justify-content-space-between">
                <span style="color: #7a7a7a; font-size: 1rem; font-weight: 400;">PARTICIPANTES</span>
                <button class="delete"></button>
              </div>
              <div>
                <span style="color: #4a4a4a; font-size: 0.75em;">{{ currentGroup.teste.length }} participantes</span>
              </div>
              <div style="border-bottom: 1px solid #e9ecf0; height: 50px; margin-top: 10px;" class="is-flex is-align-content-center is-align-items-center" v-for="(user,index) in currentGroup.teste" :key="index">
                <figure class="image is-32x32" style="margin-right: 5px;">
                  <img :src="user.photo" class="is-rounded">
                </figure>
                <span style=" color: #4a4a4a; font-size: 1rem;  font-weight: 400;">
                 {{ user.name }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import ChatHeader from "./ChatHeader.vue";
import ChatBody from "./ChatBody.vue";
import Choice from "./Choice.vue";
import Chat from "../utils/Chat";

import { ref } from "vue";
const largura = ref(document.documentElement.clientWidth);
const groups = ref([]);
const user = ref(null);
const fafa = ref([]);
const emits = defineEmits(["open-chat"]);
const showGroups = ref(false);
const currentGroup = ref(null);
const showPeoples = ref(false);
function onWindowResize2() {
  largura.value = document.documentElement.clientWidth;
}

window.addEventListener('resize', onWindowResize2);
function getUser(userChosen)
{
  user.value = userChosen;
}
function close()
{
  fafa.value.forEach(element => {
    element.close(1000);
  });
  emits("open-chat", false);
}
function getGroups(id) 
{
  Chat.getGroups(id).then((messages) => {
    groups.value = messages;
    groups.value.forEach(element => {
       Chat.getUsersOfGroup(element.id).then((messages) => {
        element.teste = messages;
   }).catch((error) => {
      console.error(error);
  });
    })
  }).catch((error) => {
      console.error(error);
  });
}
</script>

<style scoped>
.container-chat-background-gray {
  z-index: 1;
  background-color: rgba(56, 60, 68, 0.507);
  width: 100vw;
  height: 100vh;
  position: absolute;
  top: 0px;
  bottom: 0px;
}

.container-chat-all {
  width: 100vw;
  height: 100vh;
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
  top: 0px;
}

.container-chat-all .chat {
  background-color: #fff;
  width: 65%;
  height: 80%;
  position: absolute;
  z-index: 2;
}
@media (max-width: 768px) {
  .container-chat-all .chat {
    width: 85%;
  }
}
</style>
