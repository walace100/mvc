# mvc
mini framework feito em PHP desenvolvido no design pattern MVC, inspirado no Framework **Laravel**
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
### Constates
As contantes definidas são:
* `ROOT`: o valor dela é o caminho até a raiz do projeto.
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
    public function start([?array $options = null]): bool
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
### Models
A `Model` serve para interagir com o banco de dados. 

Os arquivos da Model ficam em `/Models`.

sua sintaxe é:
```
<?php

namespace Models;

use \Lib\Models\Model;

class MinhaModel extends Model
{
    // código
}
```
#### Classe Model
```
namespace \lib\Models;

abstract class Model extends DBConnection
{
    /* atributos protegidos */
    protected $connection = false;
    protected $fetch_style = PDO::FETCH_CLASS;
    
    /* métodos e atributos públicos */
    public $table = null;
    public function __construct()
    public function querySt(string $query[, mixed $arguments = []]): PDOStatement
    public function insert(array $attributes, array $values[, ?string $table = null]): void
    public function find(string $attribute, string $value[, ?array $fields = null[, ?string $table = null]]): array
    public function all(?array $attributes = null[, ?string $table = null[, int $limit = 1000000]]): array
    public function update(array $setValueAssoc, array $compareValueAssoc[, ?string $table = null[, int $limit = 1]]): void
    public function delete(string $attribute, $value[, ?string $table = null[, int $limit = 1]]): void
}
```
#### métodos e atributos da Model
Métodos e atributos | Descrição | Parâmetros | Exemplo
------------------- | --------- | ---------- | -------
`protected $connection` | Armazena a conexão com o banco de dados. Para usar ele, deve-se chamar o `parent::__construct()` pelo menos uma vez | Por padrão o valor é `FALSE` | `$this->connection->prepare($query);`
`protected $fetch_style` | Controla como a próxima linha será retornada ao chamador. | Por padrão o valor é `PDO::FETCH_CLASS` |`$this->fetch_style = PDO::FETCH_CLASS;`. (os retornos dos métodos serão como objeto.)
`public $table` | Define o nome da tabela a ser modificada. | Por padrão o valor é `NULL` |`$this->table = 'teste';`.
`public function __construct` | Inicia a conexão do banco de dados, se ocorrer sobreposição é recomendado que chame o `parent::__construct()` | Nenhum parâmetro | ``` public function __construct(){  parent::__construct(); } ```
`public function querySt` | Executa uma query e retorna um statement. | O **primeiro parâmetro** é a query, **(Opcional)** o **segundo** são os valores da query para serem blindados | `$this->querySt("SELECT * FROM teste; WHERE id = ?", [1]);`
`public function insert` | Insere valores no banco de dados. | O **primeiro parâmetro** é uma **Array** com os campos que vão ser inseridos, o **segundo** é um **Array** que contem os valores a serem inseridos, **(Opcional)** o **terceiro** define o nome da tabela a ser modificada. | `$this->insert(['campo1', 'campo2'], ['valor1', 'valor2']);`
`public function find` | Encontra um registro no banco de dados. | O **primeiro parâmetro** é um **String** com o nome do campo que vai ser comparado, o **segundo** é o valor do campo que vai ser comparado, **(Opcional)** o **terceiro**, é um **Array** com os campos que serão retornados, **(Opcional)** o **quarto**, é o nome da tabela | `$this->find('id', '1', '*', 'teste');`
`public function all` | Encontra todos os registros no banco de dados. | **(Todos os campos são opcionais)** O **primeiro parâmetro** é um **Array** com o nome dos campos que vão ser retornados, o **segundo** é o nome da tabela, o **terceiro** é um **Integer** com o limite de registros que vão ser retornados | `$this->all('id', 'teste', 10);`
`public function update` | Atualiza um registro do banco de dados. | O **Primeiro** é um **Array Associativo** com os valores que vão ser atualizados, o **segundo** é os valores que vão ser comparados,**(Opcional)** o **terceiro** é o nome da tabela, **(Opcional)** o **quarto** é o limite da atualização | `$this->update(['nome' => 'teste'], ['id' => 1]);`
`public function delete` | Deleta um registro do banco de dados. | O **Primeiro** é o nome do atributo a ser comparado, o **segundo** é o valor a ser deletado _(pode ser string ou array)_, **(Opcional)** o **Terceiro** é o nome da tabela, **(Opcional)** o **quarto** é o limite de deleção | `$this->update(['nome' => 'teste'], ['id' => 1]);`
#### Exceptions da Model
Qualquer erro retornará uma `\Lib\Exceptions\ModelException` ou uma `\Lib\Exceptions\GeralExceptions` em alguns casos uma `\Exception`.
***
### Controller
Gerencia os valores da `Model` e os mostra nas `Views`. Recomendo que leia as `Views`
#### Sintaxe:
```
<?php

namespace Controllers;

use \Lib\Controllers\Controller;

class MeuController extends Controller
{
    //código
}
```
#### Classe Controller
```
abstract class Controller
{
    /* Atributos protegidos */
    protected $viewPath = 'Views';
    protected $templetePath = 'templetes';
    protected $assetsPath = 'assets';
    protected $componentPath = 'components';
    
    /* Métodos públicos */
    public function render(string $view, ?array ...$parameters): object
    public function templete(string $alias, string $templete): object
    public function assets(array $css[, array $js = []]): object
    public function components(array $components): object
    public function run(): void
}
```
#### Métodos e Atributos do Controller (se for utilizar, apenas o método render é necessário, os outros são opcionais)
Métodos e atributos | Descrição | Parâmetros | Exemplo
------------------- | --------- | ---------- | -------
`protected $viewPath` | Define o nome da Pasta das **views** | O valor padrão é `'Views'` | `$this->viewPath = 'View';`
`protected $templetePath` | Define o nome da Pasta dos **templetes** | O valor padrão é `'templetes'` | `$this->viewPath = 'templete';`
`protected $assetsPath` | Define o nome da Pasta dos **assets** | O valor padrão é `'assets'` | `$this->viewPath = 'asset';`
`protected $componentPath` | Define o nome da Pasta dos **componentes** | O valor padrão é `'components'` | `$this->viewPath = 'component';`
`public function render` | Define o nome da **view** | O **primeiro** parâmetro é o nome do arquivo da view, o **segundo** são os parâmetros para a **view** | `$this->render('minhaview', ['var1' => 'valor']);`
`public function templete` | Define o **templete** que vai aparecer na view | O **primeiro** parâmetro é o apelido do arquivo do templete na view, o **segundo** é o nome do arquivo do templete | `$this->templete('apelidoDoTemplete', 'ArquivoTemplete');`
`public function assets` | Define os **assets** que vai aparecer na view **ou no templete** | O **primeiro** parâmetro é um **Array Associativo** com `'apelido' => 'nomedoArquivoCSS'` , o **segundo** é um **Array Associativo** com `'apelido' => 'nomedoArquivoJS'` | `$this->assets(['cssApelido' => 'style'], ['jsApelido' => 'script']);`
`public function components` | Define os **components** que vai aparecer na view **ou no templete** | O parâmetro é um **Array Associativo** com `'apelido' => 'nomedoComponente'` | `$this->components(['apelidoComponente1' => 'componente1', 'apelidoComponente2' => 'componente2']);`
`public function run` | Inicia os métodos a funcionar, é recomendado que não use ele, é para uso do sistema | nenhum | `$this->run();`
#### Exceptions no Controller
Qualquer erro resultará em uma `\Lib\Exceptions\ControllerException` ou `\Lib\Exceptions\GeralException` em alguns casos `\Exception`
### View
As view são onde serão mostrados os valores vindos do **Controller**.
#### Código PHP.
Os arquivos deverão vir como `.php`, pode-se usar as tags `<?php`, `<?=`, `?>`, mas o framework tem um sistema de tags próprio, usando `@`.
##### <?php e @@
Para usar os `<?php` troque no lugar dos `<?php` e `?>` por `@@`, e insira o código PHP entre as `@@ @@`.
###### Exemplo
`@@ var_dump('teste'); @@`
##### <?= e @
Para usar os `<?=` troque no lugar dos `<?=` e `?>` por `@`, e insira o código PHP entre as `@ @`.
###### Exemplo
`@ 'esse foi um echo'; @`
##### Onde usar
Você pode usar em qualquer arquivo: `view`, `assets`, `components` e `templetes`. Para mostrar tudo na tela primeiro o **Controller** deve dizer quais arquivos e apelidos são quem.
#### Fotos
Você pode criar um arquivo fotos, na pasta `/Views`
#### Variáveis nas Views
Você pode passar variáveis dos **Controllers** para as **Views**, pelo método `render` dos **Controllers**
##### Exemplos
```
<?php

namespace Controllers;

use Lib\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
       $val1 = 'valor1';
       $this->render('minhaView', compact('view1'));
    }
}

// na view: minhaView
@@ var_dump($val1); @@

// isso irá funcionar.
```
#### Exceptions nas Views
Qualquer erro resultará em uma `\Lib\Exceptions\GeralException` ou `\Exception`
***
### Exception
Você pode criar suas próprias Exceptions. Para criar você deve estar na pasta `/Exceptions`, e usar essa sintaxe:
```
<?php

namespace Lib\Exceptions;

use Lib\Exceptions\GeralException;

class MinhaException extends GeralException
{
    public $view = 'controllerException';
    public $controller = \Controller\ExceptionController::class;
    public function __construct(string $view, array $params) // isso vai ser explicado depois.
    {
    }
}
```
#### Classe GeralException
```
namespace Lib\Exceptions;

use Exception;

class GeralException extends Exception
{
    /* Atributos Públicos */
    public $view = 'geralException';
    public $controller = \Lib\Exceptions\Controllers\ExceptionController::class;
}
```
#### Atributos da GeralException
Atributos | Descrição
--------- | ---------
`public $view` | Define o nome da View que será chamado pelo Controller
`public $controller` | Namespace do Controller que a Exception irá chamar

