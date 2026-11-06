# 📚 Guia Definitivo de Estudo do Código: Oficina Laravel

Este guia foi elaborado para que você possa **estudar, dominar e explicar** cada linha de código do sistema **Oficina Laravel**. Ele detalha a arquitetura, o fluxo das requisições, a separação de responsabilidades e onde encontrar exatamente cada funcionalidade no projeto.

---

## 🏛️ 1. Filosofia Arquitetural do Sistema

O sistema foi concebido sob os princípios da **Clean Architecture** (Arquitetura Limpa) e dos padrões **SOLID**, garantindo que o código seja testável, desacoplado e fácil de manter.

### O Ciclo de Vida de uma Ação (Fluxo de Dados)

```
[ Navegador / Usuário ]
          │ (Requisição HTTP: GET, POST, PUT, DELETE)
          ▼
[ routes/web.php ] ────────► Define a rota e URL
          │
          ▼
[ Middleware: VerificarPerfil ] ──► Valida permissões RBAC (Admin, Mecânico, Atendente)
          │
          ▼
[ Form Request: Validação ] ────► Sanitiza dados (ex: remove pontos de CPF) e valida regras
          │
          ▼
[ Controller ] ─────────────► Apenas recebe a requisição e coordena o fluxo (Skinny Controller)
          │
          ▼
[ Service (Regra de Negócio) ] ─► Executa cálculos, lógica de estoque, transições de status
          │
          ▼
[ Repositório (Contrato / Eloquent) ] ─► Isola as queries e acesso aos dados
          │
          ▼
[ Model (Eloquent ORM) ] ────► Mapeia tabelas e relacionamentos do MySQL
          │
          ▼
[ Blade View (Interface HTML) ] ─► Renderiza a tela responsiva com Bootstrap 4
```

---

## 🗺️ 2. Mapa Rápido de Arquivos: "Onde encontrar o quê?"

| O que você quer estudar ou modificar? | Onde está o código no projeto? |
| :--- | :--- |
| **Definição de Rotas e URLs** | [`routes/web.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/routes/web.php) |
| **Controle de Acesso / Perfis (RBAC)** | [`app/Http/Middleware/VerificarPerfil.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Middleware/VerificarPerfil.php) |
| **Validação e Sanitização de Formulários** | [`app/Http/Requests/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Requests) |
| **Controladores Web (Recebimento de Dados)** | [`app/Http/Controllers/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Controllers) |
| **Regras de Negócio e Cálculos (Services)** | [`app/Services/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Services) |
| **Acesso ao Banco de Dados (Repositories)** | [`app/Repositories/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Repositories) |
| **Estrutura das Tabelas (Migrations)** | [`database/migrations/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/database/migrations) |
| **Modelos e Relacionamentos (Eloquent)** | [`app/Models/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Models) |
| **Telas e Interface Gráfica (HTML / Blade)** | [`resources/views/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views) |
| **Layout Base, Sidebar e Navbar** | [`resources/views/layouts/app.blade.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views/layouts/app.blade.php) |
| **Injeção de Dependências (IoC Container)** | [`app/Providers/RepositorioServiceProvider.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Providers/RepositorioServiceProvider.php) |

---

## 🔍 3. Mergulho Detalhado em Cada Camada

### 3.1. Camada de Roteamento & Middlewares

#### 1. Rotas do Sistema: [`routes/web.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/routes/web.php)
Todas as URLs do sistema estão centralizadas aqui. As rotas são organizadas por prefixos lógicos (`clientes`, `veiculos`, `pecas`, `servicos`, `orcamentos`, `ordens_servico`, `usuarios`) e envolvidas no grupo com middleware `auth`.

