<?php

namespace Lib\Controllers;

use Lib\Support\Arr;
use Lib\Exceptions\ControllerException;

abstract class Controller
{
    /**
     * Armazena o caminho da view.
     * 
     * @var string
     */
    protected $viewPath = 'Views';

    /**
     * Armazena o caminho do templete.
     * 
     * @var string
     */
    protected $templetePath = 'templetes';

    /**
     * Armazena o conteudo da view.
     * 
     * @var string
     */
    private $view;

    /**
     * Armazena os parâmetros da view.
     * 
     * @var array
     */
    private $viewParam;

    /**
     * Armazena o conteudo do templete.
     * 
     * @var string
     */
    private $templete;

    /**
     * Armazena o apelido do templete.
     * 
     * @var string
     */
    private $aliasTemplete;

    /**
     * Armazena o apelido e o nome do arquivo do javascript.
     * 
     * @var array
     */
    private $jsAssets;

    /**
     * Armazena o apelido e o nome do arquivo do CSS.
     * 
     * @var array
     */
    private $cssAssets;

    /**
     * Armazena o apelido e o nome do arquivo dos componentes.
     * 
     * @var array
     */
    private $components;

    /**
     * Armazena o caminho dos assets.
     * 
     * @var string
     */
    protected $assetsPath = 'assets';

    /**
     * Armazena o caminho do CSS.
     * 
     * @var string
     */
    protected $cssPath = 'css';

    /**
     * Armazena o caminho do javascript.
     * 
     * @var string
     */
    protected $jsPath = 'js';

    /**
     * Armazena o caminho dos componentes.
     * 
     * @var string
     */
    protected $componentPath = 'components';

    /**
     * Armazena o valor se o método run já foi iniciado.
     * 
     * @var bool
     */
    private $init = false;

    /**
     * Limpa os nomes da pastas.
     * 
     * Pega o conteúdo da view.
     * 
     * Define os parâmetros da view.
     * 
     * @param  string  $view
     * @param  array|null $parameters
     * @return this
     */
    public function render(string $view, ?array ...$parameters): object
    {
        $this->cleanFolders();
        $this->view = $this->getContents([$this->viewPath, $view]);
        $this->viewParam = $parameters;
        return $this;
    }

    /**
     * Renderiza a view.
     * 
     * @return void
     * 
     * @throws \Lib\Exceptions\ControllerException
     */
    private function runRender(): void
    {
        if (empty($this->view) && is_null($this->viewParam)) {
            return;
        }

        foreach ($this->viewParam as $param) {
            if (!is_null($param) && Arr::isAssoc($param)) {
                extract($param);
            } elseif (!is_null($param)) {
                throw new ControllerException('parâmetros passados não são array associativo');
            }
        }

        $view = \preg_replace('/(?(?=(@@.*[\w]+.*@@))@@|\0)/', '<?php ', $this->view);
        $middleView = \preg_replace('/(?(?=(@.*[\w]+.*@))@|\0)/', '<?= ', $view);   
        $semiView = \preg_grep('/(<\?php|<\?=).*[\w]+.*@@?/', compact('middleView'));
       
        if (count($semiView) > 0) {

            $finalView = array_map(function($value){
                return \preg_replace('/@@?/', ' ?>', $value);
            }, $semiView);

            $response = $finalView['middleView'];
        } else {
            $response = $middleView;
        }

        try {
            eval('?>' . $response);
        } catch (\Exception $e) {
            throw new ControllerException('ocorreu um erro: ' . $e->getMessage());
        }
    }

    /**
     * Procura pelo $subject usando o pattern de expressão regular e substitui pelo $replacement.
     * 
     * @param  string  $itemPattern
     * @param  string  $replacement
     * @param  string  $subject
     * @return string
     */
    private function replacer(string $itemPattern, string $replacement, string $subject): string
    {
        return \preg_replace('/(?(?=(@@\s?' . $itemPattern . '\s?@@))@@\s?' . $itemPattern . '\s?@@|\0)/', $replacement, $subject);
    }

    /**
     * Define o apelido e o nome do arquivo do templete.
     * 
     * @param  string  $alias
     * @param  string  $templete
     * @return this
     */
    public function templete(string $alias, string $templete): object
    {
        $this->templete = $templete;
        $this->aliasTemplete = $alias;
        return $this;
    }

