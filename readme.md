1. # Objetivo
	Desenvolver uma aplicação web em PHP que:
	1. Tenha um sistema de login com sessão (obrigatório);
	1. Após o login, permita ao usuário acessar um CRUD completo (Create, Read, Update, Delete) de qualquer entidade à sua escolha (ex.: produtos, tarefas, alunos, livros, etc);
	1. Seja apresentada em sala de aula, demonstrando o funcionamento do sistema e as funcionalidades implementadas.
	
1. # Requisitos obrigatórios
	1. Cadastro de usuários (com senha criptografada);
	1. Login e Logout com controle de sessão (SESSION);
	1. Proteção de páginas internas (somente usuários logados acessam);
	1. CRUD completo de pelo menos uma entidade (tema livre);
	1. Conexão com banco de dados MySQL;
	1. Utilização de métodos POST e GET para envio e recebimento de dados.
	
1. # Estrutura mínima do banco de dados
	Tabela obrigatória:
	
	```
	CREATE TABLE users ( id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(100) NOT NULL, email VARCHAR(100) UNIQUE NOT NULL, senha VARCHAR(255) NOT NULL); 
	```
	
	Além disso, o aluno deve criar ao menos uma tabela adicional para o CRUD principal, de acordo com o tema escolhido. 

1. # Conceitos avaliados
	| Conceito | Avaliação |
	|----------|-----------|
	| POST | Formulários de login, cadastro, criação e edição |
	| GET | Acesso a IDs para editar/excluir |
	| SESSION | Controle de login e restrição de acesso |
	| password_hash / password_verify | Segurança de senhas |
	| mysqli ou PDO | Conexão e manipulação no banco |
	| include / require | Organização do código |
	| header() | Login e logout |
	| Validação de formulário | Cadastro e CRUD |

1. # Critérios de avaliação
	| Critério | Pontos |
	|----------|:---------:|
	| Login funcional (sessão e proteção de páginas) | 2.0 |
	| CRUD completo de uma entidade | 3.0 |
	| Estrutura organizada e uso de includes | 1.0 |
	| Uso correto de POST, GET e SESSION | 1.0 |
	| Validação e segurança básica (senha hash, inputs) | 1.0 |
	| Interface (HTML/CSS simples e funcional) | 1.0 | 
	| Apresentação em sala e demonstração funcional do sistema | 1.0 |
	| Total | 10.0 |
