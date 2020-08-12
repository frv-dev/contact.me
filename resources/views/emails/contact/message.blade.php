<h2>Mensagem enviada pelo Contact.me:</h2>

<ul>
    <li><b>Nome:</b> {{ $contact->name }}</li>
    <li><b>E-mail:</b> {{ $contact->email }}</li>
    <li><b>Telefone/Celular:</b> {{ $contact->phone }}</li>
    <li><b>IP:</b> {{ $contact->ip }}</li>
</ul>

<b>Mensagem:</b> {{ $contact->message }}