#### 2. Controle de Permissões: [`app/Http/Middleware/VerificarPerfil.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Middleware/VerificarPerfil.php)
Implementa a matriz de permissões **RBAC (Role-Based Access Control)**:
- **`administrador`**: Acesso irrestrito a todas as áreas, incluindo cadastro de usuários e exclusões críticas.
- **`atendente`**: Operações comerciais (cadastrar clientes, veículos e criar orçamentos). Bloqueado de alterar status técnico de O.S. ou gerenciar funcionários.
- **`mecanico`**: Foco técnico na oficina (executar O.S., registrar laudo técnico, requisitar peças e finalizar manutenções).

---

### 3.2. Camada de Validação: `Form Requests`

Localizada em [`app/Http/Requests/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Requests). Evita que lógica de validação "suje" os Controllers.

* **Destaque de Engenharia — [`prepareForValidation()`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Requests/ClienteRequisicao.php#L14-L21)**:
  Antes de validar, o sistema limpa pontuações de máscaras JavaScript:
  ```php
  protected function prepareForValidation()
  {
      if ($this->has('cpf_cnpj')) {
          $this->merge([
              'cpf_cnpj' => preg_replace('/[^0-9]/', '', $this->cpf_cnpj)
          ]);
      }
  }
  ```
* **Arquivos Chave**:
  - [`ClienteRequisicao.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Requests/ClienteRequisicao.php): Validação de CPF/CNPJ único, e-mail e telefone.
  - [`VeiculoRequisicao.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Requests/VeiculoRequisicao.php): Validação de placa padrão Mercosul/Cinza e quilometragem.
  - [`PecaRequisicao.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Requests/PecaRequisicao.php): Preços de custo e venda, estoque mínimo.
  - [`ServicoRequisicao.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Requests/ServicoRequisicao.php): Código, descrição e preço padrão da mão de obra.
  - [`OrcamentoRequisicao.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Requests/OrcamentoRequisicao.php): Validação de arrays dinâmicos de itens.

---

### 3.3. Camada de Controladores (`Controllers`)

Localizada em [`app/Http/Controllers/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Controllers). Seguindo o padrão de **Skinny Controllers**, os métodos possuem apenas 3 a 8 linhas de código:
1. Recebem a requisição validada.
2. Delegam a ação para a camada de Serviço (`Service`).
3. Retornam a resposta (redirecionamento com mensagem flash ou renderização de View).

* **Arquivos Chave**:
  - [`AutenticacaoController.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Controllers/AutenticacaoController.php): Métodos `mostrarLogin()`, `entrar()` e `sair()`.
  - [`OrcamentoController.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Controllers/OrcamentoController.php): Criação de orçamento, aprovação (`aprovar()`) e conversão em O.S. (`converterEmOS()`).
  - [`OrdemServicoController.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Controllers/OrdemServicoController.php): Atualização de status operacional (`atualizarStatus()`), finalização com laudo (`finalizar()`) e cancelamento com estorno (`cancelar()`).
  - [`VeiculoController.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Controllers/VeiculoController.php): Inclui a ação `historico()` que monta a linha do tempo do carro.

---

### 3.4. Camada de Regras de Negócio (`Services`)

Localizada em [`app/Services/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Services). Esta é a **inteligência do sistema**:

#### 1. Orçamentos Inteligentes: [`OrcamentoServico.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Services/OrcamentoServico.php)
- **Cálculo Matemático Automático**: Itera sobre os itens, computa subtotais de peças (`valor_pecas`), subtotais de serviços (`valor_servicos`), aplica descontos (`valor_desconto`) e calcula o `valor_total`.
- **Conversão em 1 Clique**:
  O método `converterEmOrdemServico()` executa uma transação de banco de dados (`DB::transaction`):
  1. Marca o orçamento como convertido.
  2. Cria a nova Ordem de Serviço com numeração sequencial formatada (ex: `OS-202611-0001`).
  3. Clona todos os itens do orçamento para a O.S.
  4. Debita automaticamente as peças do estoque através de [`PecaRepositorio::decrementarEstoque()`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Repositories/Eloquent/PecaRepositorio.php).
  5. Registra o evento de entrada na linha do tempo do veículo.

