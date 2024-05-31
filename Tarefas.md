## Alterações

### 09/11
[x] - (BACK-end): Desconectar um aluno após ele sair da sala. <br />
[x] - (FRONT): Só pode apagar a mensagem, se ela não foi enviada ainda. <br />
[x] - (FRONT): No momento de reenviar a mensagem, não duplicar na tela a mensagem. <br />
[x] - (FRONT): Ajustar a tela inicial com algumas configurações do figma. 
https://www.figma.com/file/eMJTpDPiw6H74MVzayD7Hu/Untitled?type=design&node-id=1%3A2&mode=design&t=i26H6wvdChn7P09w-1 <br />
[x] - (FRONT): Colocar botão para ocultar barra de turmas e ajustar tamanho. <br />

### 18/10
[x] - (FRONT): Na listagem de alunos e professores, mostrar as turmas que eles pertencem. <br />
[x] - (BACK-END): Registar/contabilizar quais usuários estão conectados ao grupo. <br />
[x] - (FRONT e BACK): Mostrar na tela quantos usuários conectados. <br />
[x] - (BACK-END): Salvar a mensagem chamando o MessageDAO e não fazendo uma requisição. <br />
[x] - (FRONT): Caso a conexão caia (não conseguiu se comunicar com o WS), mostrar uma mensagem de erro na própria mensagem. 
E ter a possibilidade de reenviar a mensagem. Um detalhe, na hora de reenviar será preciso fazer a conexão novamente. <br />

### 05/10

[x] - (DADOS): Refatorar os IDs de grupos e usuarios para UUID. <br />
[x] - (FRONT): Na url de conexão do WebSocket, enviar o ID do grupo na url. Ex: ws://localhost:9090/group/{uuid} <br />
[x] - (FRONT): Se mensagem não conseguiu ser salva/enviada; mostrar a mensagem com erro e opção para reenviar. <br />
[x] - (FRONT): Implementar o envio de mensagem para o WebSocket. Enviar todas as informações que são salvas no banco. <br />
[x] - (WS): Salvar as mensagem enviadas no mongodb. <br />
[x] - (FRONT): Adicionar as mensagens no chat vindas do websocket. <br />

### 25/09

[x] - (FRONT) - Mostrar a mensagem com erro. E opção para reenviar. <br />
[x] - (PESQUISAR) - Criar uma conexão por sala/turma.
[x] - (API/WS) - Retornar com base na sala/turma.

### 11/09

[x] - Categoriar os usuarios: Professor e Aluno; <br />
[x] - Uma tela como selecionar um Professor ou Aluno;<br />
[x] - Adicionar um botão para sair do chat e voltar para tela de selecionar usuários;<br />
[x] - (API) - Mostrar mensagem apenas se ela foi gravada.<br />
[x] - (API) - Ajustar retornos.

### 01/08

[x] - Instalar o Slim ou uma biblioteca de rotas. <br>
[x] - Criar duas rotas. Uma para salvar as mensagens e outra para recuperar. <br>
[x] - O arquivo ".json" que salva as mensagens serem salvas na pasta do servidor. <br>
[x] - Estudar sobre "CORS" e liberar os acesso no servidor para todos dominios. <br>
