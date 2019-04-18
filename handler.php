<?php

/**
 * Connexion simple à la base de données via PDO !
 */
$db = new PDO('mysql:host=localhost;dbname=chat;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$task= "list";
if(array_key_exists("task", $_GET)){
	$task=$_GET['task'];
}

if($task=="write")
{
	postMessage();
} else {
	getMessages();
}

function getMessages()
{
	global $db; // précise qu'on veut utiliser la variable à l'exterieur
	$resultats = $db->query("SELECT id, author, content, created_at from messages ORDER BY created_at DESC LIMIT 20");
	$messages = $resultats->fetchAll();
	echo json_encode($messages);
}

function postMessage()
{
	global $db;

	if(!(array_key_exists('author', $_POST)) || !(array_key_exists('content', $_POST)))
	{
		echo json_encode(["status"=>"error", "message"=>"One field or many have not been sent"]);
		return;
	}

	$author= $_POST['author'];
	$content=$_POST['content'];

	$query = $db->prepare('INSERT INTO messages SET author= :author, content= :content, created_at= NOW()');
	$query->execute([
		"author"=>$author,
		"content"=>$content
	]);

	echo json_encode(["status"=>"success"]);
}