#### 2. Ordens de Serviço & Almoxarifado: [`OrdemServicoServico.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Services/OrdemServicoServico.php)
- **Controle de Estoque Rigoroso**: Impede a conversão ou adição de peça se a quantidade solicitada for maior que o saldo em estoque.
- **Garantia de Estorno**: Ao cancelar uma O.S., as peças reservadas voltam instantaneamente para o estoque físico.
- **Laudo Técnico e Prontuário**: Ao finalizar uma O.S., o método `finalizarOrdemServico()` grava o laudo do mecânico e gera o evento definitivo no histórico do carro.

---

### 3.5. Camada de Dados (`Repositories` e `Models`)

#### 1. Inversão de Dependência via Interfaces
Na pasta [`app/Repositories/Contratos/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Repositories/Contratos) residem as interfaces (ex: [`OrcamentoRepositorioInterface.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Repositories/Contratos/OrcamentoRepositorioInterface.php)).
Na pasta [`app/Repositories/Eloquent/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Repositories/Eloquent) residem as implementações reais com Eloquent ORM.

> **Por que isso é importante?**
> Os Serviços dependem de *interfaces*, não do banco de dados diretamente. Se um dia a oficina migrar de MySQL para MongoDB ou consumir uma API externa, nenhuma regra de negócio precisa ser reescrita.

#### 2. Modelos Eloquent: [`app/Models/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Models)
- [`Cliente.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Models/Cliente.php): Relacionamento `hasMany` com `Veiculo`, `Orcamento` e `OrdemServico`.
- [`Veiculo.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Models/Veiculo.php): Relacionamento `belongsTo` com `Cliente` e `hasMany` com `HistoricoVeiculo`.
- [`Orcamento.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Models/Orcamento.php) e [`OrdemServico.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Models/OrdemServico.php): Relacionamento `hasMany` com seus respectivos itens.
- [`Peca.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Models/Peca.php): Métodos auxiliares como `temEstoqueSuficiente($qtd)` e `estoqueAbaixoDoMinimo()`.

---

### 3.6. Banco de Dados e Migrações

Localizadas em [`database/migrations/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/database/migrations). Foram criadas 10 tabelas totalmente normalizadas:

1. `usuarios`: Credenciais com hash bcrypt e enum de perfil (`administrador`, `mecanico`, `atendente`).
2. `clientes`: Dados cadastrais com índice único para `cpf_cnpj`.
3. `veiculos`: Ficha técnica com índice único para `placa`.
4. `pecas`: Almoxarifado com código, preços e controle de estoque mínimo.
5. `servicos`: Catálogo de procedimentos de mão de obra e tempo estimado.
6. `orcamentos`: Propostas comerciais com validade, status e valores totalizados.
7. `itens_orcamento`: Itens vinculados de forma polimórfica (peça física ou serviço de mão de obra).
8. `ordens_servico`: Execução da oficina com status (`aberta`, `em_diagnostico`, `aguardando_pecas`, `em_execucao`, `finalizada`, `cancelada`).
9. `itens_ordem_servico`: Peças e serviços executados na O.S.
10. `historicos_veiculo`: O prontuário automotivo com histórico de quilometragem e laudos técnicos.

---

### 3.7. Interface com o Usuário (Blade Views & Front-End)

Localizadas em [`resources/views/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views).

* **Master Layout: [`layouts/app.blade.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views/layouts/app.blade.php)**
  - Sidebar profissional com ícones FontAwesome e itens condicionais por nível de acesso (RBAC).
  - Alertas automáticos do Bootstrap (`alert-success`, `alert-danger`) para feedback visual ao usuário.
  - Inclusão do Favicon oficial automotivo da aplicação.
