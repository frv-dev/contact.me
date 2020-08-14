<div align="center">
    <img src="./public/logo.png" width="200px" />
</div>

<h1 align="center">CONTACT.ME</h1>

<p align="center">A maneira mais simples de você ter o seu formulário de contato em minutos com PHP e React.</p>

<p align="center">
<img src="https://img.shields.io/github/license/frv-dev/contact.me" />
<img src="https://img.shields.io/github/repo-size/frv-dev/contact.me" />
</p>

## Tabela de conteúdos

* [Status do Projeto](#status-do-projeto)
* [Features](#features)
* [Pré-requisitos](#pre-requisitos)
* [Executando a Aplicação com o Docker](#executando-a-aplicacao-com-o-docker)
* [Executando a Aplicação sem o Docker](#executando-a-aplicacao-sem-o-docker)
* [Executando os Testes](#executando-os-testes)
* [Tecnologias Utilizadas](#tecnologias-utilizadas)
* [Autor](#autor)

## Status do Projeto

- Versão 1 pronta

## Features

- [x] Envio de e-mail pela tela de contato

## Pré-requisitos

Antes de executar a aplicação é preciso ter instalado as seguintes ferramentas no seu computador.

* Caso vá usar o docker:
    * Docker (versão utilizada 19.03.6, build 369ce74a3c);
    * Docker Compose (versão utilizada 1.26.1, build f216ddbf);
    * Navegador web (Firefox, Google Chrome, etc).
* Caso não vá usar o docker:
    * PHP 7.4 ou superior;
    * NodeJS 12.6 ou superior (de preferência);
    * Composer;
    * MySQL 8;
    * Mailhog (servidor SMTP falso para testes de envio de e-mail)(opcional);
    * Navegador web (Firefox, Google Chrome, etc).

## Executando a Aplicação com o Docker

Executar o comando `docker-compose up` para subir os containers.

__OBS:__ Esperar as libs do composer e npm serem instaladas para continuar.

Copiar o .env.example para .env e preencher com os dados necessário.

```.env
// Url do seu site com a porta
APP_URL=http://localhost:8085
// Url da sua api, já vem setado, alterar apenas se necessário
API_URL="${APP_URL}/api"

// Conexão com o seu banco de dados, abaixo segue o padrão do que está no docker-compose.yml que devem ser colocadas no .env
DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=contactme
DB_USERNAME=root
DB_PASSWORD=root

// Configuração para envio de e-mail por SMTP, abaixo seguem as configurações padrões que estão no docker-compose.yml que devem ser colocadas no .env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS='contact@contactme.com'
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TO_ADDRESS='receiver@contactme.com'
```

Executar o comando `docker exec contactme-web-php php artisan key:generate`, para gerar o APP_KEY.

Executar o comando `docker exec contactme-web-php php artisan migrate`, para criar as tabelas no banco de dados.

Agora é só acessar os seguintes links caso tenha usado o docker:

- http://localhost:8025 : Servidor de email falso com mailhog, ele vai receber todos os e-mails e ao acessar um e-mail poderá ver o HTML na aba HTML, um txt na aba Plain text, e os arquivos enviados na aba MIME.
- http://localhost:8085 : Aplicação Contact.me, é só preencher os dados e enviar a mensagem, o arquivo tem o limite de tamanho de 500kb = 62.5kB = 64000B.

Você pode acessar o banco de dados com os seguintes dados:

- Host: 127.0.0.1
- Port: 3309
- User: root
- Password: root
- Database: contactme

Nele você verá a tabela `contact` com todos os dados que são enviados pelo formulário.

OBS: O contêiner de banco de dados foi feito apenas para testes, esteja ciente de que, ao destruir o contêiner todos os dados desaparecerão, para evitar essa situação em produção será necessário criar um volume no arquivo docker-compose.yml para salvar os dados do banco de dados fora do container.

## Executando a Aplicação sem o Docker

Para executar a aplicação é preciso ter um servidor web (vou usar o que é provido pelo php, php -S), um servidor de banco de dados MySQL e um servidor de e-mail, pode ser usado o do gmail ou um servidor false, por exemplo.

Também é preciso ter o PHP 7.4 ou superior, o composer e o node 12.16 ou superior (de preferência) instalado.

Executar o comando `composer install` para instalar as dependências do PHP.

Executar o comando `npm install` para instalar as dependências do Node.

Copiar o .env.example para .env e preencher com os dados necessário.

```.env
// Url do seu site com a porta
APP_URL=http://localhost:8085
// Url da sua api, já vem setado, alterar apenas se necessário
API_URL="${APP_URL}/api"

// Conexão com o seu banco de dados
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contactme
DB_USERNAME=root
DB_PASSWORD=

// Configuração para envio de e-mail por SMTP
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS='contact@contactme.com'
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TO_ADDRESS='receiver@contactme.com'
```

Executar o comando `php artisan key:generate` para gerar o APP_KEY.

Criar um banco de dados com o nome `contactme` pelo seguinte comando através de um programa como o MySQL Workbench ou o DBeaver:

`CREATE SCHEMA `contactme` CHARACTER SET utf8;`

Executar o comando `php artisan migrate` para criar as tabelas no banco de dados.

Agora é só acessar o link da sua aplicação para começar a usá-la.

No banco de dados você verá a tabela `contact` com todos os dados que são enviados pelo formulário.

## Executando os Testes

Para executar os testes com pelo docker execute `docker exec contactme-web-php php artisan test`.

Para executar os testes sem o docker execte `php artisan test`.

## Tecnologias Utilizadas

Para o desenvolvimento do projeto foram utilizadas as seguintes tecnologia:

* Laravel 7;
* Bootstrap 4;
* PHP 7.4;
* React;
* TypeScript;
* Axios;
* Node.js;
* Mailhog;
* MySQL;
* Docker;

## Autor

<a href="https:/github.com/frv-dev" style="text-decoration: none;">
    <img src="https://avatars3.githubusercontent.com/u/20212780?s=460&u=31b263296ed9edab65b88e8a7ffbe9b29fef0664&v=4" width="100px;" alt=""/>
    <br />
    <b>Felipe Vieira</b>
</a>
<br />

[![Linkedin Badge](https://img.shields.io/badge/-Felipe%20Renan%20Vieira-blue?logo=Linkedin&logoColor=white&link=https://www.linkedin.com/in/felipe-renan-vieira/)](https://www.linkedin.com/in/felipe-renan-vieira/)
[![Gmail Badge](https://img.shields.io/badge/-feliperenanvieira%40gmail.com-red?logo=Gmail&logoColor=white&link=mailto:feliperenanvieira@gmail.com)](mailto:feliperenanvieira@gmail.com)
