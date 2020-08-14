import React, { useState, useRef } from 'react';
import ReactDOM from 'react-dom';
import getIp from '../service/get/getIp';
import phoneRegex from '../regex/phoneRegex';
import emailRegex from '../regex/emailRegex';
import sendMessage from '../service/post/sendMessage';
import style from './Contact.style';

const logo = require('../../../public/logo.png');

function Contact() {
  const nameRef = useRef<HTMLInputElement>(null);
  const emailRef = useRef<HTMLInputElement>(null);
  const phoneRef = useRef<HTMLInputElement>(null);
  const messageRef = useRef<HTMLTextAreaElement>(null);
  const fileRef = useRef<HTMLInputElement>(null);

  const [message, setMessage] = useState<{type: null|'error'|'success', text: string}>({ type: null, text: '' });
  const [sending, setSending] = useState(false);

  const fileTypes = [
    'text/plain',
    'application/pdf',
    'application/msword',
    'application/vnd.oasis.opendocument.text'
  ];

  function clear(): void {
    nameRef.current!.value = '';
    emailRef.current!.value = '';
    phoneRef.current!.value = '';
    messageRef.current!.value = '';
    fileRef.current!.value = '';

    setMessage({
      type: null,
      text: '',
    });
  }

  async function submit(event: React.FormEvent<HTMLFormElement>): Promise<void> {
    event.preventDefault();

    if(sending) return;
    setSending(true);

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

      setMessage({
        type: 'success',
        text: response.message,
      })
    } catch (error) {
      if(typeof error === 'string') setMessage({
        type: 'error',
        text: error,
      });
      else {
        setMessage({
          type: 'error',
          text: 'Erro desconhecido, tente novamente ou entre em contato com o adminstrador do sistema.',
        });
        console.log(error);
      }
    } finally {
      setSending(false);
    }
  }

  return (
    <div style={style.body}>
      <div className="container py-4">
        <div className="row">
          <div className="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-12 p-5" style={style.card}>
            <div className="text-center mb-3">
              <img src={logo} alt="Contact.me" style={style.logo} />
            </div>
            <h1 className="text-center mb-3">Contact.me</h1>
            <hr/>
            {
              message.text &&
              <p className={
                'text-center' +
                (message.type === 'success' ? ' text-success' : ' text-danger')
              }>{message.text}</p>
            }
            <form onSubmit={submit}>
              <div className="form-group">
                <label htmlFor="name">Nome: <span className="text-danger">*</span></label>
                <input className="form-control" type="text" id="name" ref={nameRef} required={true} />
              </div>

              <div className="form-group">
                <label htmlFor="email">E-mail: <span className="text-danger">*</span></label>
                <input className="form-control" type="email" id="email" ref={emailRef} required={true} />
              </div>

              <div className="form-group">
                <label htmlFor="phone">Telefone: <span className="text-danger">*</span></label>
                <input
                  className="form-control"
                  type="text"
                  id="phone"
                  ref={phoneRef}
                  required={true}
                  maxLength={15}
                />
              </div>

              <div className="form-group">
                <label htmlFor="message">Mensagem: <span className="text-danger">*</span></label>
                <textarea
                  className="form-control"
                  id="message"
                  ref={messageRef}
                  required={true}
                  rows={5}
                />
              </div>

              <div className="form-group">
                <label htmlFor="file">Arquivo (62.5 KB no máximo): <span className="text-danger">*</span></label>
                <input
                  className="form-control"
                  type="file"
                  id="file"
                  ref={fileRef}
                  required={true}
                  accept=".doc,.pdf,.docx,.odt,.txt"
                />
              </div>

              <div className="row">
                <div className="col-12 d-flex justify-content-center">
                  <button className="btn btn-secondary" type="button" onClick={clear} disabled={sending}>
                    LIMPAR
                  </button>
                  &nbsp;
                  <button className="btn btn-success" type="submit" disabled={sending}>
                    {sending ? 'ENVIANDO...' : 'ENVIAR'}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Contact;

if (document.getElementById('contact')) {
  ReactDOM.render(<Contact />, document.getElementById('contact'));
}
