# mvc
mini framework feito em PHP desenvolvido no design pattern MVC.
## Requesitos
`PHP: >=7.0.0`

O `composer` é opcional, pois o sistema tem um próprio autoload, que identifica se o autoload do **composer** está instalado. 
## Documentação
### Configuração
No arquivo `config.php` localizado na raiz do projeto, é o local onde será feito algumas configurações do projeto como banco de dados.

Constante | Descrição | Exemplo
--------- | --------- | -------
`APP_BASE`| qual diretório será ignorado pelo sistema e o definirá como raiz | `localhost/mvc`, `mvc` será considerado o diretório raiz do sistema.
`DBHOST`| nome do host que servirá para conectar ao banco de dados | `define('DBHOST', 'localhost');`, se não for usar, apenas desconsidere.
`DBNAME`| nome do banco de dados | `define('DBNAME', 'teste');`, se não for usar, apenas desconsidere.
`DBUSER`| nome do usuário do banco de dados | `define('DBUSER', 'root');`, se não for usar, apenas desconsidere.
`DBPASSWORD`| senha do banco de dados | `define('DBPASSWORD', '');`, se não for usar, apenas desconsidere.
`DBCHARSET`| charset das requisições, envios e nomes de tabelas do banco de daos | `define('DBCHARSET', 'UTF8');`, **não mexa se não souber.**
***
### Rotas
As rotas são para quando uma rota na url for acessada, um **callback** ou um **método de uma classe Controller** será chamado.

Para criar rotas, deverá usar o arquivo `route.php` localizado na raiz do projeto. 
```
namespace \Lib\Http\CreateRoute;

final class CreateRoute
{
    /* Métodos públicos */
    public static function get (string $route, mixed $action [, ?string = NULL]): void
    public static function post (string $route, mixed $action [, ?string = NULL]): void
    public static function any (string $route, mixed $action [, ?string = NULL]): void
    public static function string (string $route): string
    public static function to (string $route): void
    public static function run (): void
}
```
Para criar uma rota, use a sintaxe:

`Route::get(rota, callback ou classe, método da classe);`

**Observação:** Se usar uma classe, o último parâmetro deverá ser utilizado.
#### Métodos das rotas
Método | Descrição | Exemplo
------ | --------- | -------
`Route::get` | Toda rota de requisição `GET` que for igual a rota descrita o callback ou a classe será chamada | `URL: localhost/projeto`, `rota: Route::get('/projeto','meuController', 'index');`
`Route::post` | Toda rota de requisição `POST` que for igual a rota descrita o callback ou a classe será chamada | `URL: localhost/projeto/create`, `rota: Route::post('/projeto/create','meuController', 'create');`
`Route::any` | Toda rota de requisição **qualquer** que for igual a rota descrita o callback ou a classe será chamada | `URL: localhost/projeto/show`, `rota: Route::amy('/projeto/show','meuController', 'show');`
`Route::string` | Retorna o domínio junto com a rota passada | `Route::string('/projeto'); retorno: http://localhost/projeto`
`Route::to` | Redireciona para algum lugar do site | `Route::to('/projeto'); redireciona para: http://localhost/projeto`
`Route::run` | Cadastra todas as rotas | É usado pelo sistema, não use a menos que saiba o que está fazendo.
#### Callbacks em rotas
Os callbacks são usados quando não precisa criar uma novo **Controller**

Para criar um callback, use a sintaxe:

`Route::get(rota, callback);`
##### Exemplos
```
Route::get('/', function(){
    echo 'Olá, Mundo!';
});

Route::post('/', function(){
    echo 'Olá, Mundo!';
});
```
#### Parâmetros por URL
Rotas com parâmetros por URL são utilizadas, geralmente, como uma rota genérica e os valores usados são redirecionados para o **método** ou o **callback**

Para criar uma rota com parâmetros por URL, a rota deve ter, o parâmetro entre chaves `{id}`. Os valores os parâmetros serão passados como parâmetros dos **métodos** e dos **callbacks**. Sintaxe:

`Route::get(rota, callback ou classe, método da classe);`

