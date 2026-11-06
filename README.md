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

* **Rede Isolada:** `bdsoftcom_network` (driver bridge)
* **Volume Persistente:** `bdsoftcom_db_data` (dados preservados do banco)

---

## 🏛️ Padrões de Projeto & SOLID

A aplicação implementa os 5 princípios do SOLID:

1. **S - Single Responsibility Principle (SRP):**
   - **Controllers:** Tratam exclusivamente da camada HTTP e retorno de visualizações/respostas.
   - **Form Requests:** Responsáveis unicamente pela validação e sanitização de dados de entrada.
   - **Services:** Concentram as regras de negócio do domínio automotivo (ex: cálculo de orçamentos, baixa no estoque de peças, registro de históricos).
   - **Repositories:** Abstraem o acesso e persistência no banco de dados Eloquent.

2. **O - Open/Closed Principle (OCP):**
   - Camadas de serviço e repositório dependem de interfaces (`Contratos`), permitindo novas implementações (ex: auditorias, cache Redis) sem modificar o código existente.

3. **L - Liskov Substitution Principle (LSP):**
   - Todas as implementações em `app/Repositories/Eloquent/` e `app/Services/` respeitam estritamente suas interfaces correspondentes.

4. **I - Interface Segregation Principle (ISP):**
   - Interfaces enxutas e específicas para cada entidade (`ClienteRepositorioInterface`, `VeiculoRepositorioInterface`, `PecaRepositorioInterface`, `OrcamentoRepositorioInterface`, `OrdemServicoRepositorioInterface`).

5. **D - Dependency Inversion Principle (DIP):**
   - Inversão de dependência através do `RepositorioServiceProvider`, injetando abstrações nos controladores ao invés de classes concretas.

---

## 📦 Módulos do Sistema (Nomenclatura PT-BR)

### 1. Padrões: Usuários, Autenticação e Clientes
- `Usuario` (`usuarios`): Controle de acesso, autenticação nativa, perfis (administrador, mecânico, atendente).
- `Cliente` (`clientes`): Gestão completa de clientes (CPF/CNPJ, contatos, endereços e vínculos de veículos).

### 2. Ordens de Serviço (O.S.)
- `OrdemServico` (`ordens_servico`):
  - Número único (`OS-2026-XXXX`).
  - Fluxo de status: `aberta` -> `em_diagnostico` -> `aguardando_pecas` -> `em_execucao` -> `finalizada` / `cancelada`.
  - Baixa automática no estoque de peças ao associar na O.S. e estorno caso a ordem seja cancelada.
  - Conclusão com registro de laudo e serviços realizados.

### 3. Histórico de Veículos
- `Veiculo` (`veiculos`): Placa, marca, modelo, ano, chassi e quilometragem atual.
- `HistoricoVeiculo` (`historicos_veiculo`):
  - Registro cronológico automático de todas as passagens do veículo pela oficina.
  - Rastreamento de quilometragem por evento.
  - Vínculo direto com cada Ordem de Serviço executada.

### 4. Controle de Peças, Serviços e Orçamentos
- `Peca` (`pecas`): Código, estoque atual, estoque mínimo, preço de custo, preço de venda e alertas automáticos de reposição.
- `Servico` (`servicos`): Catálogo de serviços e tabelas de mão de obra (código, tempo estimado em minutos, preço padrão).
- `Orcamento` (`orcamentos`):
  - Número único (`ORC-2026-XXXX`).
  - Itens dinâmicos (peças e serviços do catálogo com autopreenchimento).
  - Cálculo de subtotais, mão de obra, descontos e total líquido.
  - Conversão em 1 clique do orçamento aprovado diretamente em Ordem de Serviço.

---

## 🚀 Como Executar o Projeto

Com o Docker e Docker Compose instalados no seu ambiente:

```bash
# 1. Entrar na pasta do projeto
cd Oficina-Laravel

# 2. Subir todos os containers bdsoftcom
docker compose up -d --build
```

O script `docker/entrypoint.sh` aguardará o banco de dados `bdsoftcom_db`, instalará automaticamente as dependências do Composer, executará as migrações e criará os dados iniciais através dos seeders.

---

## 🌐 URLs de Acesso Local

- **Aplicação Web (Oficina Laravel):** [http://localhost:8000](http://localhost:8000)
- **phpMyAdmin (Gerenciador MySQL):** [http://localhost:8081](http://localhost:8081)
  - Servidor: `bdsoftcom_db`
  - Usuário: `bdsoftcom_user` (ou `root`)
  - Senha: `bdsoftcom_pass` (ou `bdsoftcom_root_pass`)

---

## 🔑 Credenciais Padrão de Acesso

- **E-mail:** `admin@oficina.com.br`
- **Senha:** `senha123`
