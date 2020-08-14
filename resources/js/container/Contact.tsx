import React, { useState, useEffect, useRef } from 'react';
import ReactDOM from 'react-dom';
import getIp from '../service/get/getIp';
import phoneRegex from '../regex/phoneRegex';
import emailRegex from '../regex/emailRegex';
import sendMessage from '../service/post/sendMessage';

function Contact() {
  const nameRef = useRef<HTMLInputElement>(null);
  const emailRef = useRef<HTMLInputElement>(null);
  const phoneRef = useRef<HTMLInputElement>(null);
  const messageRef = useRef<HTMLTextAreaElement>(null);
  const fileRef = useRef<HTMLInputElement>(null);

  const fileTypes = [
    'text/plain',
    'application/pdf',
    'application/msword',
    'application/vnd.oasis.opendocument.text'
  ];

  async function submit(event: React.FormEvent<HTMLFormElement>): Promise<void> {
    event.preventDefault();

    try {
      const ip = await getIp();

      const name = nameRef.current?.value;
      const email = emailRef.current?.value;
      const phone = phoneRef.current?.value;
      const message = messageRef.current?.value;
      const file = fileRef.current;

      if([name, email, phone, message, file].includes(undefined)) {
        throw 'Preencha todos os campos.';
      }
      if(!fileTypes.includes(file!.files!.item(0)!.type)) {
        throw 'O tipo do arquivo é inválido.';
      }
      /**
       * EMBORA NÃO SEJA IDEAL COLOCAR COMENTÁRIOS COMO ESTE NO MEIO
       * DO CÓDIGO, ESTE COMENTÁRIO ESTÁ RELACIONADO AO TESTE TÉCNICO
       * PROPOSTO PELA EMPRESA, NESTE FOI INFORMADO QUE DEVERIA SER
       * ENVIADO UM ARQUIVO DE, NO MÁXIMO, 500kb COM b MINÚSCULO,
       * POR NÃO SABER SE FOI UM ERRO OU PROPOSITAL EU CONSIDEREI
       * KILOBITS (Kb) E NÃO KILOBYTES (KB), LOGO O ARQUIVO TERÁ NO MÁXIMO
       * O SEGUINTE VALOR:
       *
       * 500Kb / 8 = 62.5KB
       * 62.5KB * 1024 = 64000B
       *
       * SEGUE ESTÁ INFORMAÇÃO PARA IDENTIFICAR QUE, CASO SEJA KILOBYTES,
       * FOI UMA ESCOLHA PARA SEGUIR O QUE O TESTE TÉCNICO ESTAVA PEDINDO
       * E NÃO UM ERRO DE CÁLCULO.
       *
       * NOS TESTES UNITÁRIOS DO PHP UNIT TEM UM TESTE PARA O ENVIO E
       * VALIDAÇÃO DO ARQUIVO COM 64000B E 64001B NO QUAL É POSSÍVEL
       * VERIFICAR QUE O LIMITE REALMENTE É DE 64000B.
       *
       * ATT: FELIPE RENAN VIEIRA
       */
      if(file!.files!.item(0)!.size > 64000) throw 'O arquivo não pode ser maior do que 500Kb/62,5KB/64000B';
      if (!phone!.match(phoneRegex)) throw 'O telefone é inválido.';
      if (!email!.match(emailRegex)) throw 'O e-mail é inválido.';

      const data = new FormData();
      data.append('name', name!);
      data.append('email', email!);
      data.append('phone', phone!);
      data.append('message', message!);
      data.append('file', file!.files!.item(0)!);
      data.append('ip', ip);

      const response = await sendMessage(data);
      alert(response.message);
    } catch (error) {
      alert('Erro');
      console.log(error);
    }
  }

  return (
    <div>
      <h1>Contact.me</h1>
      <form onSubmit={submit}>
        <label htmlFor="name">Nome: </label>
        <input type="text" id="name" ref={nameRef} required={true} />

        <label htmlFor="email">E-mail: </label>
        <input type="email" id="email" ref={emailRef} required={true} />

        <label htmlFor="phone">Telefone: </label>
        <input
          type="text"
          id="phone"
          ref={phoneRef}
          required={true}
          maxLength={15}
        />

        <label htmlFor="message">Mensagem: </label>
        <textarea id="message" ref={messageRef} required={true} />

        <label htmlFor="file">Arquivo: </label>
        <input
          type="file"
          id="file"
          ref={fileRef}
          required={true}
          accept=".doc,.pdf,.docx,.odt,.txt"
        />

        <button type="submit">Enviar</button>
      </form>
    </div>
  );
}

export default Contact;

if (document.getElementById('contact')) {
  ReactDOM.render(<Contact />, document.getElementById('contact'));
}
