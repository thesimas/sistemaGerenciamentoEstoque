<div align="center">
  <h1>Sistema de Controle de Estoque Multi-Tenant</h1>
  <p>
    Uma aplicação Web robusta desenvolvida em <strong>PHP Puro (Orientado a Objetos)</strong> e <strong>MySQL</strong>, focada em boas práticas de Arquitetura de Software e Padrões de Projeto.
  </p>
  
  <p>
    <a href="#-sobre-o-projeto">Sobre</a> •
    <a href="#-desenvolvedores">Desenvolvedores</a> •
    <a href="#-arquitetura-e-padrões">Arquitetura</a> •
    <a href="#-funcionalidades">Funcionalidades</a> •
    <a href="#-tecnologias">Tecnologias</a> •
    <a href="#-como-executar">Como Executar</a>
  </p>
</div>

<hr>

## Sobre o Projeto

Este projeto consiste em uma plataforma de **Gestão de Estoque no modelo SaaS (Software as a Service) Multi-Tenant (Multi-inquilino)**. O objetivo é permitir que diversas empresas (clientes) utilizem a mesma infraestrutura e banco de dados para gerenciar seus inventários de forma **totalmente isolada, autônoma e segura**.

> **Modelo de Negócio:** A plataforma opera com controle centralizado. O **Administrador Geral** (dono do SaaS) é responsável por gerenciar as contas das empresas contratantes. Cada empresa logada acessa exclusivamente o seu próprio ecossistema de produtos, categorias, fornecedores e relatórios financeiros.

---

## Desenvolvedores

Projeto integrador desenvolvido por:

* **Luciano Simas Junior**
* **Cesar Nahra**

---

## Arquitetura e Padrões de Projeto

O sistema foi estruturado utilizando os conceitos abaixo:

* **POO (Programação Orientada a Objetos):** Aplicação de Herança (`Usuario` ➔ `Cliente` / `Administrador`), Encapsulamento, Polimorfismo e Classes Abstratas.
* **Padrão MVC (Model-View-Controller):** Separação estrita entre regras de negócio (`Model`), apresentação (`View`) e roteamento/interceptação de requisições (`Controller`).
* **Padrão DAO (Data Access Object):** Isolamento total dos comandos SQL em classes exclusivas de persistência (`UsuarioDAO`, `ProdutoDAO`, etc.), mantendo as entidades de domínio limpas e responsáveis apenas por seus próprios estados e comportamentos (ex: método `estoqueBaixo()`).
* **Multi-Tenancy (Isolamento Lógico):** Todas as consultas e mutações no banco de dados injetam automaticamente a restrição do `id_usuario` logado, impedindo vazamento horizontal de dados entre empresas distintas.
* **Integridade Relacional:** Estrutura de banco otimizada com chaves estrangeiras utilizando `ON DELETE CASCADE` (para remoção limpa de perfis) e `ON DELETE SET NULL` (garantindo que a exclusão de uma categoria ou fornecedor preserve o histórico e o cadastro dos produtos associados).

### Camada de Domínio

<div align="center">
  <img src="DiagramaDeClasse.png" alt="Diagrama de Classes da Camada de Domínio" width="750px">
  <br>
  <i>Diagrama de Classes ilustrando as entidades centrais do domínio e seus relacionamentos.</i>
</div>

---

## Funcionalidades

### Painel do Cliente (Empresa Contratante)
* **Dashboard Interativo:** Menu visual em grade de navegação rápida.
* **Gestão de Inventário (CRUD Completo):** Cadastro de produtos com código SKU, preços, saldo inicial, estoque mínimo de segurança e vinculação relacional.
* **Ajuste Rápido de Saldo:** Operação ágil na própria tabela listada para entradas e saídas rápidas no dia a dia do balcão.
* **Categorias e Fornecedores:** Gestão autônoma de fornecedores e categorias próprias.
* **Alertas Inteligentes:** Destaque visual em tempo real na listagem para mercadorias que atingiram ou caíram abaixo do **Estoque Mínimo**.
* **Central de Relatórios Gerenciais:**
  * **Financeiro:** Apuração do capital total investido no inventário (`Preço x Quantidade`).
  * **Extrato / Histórico:** Auditoria completa de todas as movimentações por intervalo de datas.
  * **Ranking de Vendas e Compras:** Gráficos interativos renderizados em **Chart.js** exibindo os 5 itens mais movimentados (Entradas ou Saídas).
  * **Exportação:** Suporte nativo à geração de relatórios otimizados para impressão ou salvar em PDF.
* **Gestão de Perfil:** Atualização de dados corporativos, troca de senha com verificação dinâmica, upload de foto de perfil e opção de **Exclusão Definitiva da Conta** (apaga todo o ecossistema da empresa em cascata).

### Painel do Administrador Geral
* **Visão Global:** Listagem e monitoramento de todas as empresas cadastradas na plataforma.
* **Moderação:** Capacidade de revogar e excluir contas de clientes do servidor.

---

## Tecnologias Utilizadas

* **Back-end:** PHP
* **Banco de Dados:** MySQL
* **Front-end:** HTML5, CSS3 e JavaScript
* **Visualização de Dados:** Chart.js (Geração dos gráficos)

---

## Como Executar o Projeto

1. **Pré-requisitos:** Certifique-se de ter um ambiente web local rodando (ex: **XAMPP**).
2. **Clonagem:** Baixe ou clone este repositório para dentro do diretório raiz do servidor web.
3. **Banco de Dados:**
   * Acesse o seu gerenciador MySQL (ex: `http://localhost/phpmyadmin`).
   * Crie ou selecione um banco de dados e importe o arquivo **`script_estoque.sql`** (presente na raiz do projeto). Ele construirá todas as tabelas e povoará o sistema com dados reais para testes.
4. **Configuração de Conexão:**
   * Verifique o arquivo `Config/Conexao.php` e ajuste as credenciais de acesso ao MySQL (`$usuario`, `$senha`), caso necessário.
5. **Acesso no Navegador:**
   * Abra: `http://localhost/projetoEstoque/View/Login.php`
   * **Credenciais de Teste (Admin):** Email: `admin@sistema.com` | Senha: `123`
   * **Credenciais de Teste (Cliente TechZone):** Email: `tech@cliente.com` | Senha: `123`