    /**
     * Renderiza o templete.
     * 
     * @return void
     * 
     * @throws \Lib\Exceptions\ControllerException
     */
    private function runTemplete(): void
    {
        if (empty($this->templete) || empty($this->aliasTemplete)){
            return;
        } elseif (empty($this->view)) {
            throw new ControllerException('a view não pode ficar vazia');
        }

        $templete = $this->getContents([$this->viewPath, $this->templetePath, $this->templete]);
        $this->view = $this->replacer($this->aliasTemplete, $this->view, $templete);
    }

    /**
     * Define os assets.
     * 
     * @param  array  $css
     * @param  array  $js
     * @return this
     */
    public function assets(array $css, array $js = []): object
    {
        $this->jsAssets = $js;
        $this->cssAssets = $css;
        return $this;
    }

    /**
     * Renderiza os assets.
     * 
     * @return void
     * 
     * @throws \Lib\Exceptions\ControllerException
     */
    private function runAssets(): void
    {
        if (empty($this->jsAssets) && empty($this->cssAssets)) {
            return;
        } elseif (empty($this->view)) {
            throw new ControllerException('a view não pode ficar vazia');
        }

        $assets = [$this->cssAssets, $this->jsAssets];

        foreach ($assets as $asset) {
            if (!empty($asset) && !Arr::isAssoc($asset)) {
                throw new ControllerException('parâmetros passados não são array associativo');
            }
        }

        foreach ($assets as $keyAssets => $asset) {

            foreach ($asset as $key => $value) {

                $content = '';
                $link = $keyAssets === 0 ? $this->cssPath: $this->jsPath;

                if (empty($link) || is_null($link)) {
                    $path = [APPBASE, $this->viewPath, $this->assetsPath, $value];
                } else {
                    $path = [APPBASE, $this->viewPath, $this->assetsPath, $link, $value];
                }

                 if (isset($_SERVER['REQUEST_SCHEME'])) {
                    $path = 'https://' . implode('/', $path); 
                } else {
                    $path = 'http://' . implode('/', $path); 
                }
            
                $path = str_replace('\\', '/', $path);

                if ($keyAssets === 0) {
                    $content .= '<link rel="stylesheet" href="' . $path . '.css">';
                } else {
                    $content .= '<script src="' . $path . '.js"></script>';
                }

                $this->view = $this->replacer($key, $content, $this->view);
            }
        }
    }

    /**
     * Define os componentes
     * 
     * @param  array  $components
     * @return this
     */
    public function components(array $components): object
    {
        $this->components = $components;
        return $this;
    }

    /**
     * Renderiza os componentes.
     * 
     * @return void
     * 
     * @throws \Lib\Exceptions\ControllerException
     */
    private function runComponents(): void
    {
        if (empty($this->components)) {
            return;
        } elseif (empty($this->view)) {
            throw new ControllerException('a view não pode ficar vazia');
        }

        if (!Arr::isAssoc($this->components)) {
            throw new ControllerException('parâmetros passados não são array associativo');
        }

        foreach ($this->components as $key => $component) {
            $content = $this->getContents([$this->viewPath, $this->componentPath, $component]);
            $this->view = $this->replacer($key, $content, $this->view);
        }
    }

    /**
     * Pega o conteúdo do arquivo.
     * 
     * @param  array  $path
     * @return string
     * 
     * @throws \Lib\Exceptions\ControllerException
     */
    private function getContents(array $path): string
    {
        $itens = implode('/', $path);

        try {
            return \file_get_contents(ROOT . '/' . $itens . '.php');
        } catch (\Exception $e) {
            throw new ControllerException('ocorreu um erro: ' . $e->getMessage());
        }
    }

    /**
     * Limpa os nomes das pastas tirando a / do começo e fim.
     * 
     * @return void
     */
    private function cleanFolders(): void
    {
        $queue = [
            &$this->viewPath,
            &$this->templetePath,
            &$this->componentPath,
            &$this->assetsPath,
            &$this->cssPath,
            &$this->jsPath,
        ];

        foreach ($queue as $key => $value) {
            $value = preg_replace('/^\//', '', $value);
            $value = preg_replace('/\/$/', '', $value);
            $queue[$key] = $value;
        }
    }

    /**
     * Inicia as renderizações.
     * 
     * @return void
     */
    public function run(): void
    {
        if (!$this->init) {
            $this->init = true;
        } else {
            return;
        }

        $this->runTemplete();
        $this->runAssets();
        $this->runComponents();
        $this->runRender();
    }

    /**
     * Inicia as renderizações.
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->run();
    }
}
