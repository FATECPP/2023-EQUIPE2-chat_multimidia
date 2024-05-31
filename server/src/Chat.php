<?php

namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\controllers\Controller;
use App\DAO\UserDAO;
use App\DAO\GroupDAO;
use App\DAO\MessageDAO;

error_reporting(E_ALL & ~E_DEPRECATED);
final class Chat extends Controller implements MessageComponentInterface 
{
    protected $clients;
    public static $usersSockets = [];
    public static $groupsActiveWebsockets = [];
    private $dao;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->dao = $this->getDataBase();
    }

    public function censurando($text){
        $words = [];
        foreach (file(__DIR__ . "/../data/words_blacklist.txt") as $line) {
            $words[] = explode("\n", $line)[0]; 
        }
    
        foreach ($words as $word) {
            $count = 0;
            $censura = "";
            while ($count < strlen($word)){
                $censura = $censura . "*";
                $count++;
            }
            $text = str_replace($word, $censura, $text);
        }
        return $text;
    }

    private function writeTextBlue($text)
    {
        return  "\033[34m" . $text . "\033[0m";
    }

    private function writeTextRed($text)
    {
        echo "\033[31m". $text ."\033[0m". "\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $url = $conn->httpRequest->getUri()->getQuery();

        parse_str($url, $output);

        $groupID = $output['groupID']; // coleta groupID da url
        $userID = $output['userID']; // coleta userID da url

        $queryUser = new UserDAO($this->dao);
        $user = $queryUser->findUserById($userID); // coleta o user partindo do ID dele
        $queryGroup = new GroupDAO($this->dao);
        $groupALL = $queryGroup->findGroupById($groupID); // coleta todos os grupos

        echo $this->writeTextBlue($user['name']).": he's currently in ". $this->writeTextBlue($groupALL['name']). "\n";


        Chat::$usersSockets[] = [$conn->resourceId => $groupID]; // jogo um array para classe Chat, onde key é id da conexao e valor é id do grupo

        $listConnUsers = [];
        foreach (Chat::$usersSockets as $userSocket) { // para cada conexao do array da Classe
            foreach ($userSocket as $key => $group) { // para cada userSocket pegue valor da Key, e valor do userSocket
                if ($group == $groupID) { // compara se group da Classe é indetico ao groupID enviado na request
                    $listConnUsers[] = $key; // joga a chave para array
                }
            }
        } // aqui coleto todos os id conectados naquele grupo
        
        foreach ($this->clients as $client) // para cada client
        {
            if(in_array($client->resourceId, $listConnUsers)){ // se client exister na lista de conectados 
                $client->send(json_encode(['status' => 'success', "group" => $groupALL['id'],'numbers' => count($listConnUsers), 'message' => 'updateNumbers'])); // envio para o client da request, o numero de usuarios conecetados
            }
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Connection: $from->resourceId:, send a message!\n";
        $clientOfGroup = [];
        $message = json_decode($msg, true);
        if($message['file']){
            $file = "";
            if($message['type'] == 'video'){
                $file = base64_decode(preg_replace('#^data:video/\w+;base64,#i', '', $message['file']));
            }else if($message['type'] == 'image'){
                $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $message['file']));
            }else if($message['type'] == 'audio'){
                $file = base64_decode(preg_replace('#^data:audio/\w+;base64,#i', '', $message['file']));
            }

            if($file !== false){
                $extension = "";
                if( $message['type'] == "video"){
                    $extension = ".mp4";
                }else if($message['type'] == "image" ){
                    switch($message['ext']){
                        case 'png':
                            $extension = ".png";
                            break;
                        case 'jpg':
                            $extension = ".jpg";
                            break;
                        case 'jpeg':
                            $extension = ".jpeg";
                            break;
                        case 'gif':
                            $extension = ".gif";
                            break;
                        default:
                            break;
                    }
                }else if($message['type'] == "audio"){
                    $extension = ".mp3";   
                }

                $message['url'] = $message['type']. date('--Y-m-d-H-i-s') . $extension;
                file_put_contents( __DIR__ . "/../uploads/" . $message['url'], $file);
                unset($message['file']);
                //unset($message['text']);
            }
        }
        
        var_dump($message);

        if($message['type'] == "text"){
            $message['text'] = $this->censurando($message['text']); // censuro a mensagem caso tenha algo indevido
        }

        foreach (Chat::$usersSockets as $userSocket) {
            foreach ($userSocket as $key => $groupID) {
                if ($groupID == $message['groupID']) {
                    $clientOfGroup[] = $key;
                }
            }
        }
        $queryMessage = new MessageDAO($this->dao);
        if($queryMessage->create(json_encode($message))['status'] == 'error'){
            echo "ERROR";
            foreach ($this->clients as $client) {
                if ($from === $client) {
                    unset($clientOfGroup[$from->resourceId]);
                    foreach ($clientOfGroup as $id) {
                        if ($client->resourceId == $id) {
                            $client->send(json_encode(['status' => 'error', 'messageID' => $message['id']]));
                        }
                    }
                }
            }
        }else {
            if($message['type'] == "text"){  // para distribuicao de texto
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        unset($clientOfGroup[$from->resourceId]);
                        foreach ($clientOfGroup as $id) {
                            if ($client->resourceId == $id) {
                                echo "\n send to ".$client->resourceId." \n";
                                $client->send($msg);
                            }
                        }
                    }
                }
            }else { // para distribuicao de arquivos
                foreach ($this->clients as $client) {
                    foreach ($clientOfGroup as $id) {
                        if ($client->resourceId == $id) {
                            echo "\n send the file to ".$client->resourceId." \n";
                            $client->send(json_encode($message));
                        }
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {

        $url = $conn->httpRequest->getUri()->getQuery();

        parse_str($url, $output);

        $groupID = $output['groupID'];
        $queryGroup = new GroupDAO($this->dao);
        $groupALL = $queryGroup->findAllGroups();

        $this->clients->detach($conn);
        $count = 0;
        foreach (Chat::$usersSockets as $userSocket) {
            foreach ($userSocket as $key => $group) {
                if ($key == $conn->resourceId) {
                    unset(Chat::$usersSockets[$count][$conn->resourceId]); /// removo as conexoes do array da Class
                }
            }
            $count++;
        }
        $this->writeTextRed("Connection {$conn->resourceId} has disconnected");

        $listConnUsers = [];
        foreach (Chat::$usersSockets as $userSocket) { // para cada conexao do array da Classe
            foreach ($userSocket as $key => $group) { // para cada userSocket pegue valor da Key, e valor do userSocket
                if ($group == $groupID) { // compara se group da Classe é indetico ao groupID enviado na request
                    $listConnUsers[] = $key; // joga a chave para array
                }
            }
        } // aqui coleto todos os id conectados naquele grupo
        
        foreach ($this->clients as $client) // para cada client
        {
            if(in_array($client->resourceId, $listConnUsers)){ // se client exister na lista de conectados 
                $client->send(json_encode(['status' => 'success', "group" => $groupALL['id'],'numbers' => count($listConnUsers), 'message' => 'updateNumbers'])); // envio para o client da request, o numero de usuarios conecetados
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
