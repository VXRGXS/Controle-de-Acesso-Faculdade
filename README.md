# 🎓 Sistema de Controle de Acesso - Faculdade

## 📋 Sobre o Projeto
Sistema web desenvolvido para gerenciar o controle de acesso de alunos em ambiente universitário, permitindo o registro e monitoramento de entradas e saídas em tempo real.

## ✨ Funcionalidades Principais

- 📝 Cadastro de alunos
  - Geração automática de matrícula
  - Interface intuitiva para registro de dados
  
- 🚪 Controle de Acesso
  - Registro de entrada e saída
  - Validação em tempo real
  - Histórico de acessos
  
- 📊 Relatórios e Estatísticas
  - Dashboard com dados diários
  - Contagem de alunos presentes
  - Histórico de entradas e saídas
  - Listagem completa de alunos cadastrados

## 🛠️ Tecnologias Utilizadas

- **Front-end:**
  - HTML5
  - CSS3
  - JavaScript

- **Back-end:**
  - PHP
  - MySQL

- **Ambiente de Desenvolvimento:**
  - XAMPP

## 📁 Estrutura do Projeto

```
📦 controle-acesso/
├── 📂 css/
│   ├── style.css
│   └── style-cadastro.css
├── 📂 js/
│   ├── script.js
│   └── script-cadastro.js
│
├── cadastro.php
├── listar_alunos.php
├── 📄 index.php
├── 📄 home.html
├── 📄 README.md
├── router.php
└── setup.php

```

## 🚀 Como Executar o Projeto

1. **Pré-requisitos:**
   - XAMPP instalado
   - MySQL configurado
   - PHP 7.4 ou superior

2. **Configuração:**
   ```bash
   # Clone este repositório
   git clone https://github.com/VXRGXS/Controle-de-Acesso-Faculdade

   # Acesse a pasta do projeto
   cd Controle-de-Acesso-Faculdade

   # Rode este arquivo para criar a base de dados e as tabelas
   php .\setup.php

   # Ative o servidor
   php -S 127.0.0.1:8000 router.php
   
   ```

3. **Execução:**
   - Inicie o XAMPP (Apache e MySQL)
   - Acesse `http://127.0.0.1:8000/cadastro.html` para cadastrar o aluno
   - Acesse `http://127.0.0.1:8000/home.html` para controlar o acesso do aluno

## 👥 Autor
João Victor Santos Vargas da Silva - https://github.com/VXRGXS

## 📬 Contato
- Email: joaovargas1124@gmail.com
- LinkedIn: https://www.linkedin.com/in/joão-victor-santos-vargas-da-silva-32a153235/