* **Tabela Dinâmica de Itens: [`orcamentos/criar.blade.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views/orcamentos/criar.blade.php#L60-L245)**
  - Permite adicionar e remover linhas dinamicamente via JavaScript sem recarregar a tela.
  - Ao selecionar se o item é **Peça** ou **Serviço**, o dropdown correspondente é exibido e os campos de descrição e preço são preenchidos automaticamente.
* **Prontuário Histórico: [`veiculos/historico.blade.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views/veiculos/historico.blade.php)**
  - Componente de linha do tempo (*timeline*) cronológica com todos os serviços e passagens do veículo pela oficina.

---

## 🗓️ 4. Roteiro Sugerido de Estudos (Passo a Passo)

Para absorver todo o projeto com facilidade, estude nesta ordem:

### 🔹 Passo 1: O Modelo de Dados
1. Abra as migrações em [`database/migrations/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/database/migrations) para ver como as tabelas se conectam (chaves estrangeiras).
2. Abra os modelos em [`app/Models/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Models) para entender os relacionamentos `hasMany` e `belongsTo`.

### 🔹 Passo 2: O Desacoplamento com Repositórios
1. Veja as interfaces em [`app/Repositories/Contratos/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Repositories/Contratos).
2. Veja a implementação em [`app/Repositories/Eloquent/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Repositories/Eloquent).
3. Veja como elas são unidas no [`app/Providers/RepositorioServiceProvider.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Providers/RepositorioServiceProvider.php).

### 🔹 Passo 3: A Lógica de Negócios
1. Leia o arquivo [`app/Services/OrcamentoServico.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Services/OrcamentoServico.php) e veja o cálculo dos totais e a conversão em O.S.
2. Leia o arquivo [`app/Services/OrdemServicoServico.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Services/OrdemServicoServico.php) e veja as transições de status e o controle de estoque.

### 🔹 Passo 4: O Fluxo Web
1. Abra [`routes/web.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/routes/web.php) para acompanhar os caminhos das URLs.
2. Abra [`app/Http/Middleware/VerificarPerfil.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Middleware/VerificarPerfil.php) para entender a proteção RBAC.
3. Abra os controladores em [`app/Http/Controllers/`](file:///C:/Users/pedro.miguel/Oficina-Laravel/app/Http/Controllers).

### 🔹 Passo 5: A Experiência Visual
1. Abra [`resources/views/layouts/app.blade.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views/layouts/app.blade.php) para analisar a barra lateral e navegação.
2. Abra [`resources/views/orcamentos/criar.blade.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views/orcamentos/criar.blade.php) para ver a tabela dinâmica com JavaScript.
3. Abra [`resources/views/veiculos/historico.blade.php`](file:///C:/Users/pedro.miguel/Oficina-Laravel/resources/views/veiculos/historico.blade.php) para inspecionar o prontuário histórico do carro.

---

## 🎯 5. Dicas para Explicar em uma Apresentação Técnica

Se for apresentar o projeto para professores, avaliadores ou recrutadores:

1. **Destaque a Arquitetura em Camadas**:
   > *"Em vez de colocar código SQL ou regras de cálculo dentro dos Controllers, separamos o sistema em Camada de Apresentação (Blade/Bootstrap), Camada de Controle (Controllers magros), Camada de Validação (Form Requests com sanitização), Camada de Negócio (Services) e Camada de Acesso a Dados (Repositories e Eloquent)."*

2. **Destaque a Integridade Operacional**:
   > *"O sistema possui controle transacional atômico (`DB::transaction`). Ao converter um orçamento em Ordem de Serviço, as peças são validadas contra o saldo em estoque, debitadas instantaneamente e o histórico do carro é alimentado de forma sincronizada."*

3. **Destaque a Segurança e RBAC**:
   > *"O sistema adota Controle de Acesso Baseado em Perfis (RBAC), separando as interfaces e rotas para Administrador, Mecânico e Atendente, garantindo que mecânicos não alterem orçamentos comerciais e atendentes não encerrem ordens sem laudo técnico."*
