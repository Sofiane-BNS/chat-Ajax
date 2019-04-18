function getMessages()
{
	 const requeteAjax = new XMLHttpRequest();
	 requeteAjax.open("GET","handler.php");

	 requeteAjax.onload= function(){
	 	const resultat= JSON.parse(requeteAjax.responseText);
	 	const html= resultat.reverse().map(function(message){
		 		return `
		 		<div class="message">
					<span class="date">${message.created_at.substring(11,16)}</span>
					<span class="author">${message.author}</span>
					<span class="content"> ${message.content} </span>
				</div>
		 		`
		 	}).join('');

	 	const messages=document.querySelector('.messages');
	 	messages.innerHTML=html;
	 	messages.scrollTop=messages.scrollHeight;
	 	
	 	}

	 requeteAjax.send();
}

function postMessages(event)
{
	event.preventDefault();

	const author = document.querySelector('#author');
	const content = document.querySelector('#content');

	const data = new FormData();
	data.append('author',author.value);
	data.append('content',content.value);

	const requeteAjax = new XMLHttpRequest();
	requeteAjax.open('POST','handler.php?task=write');

	requeteAjax.onload= function(){
		content.value='';
		content.focus();
		getMessages(); 
	};

	requeteAjax.send(data);

}

document.querySelector('form').addEventListener('submit', postMessages);

const interval = window.setInterval(getMessages,3000);

getMessages();