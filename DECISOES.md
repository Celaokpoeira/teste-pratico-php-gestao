# Documentação de Decisões Técnicas

## 1. Modelagem do Banco de Dados



Para atender ao requisito de que "um produto pode estar vinculado a um ou mais fornecedores" e manter a base de dados em conformidade com as regras de normalização (evitando redundância e anomalias de atualização), optei por uma modelagem relacional clássica de **Muitos-para-Muitos (N:M)**.

A estrutura é composta por 3 tabelas:
* **`fornecedores`**: Armazena os dados cadastrais (id, nome, cnpj, email, telefone, status).
* **`produtos`**: Armazena os detalhes dos produtos (id, nome, descricao, codigo_interno, status).
* **`produto_fornecedor` (Tabela Pivô/Associativa)**: Contém apenas as chaves estrangeiras (`produto_id` e `fornecedor_id`). 

**Decisão de Integridade:** Utilizei a restrição `ON DELETE CASCADE` nas chaves estrangeiras da tabela pivô. Dessa forma, se um produto ou fornecedor for excluído do sistema, seus vínculos são removidos automaticamente, evitando registros órfãos e mantendo a integridade referencial do banco.

## 2. Arquitetura e Estrutura do Projeto

Mesmo com a restrição do uso de frameworks, decidi não adotar o padrão procedural de "código espaguete" (misturar SQL, regra de negócio e HTML no mesmo arquivo). 

Optei por implementar uma arquitetura inspirada no **MVC (Model-View-Controller)** e no padrão **DAO (Data Access Object)**:
* **Config (`/config`)**: Centraliza a conexão com o banco de dados utilizando a extensão **PDO (PHP Data Objects)**. O uso do PDO com *Prepared Statements* foi a escolha técnica para garantir proteção nativa contra SQL Injection.
* **Models (`/models`)**: Classes responsáveis unicamente pela interação com o banco de dados e execução das queries (CRUD e JOINs).
* **Controllers (`/controllers`)**: Intermediários que recebem as requisições HTTP (sejam submits de formulário ou chamadas AJAX), validam os dados, acionam os Models e retornam a resposta adequada (ex: JSON para requisições assíncronas).
* **Views (`/views`)**: Arquivos estritamente focados na interface com o usuário (HTML/CSS), mantendo a camada de apresentação isolada da lógica de negócios.
* **Assets (`/assets`)**: Isolamento de arquivos estáticos (CSS e JS), onde o jQuery manipula o DOM e gerencia a comunicação assíncrona.

Essa separação de responsabilidades garante um código escalável, de fácil manutenção e legível para o time.

## 3. O que eu melhoraria com mais tempo

Dado o escopo de tempo de um teste prático, foquei em entregar os requisitos obrigatórios com máxima qualidade estrutural. Contudo, em um cenário real de produção, eu implementaria as seguintes melhorias:

1.  **Roteamento Avançado (Front Controller):** Em vez de acessar os arquivos `.php` diretamente na URL, criaria um arquivo `index.php` único como ponto de entrada para despachar rotas amigáveis (ex: `/produtos/listar`).
2.  **Segurança Adicional:** Implementação de tokens CSRF (Cross-Site Request Forgery) nos formulários e nas requisições AJAX para evitar ataques de falsificação de requisição.
3.  **Validação Avançada:** Inserir um algoritmo no backend e frontend para validar se a string do CNPJ corresponde a um CNPJ real da Receita Federal (cálculo de dígitos verificadores).
4.  **Paginação de Dados:** Implementar paginação nas listagens de SQL (`LIMIT` e `OFFSET`) para garantir a performance da tela caso o banco cresça para milhares de registros.
5.  **Feedback Visual Aprimorado:** Utilizar bibliotecas como SweetAlert2 ou Toastr para notificações mais fluidas, e um framework CSS (Tailwind ou Bootstrap) para acelerar a construção de uma interface mais moderna e responsiva.