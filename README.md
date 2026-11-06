# Oficina Laravel - Sistema de Gestão para Oficina Mecânica

Sistema completo de gestão para oficina mecânica desenvolvido em **PHP 7.2** e **Laravel 7**, orquestrado via **Docker** seguindo o padrão de nomenclatura `bdsoftcom`, arquitetura **SOLID** e entidades/classes nomeadas integralmente em **Português do Brasil (PT-BR)**.

---

## 🛠️ Stack Tecnológica & Infraestrutura Docker (`bdsoftcom`)

O ambiente Docker foi desenhado para isolamento completo, consistência e alta performance:

| Container | Imagem / Base | Descrição | Portas Mapeadas |
|---|---|---|---|
| **`bdsoftcom_app`** | PHP 7.2-FPM + Composer 2.2 | Aplicação Laravel 7, extensões PDO MySQL, GD, Zip, Bcmath | Interna (9000) |
| **`bdsoftcom_web`** | Nginx 1.19 Alpine | Servidor web HTTP com proxy FastCGI | `8000:80` |
| **`bdsoftcom_db`** | MySQL 5.7 | Banco de dados relacional compatível com PHP 7.2 | `3307:3306` |
| **`bdsoftcom_phpmyadmin`** | phpMyAdmin Latest | Interface web para gerenciamento visual do MySQL | `8081:80` |


## 🔑 Credenciais Padrão de Acesso

- **E-mail:** `admin@oficina.com.br`
- **Senha:** `senha123`
