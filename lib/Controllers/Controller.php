<?php

namespace Lib\Controllers;

use Lib\Support\Arr;
use Lib\Exceptions\ControllerException;

abstract class Controller
{
    protected $viewPath = 'Views';

    protected $templetePath = 'templetes';

    private $view;

    private $viewParam;

    private $templete;

    private $aliasTemplete;

    private $jsAssets;

    private $cssAssets;

    private $components;

    protected $assetsPath = 'assets';

    protected $cssPath = 'css';

    protected $jsPath = 'js';

    protected $componentPath = 'components';

    private $init = false;

    public function render(string $view, ?array ...$parameters): object
    {
        $this->cleanFolders();
        $this->view = $this->getContents([$this->viewPath, $view]);
        $this->viewParam = $parameters;
        return $this;
    }

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

    private function replacer($itemPattern, $replacement, $subject): string
    {
        return \preg_replace('/(?(?=(@@\s?' . $itemPattern . '\s?@@))@@\s?' . $itemPattern . '\s?@@|\0)/', $replacement, $subject);
    }

    public function templete(string $alias, string $templete): object
    {
        $this->templete = $templete;
        $this->aliasTemplete = $alias;
        return $this;
    }

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

    public function assets(array $css, array $js = []): object
    {
        $this->jsAssets = $js;
        $this->cssAssets = $css;
        return $this;
    }

    private function runAssets(): void
    {
        if (empty($this->jsAssets) && empty($this->cssAssets)) {
            return;
        } elseif (empty($this->view)) {
            throw new ControllerException('a view não pode ficar vazia');
        }

        $assets = [$this->cssAssets, $this->jsAssets];

        if (!Arr::every($assets, 'Lib\Support\Arr::isAssoc')) {
            throw new ControllerException('parâmetros passados não são array associativo');
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

                $path = $_SERVER['REQUEST_SCHEME'] . '://' . implode('/', $path); 
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

    public function components(array $components): object
    {
        $this->components = $components;
        return $this;
    }

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

    private function getContents(array $path): string
    {
        $itens = implode('/', $path);

        try {
            return \file_get_contents(ROOT . '/' . $itens . '.php');
        } catch (\Exception $e) {
            throw new ControllerException('ocorreu um erro: ' . $e->getMessage());
        }
    }

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

    public function __destruct()
    {
        $this->run();
    }
}
