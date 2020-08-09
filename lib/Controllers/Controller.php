<?php

namespace Lib\Controllers;

abstract class Controller
{
    private $viewPath;

    private $templetePath;

    private $levels;

    private $root;

    private $view;

    private $viewParam;

    private $templete;

    private $contentNameTemplete;

    private $jsAssets;

    private $cssAssets;

    private $components;

    public function __construct()
    {
        $this->viewPath = '/Views/';
        $this->templetePath = '/templetes/';
        $this->componentPath = 'components/';
        $this->assetsPath = 'assets/';
        $this->levels = 2;
        $this->getRoot();
    }

    public function render(string $view, array $parameters = []): object
    {
        $this->view = \file_get_contents($this->root . $this->viewPath . $view . '.php');
        $this->viewParam = $parameters;
        return $this;
    }

    private function runRender(): void
    {
        extract($this->viewParam);
        $rawView = $this->view;
        $view = \preg_replace('/(?(?=({{.*[\w]+.*}})){{|\0)/', '<?php ', $rawView);
        $middleView = \preg_replace('/(?(?=({.*[\w]+.*})){|\0)/', '<?= ', $view);
        $semiView = \preg_grep('/(<\?php|<\?=).*[\w]+.*}}/', compact('middleView'));
        $finalView = \array_reduce($semiView, function($acc, $value){
            return \preg_replace('/}}?/', '?>', $value);
        });
        eval('?>' . $finalView);
    }

    public function templete(string $templete, string $contentName): object
    {
        $this->templete = $templete;
        $this->contentNameTemplete = $contentName;
        return $this;
    }

    private function runTemplete(): void
    {
        if (empty($this->templete) || empty($this->contentNameTemplete))
            return;

        $templete = \file_get_contents($this->root . $this->viewPath . $this->templetePath . $this->templete . '.php');
        $this->view = \preg_replace('/(?(?=({{\s?' . $this->contentNameTemplete . '\s?}})){{\s?' . $this->contentNameTemplete . '\s?}}|\0)/', $this->view, $templete);
    }

    public function assets(array $js, array $css): object
    {
        $this->jsAssets = $js;
        $this->cssAssets = $css;
        return $this;
    }

    private function runAssets(): void
    {
        if (empty($this->jsAssets) || empty($this->cssAssets))
            return;
        
        $assets = [$this->jsAssets, $this->cssAssets];
        foreach ($assets as $keyAssets => $asset) {
            $content = '';
            foreach ($asset as $key => $value) {
                $path = $_SERVER['REQUEST_SCHEME'] . '://' . APP_BASE . $this->viewPath . $this->assetsPath . ( $keyAssets === 0 ? 'js/': 'css/') . $value;
                $path = str_replace('\\', '/', $path);
                $name = $value;
                if (\is_string($key))
                    $name = $key;
    
                if ($keyAssets === 0) {
                    $content .= '<script src="' . $path . '.js"></script>';
                } else {
                    $content .= '<link rel="stylesheet" href="' . $path . '.css">';
                }
                $this->view = \preg_replace('/(?(?=({{\s?' . $name . '\s?}})){{\s?' . $name . '\s?}}|\0)/', $content, $this->view);
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
        if (empty($this->components))
            return;

        foreach ($this->components as $key => $component) {
            $name = $component;
            if (\is_string($key))
                $name = $key;

            $content = \file_get_contents($this->root . $this->viewPath . $this->componentPath . $component . '.php');
            $this->view = \preg_replace('/(?(?=({{\s?' . $name . '\s?}})){{\s?' . $name . '\s?}}|\0)/', $content, $this->view);
        }
    }

    private function run(): void
    {
        $this->runTemplete();
        $this->runAssets();
        $this->runComponents();
        $this->runRender();
    }

    private function getRoot(): void
    {
        $this->root = \dirname(__DIR__, $this->levels);
    }

    public function __destruct()
    {
        $this->run();
    }
}
