# API Solicitações Internas

## Visão geral
Sistema de Solicitação Interna onde um usuário autenticado cria uma solicitação que passa por análise e decisão (aprovação ou rejeição). O fluxo é rastreável, com controle de acesso por papéis e auditoria de transições.

## Papéis do sistema
- `solicitante`
- `avaliador`
- `aprovador`
- `admin`

Avaliador e aprovador são papéis distintos para separar **análise** de **decisão final**.

## Tipos de solicitação
Os tipos foram definidos como **enum** (`SolicitacaoTipo`) por simplicidade, validação e padronização no domínio.

## Fluxo de estados
- `rascunho` → `enviada` → `em_analise` → `aprovada` | `rejeitada`
- `rascunho` → `cancelada`
- `enviada` → `cancelada`

Regras:
- Não pula estados
- Estados finais são imutáveis
- Apenas o solicitante pode cancelar
- Cada transição gera registro de auditoria

## Regras de negócio (resumo)
- Usuário vê apenas solicitações que criou, ou que está designado para avaliar/aprovar, ou se for admin.
- Justificativa é obrigatória.
- Aprovar/rejeitar exige comentário obrigatório.
- Rejeitada não pode ser reaberta.

## Arquitetura / decisões técnicas
- Laravel + Laravel Sail
- Autenticação via Sanctum
- Papéis/Permissões via Spatie
- Actions Pattern por caso de uso
- DTOs validados (`wendelladriel/laravel-validated-dto`)
- API Resources para saída
- Evento de domínio único `SolicitacaoDecidida`
- Job assíncrono para envio de e-mail
- Laravel Pint para padronização

## Como clonar o projeto
```bash
git clone <seu-repositorio>
cd api-gestao
```

## Como rodar o projeto
1. Copie o `.env`:
```bash
cp .env.example .env
```
2. Instale dependências:
```bash
composer install
```
3. Suba os containers:
```bash
./vendor/bin/sail up -d
```
4. Gere a key e rode as migrations:
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

Para parar:
```bash
./vendor/bin/sail down
```

## Como rodar os testes
```bash
./vendor/bin/sail artisan test
```


## E-mails (Mailtrap)
Configure no `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="API Solicitações"
```

### Como testar envio de e-mail
1. Verifique se o worker (fila) está rodando
```bash
./vendor/bin/sail up -d queue
```
2. Execute o fluxo de aprovação/rejeição.
3. Verifique o inbox do Mailtrap.

## Rotas da API
As rotas estão em `routes/api.php`.

Principais endpoints:
- `GET /api/solicitacoes`
- `GET /api/solicitacoes/{id}`
- `POST /api/solicitacoes`
- `PUT /api/solicitacoes/{id}` (apenas admin)
- `DELETE /api/solicitacoes/{id}` (apenas admin)
- `POST /api/solicitacoes/{id}/enviar`
- `POST /api/solicitacoes/{id}/analisar`
- `POST /api/solicitacoes/{id}/aprovar`
- `POST /api/solicitacoes/{id}/rejeitar`
- `POST /api/solicitacoes/{id}/cancelar`


## Como formatar o código
```bash
./vendor/bin/sail pint
```
