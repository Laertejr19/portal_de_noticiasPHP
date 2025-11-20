📘 Sobre o Projeto

O portal_de_noticiasPHP apresenta um sistema completo de gerenciamento de notícias, incluindo registro de usuários, login, painel administrativo e exibição pública das notícias.

Ele serve como base para entender:

fluxo de autenticação;

CRUD completo em PHP;

organização de arquivos no backend;

exibição dinâmica de conteúdo;

upload de imagens;

integração com MySQL.

🛠️ Tecnologias Utilizadas
Categoria	Ferramenta
Linguagem	PHP
Banco de Dados	MySQL
Servidor Local	XAMPP / WAMP / Laragon
Versão Recomendada	PHP 7.4+
Navegador	Chrome / Firefox
Editor de Código	VS Code
📁 Estrutura do Projeto
portal_de_noticiasPHP/
│
├── conexao.php
├── funcoes.php
│
├── login.php
├── logout.php
├── cadastro.php
├── verifica_login.php
│
├── index.php
├── noticia.php
│
├── nova_noticia.php
├── editar_noticia.php
├── excluir_noticia.php
│
├── gerenciar_usuarios.php
├── editar_usuario.php
├── excluir_usuario.php
│
├── imagens/          → uploads das notícias
├── style.css
└── dump.sql          → script do banco de dados

🔑 Lógica de Autenticação

O sistema utiliza sessões PHP para:

validar credenciais do usuário,

manter o login ativo,

impedir acesso ao painel sem autenticação,

permitir logout destruindo a sessão.

🖼️ Lógica de Upload de Imagens

As imagens são enviadas pelo formulário da notícia.

São salvas na pasta /imagens.

O caminho é gravado no banco de dados para exibição.

✔️ Funcionalidades Implementadas

Cadastro de usuários

Login e logout

Verificação de sessão ativa

Cadastro de notícias

Edição de notícias

Exclusão de notícias

Exibição pública das notícias

Página individual para cada notícia

Upload de imagem

Gerenciamento de usuários

Painel administrativo protegido

🚀 Possíveis Melhorias

Paginação das notícias

Melhor sistema de categorias

Proteção contra SQL Injection com prepared statements

Sistema de comentários

Editor de texto avançado (WYSIWYG)

Atributos de permissão (admin / editor)

Melhoria da interface com Bootstrap

Campo de busca interna

👨‍💻 Autor

Nome: Laerte Ferraz da Silva Júnior
Instituição: Curso Técnico em Informática — Escola Ulbra São Lucas
Disciplina: Desenvolvimento Web II – PHP
Professor: Jeferson Leon

📄 Licença

Projeto desenvolvido para fins educacionais.
Livre para estudo, modificação e uso, desde que mantidos os créditos ao autor.

“Programar é transformar lógica em experiência. Cada página é uma conversa entre servidor e usuário.” 💻🔥
