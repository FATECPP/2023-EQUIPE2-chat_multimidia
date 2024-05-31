import { groupCurrent, idMessageError, connectionCurrent } from './ChatBody.vue';

export function reSend(id) {
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
console.log();
if (!connectionCurrent.value.CLOSED == 3) {
connectionCurrent.value.send(JSON.stringify(message));
} else {
idMessageError.value.push(message.id);
}
groupCurrent.value.messages.unshift(message);
}
