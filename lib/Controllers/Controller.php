<?php

namespace Lib\Controllers;

use Lib\Support\Arr;

abstract class Controller
{
    protected $viewPath = '/Views/';

    protected $templetePath = '/templetes/';

    private $view;

    private $viewParam;

    private $templete;

    private $aliasTemplete;

    private $jsAssets;

    private $cssAssets;

    private $components;

    protected $assetsPath = 'assets/';

    protected $componentPath = 'components/';

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
                return;
            }
        }

        $view = \preg_replace('/(?(?=(@@.*[\w]+.*@@))@@|\0)/', '<?php ', $this->view);
        $middleView = \preg_replace('/(?(?=(@.*[\w]+.*@))@|\0)/', '<?= ', $view);   
        $semiView = \preg_grep('/(<\?php|<\?=).*[\w]+.*@@?/', compact('middleView'));
       
        if (count($semiView) > 0) {

            $finalView = array_map(function($value){
                return \preg_replace('/@@?/', ' ?>', $value);
            }, $semiView);

            eval('?>' . $finalView['middleView']);
        } else {

            eval('?>' . $middleView);
        }
    }

    private function replacer($itemPattern, $replacement, $subject): string
    {
        return \preg_replace('/(?(?=(@@\s?' . $itemPattern . '\s?@@))@@\s?' . $itemPattern . '\s?@@|\0)/', $replacement, $subject);
    }

    public function templete(string $templete, string $alias): object
    {
        $this->templete = $templete;
        $this->aliasTemplete = $alias;
        return $this;
    }

    private function runTemplete(): void
    {
        if (empty($this->templete) || empty($this->aliasTemplete))
            return;

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
        }

        $assets = [$this->cssAssets, $this->jsAssets];

        foreach ($assets as $keyAssets => $asset) {
            $content = '';

            foreach ($asset as $key => $value) {

                $path = $_SERVER['REQUEST_SCHEME'] . '://' . APPBASE . $this->viewPath . $this->assetsPath . ( $keyAssets === 0 ? 'css/': 'js/') . $value;
                $path = str_replace('\\', '/', $path);

                if (\is_string($key)) {
                    $name = $key;
                } else {
                    $name = $value;
                }
    
                if ($keyAssets === 0) {
                    $content .= '<link rel="stylesheet" href="' . $path . '.css">';
                } else {
                    $content .= '<script src="' . $path . '.js"></script>';
                }

                $this->view = $this->replacer($name, $content, $this->view);
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
        }

        foreach ($this->components as $key => $component) {

            if (\is_string($key)) {
                $name = $key;
            } else {
                $name = $component;
            }

            $content = $this->getContents([$this->viewPath, $this->componentPath, $component]);
            $this->view = $this->replacer($name, $content, $this->view);
        }
    }

    private function getContents(array $path): string
    {
        $itens = implode('/', $path);
        return \file_get_contents(ROOT . '/' . $itens . '.php');
    }

    private function cleanFolders()
    {
        $queue = [&$this->viewPath, &$this->templetePath, &$this->componentPath, &$this->assetsPath];

        foreach ($queue as $value) {
            $value = preg_replace('/^\//', '', $value);
            $value = preg_replace('/\/^/', '', $value);
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
