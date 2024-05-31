<template>
  <div>
    <div id="headerZ">
      <ul>
        <li class="items">
          <a href="#"><img src="https://creator4all.com/imagens/icones/default/icone-atividades.svg" alt="" srcset="" />
            <span>Aulas</span></a>
        </li>
        <li class="items">
          <a href="#"><img src="https://creator4all.com/imagens/icones/default/icone-turmas.svg" alt="" srcset="" />
            <span>Turmas</span></a>
        </li>
        <li class="items">
          <a href="#"><img src="https://creator4all.com/imagens/logo.svg" alt="" srcset="" />
            <span>User</span></a>
        </li>
      </ul>
    </div>
    <main id="sectionZ">
      <img src="https://creator4all.com/imagens/search.svg" alt="" />
      <h1 style="color: #484fa8; font-weight: bolder">Sem resultados</h1>
      <h1 style="color: #7c7c7c">Você não participa de nenhuma turma.</h1>
    </main>
    <div id="buttonOpenDesktop" class="svg-plus" @click="change">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
        <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z" fill="white" />
      </svg>
    </div>

    <div id="buttonOpenMobile" class="svg-plus" @click="change()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
        <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z" fill="white" />
      </svg>
    </div>
    <Chat v-if="open" :open="open" @open-chat="open = $event" />
  </div>
</template>
  

<script setup>
import { ref } from "vue";
import Chat from "./Chat.vue";
let open = ref(true);

function change()
{
  open.value = !open.value;
}

</script>

<style scoped>
#headerZ {
  background-color: rgba(6, 37, 98, 1);
  border-bottom: 4px solid #00b2ff;
}

#headerZ ul {
  padding: 0px;
  margin: 0px;
  display: flex;
  justify-content: center;
  list-style: none;
}

#headerZ ul .items {
  display: flex;
}

#headerZ ul .items:hover {
  opacity: 0.9;
  background-color: #052053;
}

#headerZ ul .items a img {
  width: 32px;
}

#headerZ ul .items a {
  padding: 10px 25px;
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
  color: white;
  text-decoration: none;
  font-size: 0.7em;
}

#sectionZ {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 5%;
}

#sectionZ h1 {
  margin: 0px;
  font-size: 1em;
}

#sectionZ #blueZ {
  color: #484fa8;
}

#sectionZ img {
  width: 128px;
  padding-bottom: 2.5%;
}

@media (max-width: 768px) {
  #headerZ {
    position: fixed;
    bottom: 0px;
    width: 100%;
  }

  #headerZ ul .items {
    display: flex;
    width: 33%;
    justify-content: center;
  }

  #headerZ {
    background-color: rgba(6, 37, 98, 1);
    border: none;
    border-top: 4px solid #00b2ff;
  }

  #buttonOpenDesktop {
    display: none;
  }

  #buttonOpenDesktop svg {
    display: none;
  }

  #buttonOpenMobile svg {
    display: flex;
  }

  #buttonOpenMobile {
    background-color: #323995;
    width: 2.5em;
    height: 2.5em;
    border-radius: 50%;
    position: fixed;
    bottom: 5em;
    right: 0.6em;
  }
}

#buttonOpenDesktop {
  background-color: #323995;
  width: 2.5em;
  height: 2.5em;
  border-radius: 50%;
  position: fixed;
  bottom: 0.6em;
  right: 0.6em;
}

.svg-plus {
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}
</style>