**Observação:** Esses Atributos deverão ser utilizados. se não for sobrescrevido a **view** `geralException` será chamada .
#### Criando a Exception
Após você ter criado a sua `Exception` e o Controller, você deve cadastrar sua `view` no arquivo `ConfigException.php`
##### ConfigException
Quando você abrir o arquivo você encontrará isso:
```
<?php

namespace Exceptions;

final class ConfigException
{
    /**
     * Aqui você registra suas Exceptions.
     * 
     * Se a exception for criada, mas não registrada
     * retornará uma exception padrão: \Lib\Exceptions\GeralException
     * 
     * @var array
     */
    public const EXCEPTIONS = [
        \Lib\Exceptions\GeralException::class,
        \Lib\Exceptions\RouteException::class,
        \Lib\Exceptions\ControllerException::class,
        \Lib\Exceptions\ModelController::class,
    ];
}
```
Para cadastrar sua view, apenas insira o `namespace` dela na **constante** `EXCEPTIONS`. Após fazer isso, sua `Exception` será chamada quando lançada.
##### Parâmetros passados pela `GeralException`
Quando sua Exception for criada, e lançada em algum momento será passado pelo seu `__construct` dois parâmetros:
1. O primeiro é o nome da view que você definiu e terá que chamar.
1. O segundo é um **Array** com os métodos extendidos da `\Exception`
###### Métodos da `\Exception`
Quando sua `Exception` for chamada, será passado dois valores, um deles contém valores de todos os métodos da `Exception`, esses valores são:
Valor | Descrição
----- | ---------
`getMessage` | Obtém a mensagem da exceção
`getCode` | Obtém o código da exceção
`getFile` | Obtém o arquivo no qual a exceção ocorreu
`getLine` | Obtém a linha na qual a exceção ocorreu
`getTrace` | Obtém a stack trace
`getPrevious` | Retorna Exception anterior
`getTraceAsString` | Obtém a stack trace como uma string
###### Exemplo
Criado a Exception em `\Exceptions`:
```
<?php

namespace Lib\Exceptions;

use Lib\Exceptions\GeralException;

class MinhaException extends GeralException
{
    public $view = 'MinhaView';
    public $controller = \Controllers\MinhaExceptionController::class;
}
```
Criado o Controller em `\Controllers`:
```
<?php

namespace Lib\Exceptions\Controllers;

use Lib\Controllers\Controller;

class MinhaExceptionController extends Controller
{
    private $view;
    private $param;
    public function __construct(string $view, array $param)
    {
        $this->view = $view;
        $this->param = $param;
        $this->init();
    }
    private function init()
    {
        $this->render($this->view, $this->param)
    }
}
```
Cadastrando a `Exception`:
```
<?php

namespace Exceptions;

final class ConfigException
{
    public const EXCEPTIONS = [
        \Lib\Exceptions\GeralException::class,
        \Lib\Exceptions\RouteException::class,
        \Lib\Exceptions\ControllerException::class,
        \Lib\Exceptions\ModelController::class,
        \Exceptions\MinhaException::class,
    ];
}
```
Na view `MinhaView`:
```
<h1>MinhaException</h1>
mensagem: @@ var_dump($getMessage); @@
```
***
**535 linhas feitas a toa :slightly_smiling_face:**
### Licença
A licença é em base a [MIT LICENSE](https://github.com/walace100/mvc/blob/master/LICENSE)