##### Exemplos
```
Route::get('/{id}', function($id){
    echo 'O id foi ' . $id;
});

Route::post('/create/{id}', 'MeuController', 'index');

/* No método index da classe MeuController */
public function index($id)
{
    echo 'O id foi ' . $id;
}
```
#### Exceptions das Rotas
Qualquer erro nas rotas será lançado uma `\Lib\Execeptions\RouteException` ou uma `\Exception`
***
### Request
A classe `Request` é usada para acessar as variável `GET`, `POST` e `SESSION`, para chamar a `Request` pode chamar por namespace: `\Lib\Http\Request` ou por parâmetro de **callback** ou **método**, mas apenas se a rota foi criada direcionando para essas funções.
```
<?php

class Request
{
    /* Métodos publicos */
    public function __construct([string $method = null])
    public function get(): ?object
    public function post(): ?object
    public function has(): ?object
    public function session(): ?object
    public function redirect(string $url): void
}
```
#### Métodos da classe Request
Método | Descrição | Exemplo
------ | --------- | -------
`get` | Retorna um objeto com todos os valores da `$_GET` | `$valoresGET = $request->get();`
`post` | Retorna um objeto com todos os valores da `$_POST` | `$valoresPOST = $request->post();`
`has` | Serve para verificar se o valor definido existe, é utilizado antes da `post` ou da `get`, se não existir retona `NULL` | `$exists = $request->has('val')->post()->val;`
`session` | retorna uma classe `\Lib\Http\Session`, onde retorna vários métodos da session | `$session = $request->session();`
`redirect` | Redireciona para qualquer URL | `$request->redirect('https://google.com');`
#### Request por Namespace
Em qualquer classe você pode usar a `Request` usando essa sintaxe:

```
<?php

namespace \Controllers;

use \Lib\Http\Request;

class ClasseQualquer
{
    index()
    {
        $request = new Request();
    }
}
```
#### Request por Rotas
Para usar `Request` em uma rota, primeiro deve-se cadastrar uma rota, depois, nos parâmetros do **callback** ou no **método** deve-se instanciar a classe `Request`
##### Exemplos
```
Route::any('/', function(Request $request) {
    //código
});

Route::post('/projeto/{nome}', function($nome, Request $request) {
    $requestGet = $request->get();
});

Route::get('/projeto/{nome}', 'MeuController', 'index');

/* Na classe MeuController */
<?php

namespace \Controllers;

use \Lib\Http\Request;

class MeuController
{
    public function index (Request $request, $nome)
    {
        //código
    }
}
```
#### Exceptions da Request
Se dizer um método que não existe, retornará `\Lib\Exceptions\GeralException` ou, em outros erros, uma `\Exception`
***
### Session
Você pode usar as funções e variável de sessão por essa classe ou pela `Request`.
```
final class Session
{
    /* Métodos públicos */
    public function __construct()
    public function start(?array $options = null): bool
    public function delete(string $id): bool
    public function destroy(): bool
    public function abort(): void
    public function encode(): string
    public function decode(string $data): bool
    public function id(string $id = null): string
    public function regenerateID(bool $delete_old_session = false): bool
    public function reset(): void
    public function name(?string $name = null): string
    public function status(): int
    public function close(): void
    public function save(): void
    public function all(): object
    public function allAssoc(): array
    public function __destruct()
}
```
#### Métodos da Session
Método | Descrição
------ | ---------
`__construct()` | Inicia a sessão quando instanciado.
`start` | Inicia uma nova sessão ou resume uma sessão existente.
`delete` | Deleta uma variável de sessão.
`destroy` | Destrói todos os dados registrados em uma sessão.
`abort` | Descarta as alterações no array da sessão e encerra a sessão.
`encode` | Codifica os dados atuais da sessão como uma sessão codificada em formato string.
`decode` | Decodifica dados de sessão de uma sessão codificada em formato string.
`id` | Obtém ou define o id de sessão atual.
`regenerateID` | Atualiza o id da sessão atual com um novo id gerado.
`reset` | Reinicializa um array de sessão com os valores originais.
`name` | Obtém e/ou define o nome da sessão atual.
`status` | Retorna o status atual da sessão.
`close` | Guarda os dados de sessão e fecha a sessão.
`save` | Define os dados e os salva.
`all` | Retorna todos os dados da `$_SESSION`.
`allAssoc` | Retorna todos os dados da `$_SESSION` em um array associativo.
`__destruct` | Salva e fecha a sessão.
#### Salvar valores na Session
Para salvar valores na classe `Session` você deve puxar um atributo com o nome que deseja e atribuir um valor, a classe `Session` salvará os valores automáticamente, ou você pode salvar usando: `$session->save();`
#### Exemplo
```
<?php

namespace \Controllers;

use \Lib\Http\Request;
use \Lib\Http\Session;

class MeuController
{
    public function __construct()
    {
        // usando a Session com a Request
        $request = new Request();
        $request->session()->valor1 = 'valor1';
    }
    
    public function index()
    {
        // usando com a classe Session
        $session = new Session();
        $session->valor1 = 'valor1';
        $valores = $session->all();
    }
}
```
#### Exceptions da Session
Qualquer erro resultará em uma `\Lib\Exceptions\GeralException` ou `\Exception`
***
