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
        $this->view = \file_get_contents(ROOT . $this->viewPath . $view . '.php');
        $this->viewParam = $parameters;
        return $this;
    }

    private function runRender(): void
    {
        if (empty($this->view) || is_null($this->viewParam)) {
            return;
        }

        foreach ($this->viewParam as $param) {
            if (Arr::isAssoc($param)) {
                extract($param);
            } else {
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

        $templete = \file_get_contents(ROOT . $this->viewPath . $this->templetePath . $this->templete . '.php');
        $this->view = \preg_replace('/(?(?=({{\s?' . $this->aliasTemplete . '\s?}})){{\s?' . $this->aliasTemplete . '\s?}}|\0)/', $this->view, $templete);
    }

    public function assets(array $css, array $js = []): object
    {
        $this->jsAssets = $js;
        $this->cssAssets = $css;
        return $this;
    }

    private function runAssets(): void
    {
        if (empty($this->jsAssets) && empty($this->cssAssets))
            return;
        
        $assets = [$this->cssAssets, $this->jsAssets];

        foreach ($assets as $keyAssets => $asset) {
            $content = '';

            foreach ($asset as $key => $value) {

                $path = $_SERVER['REQUEST_SCHEME'] . '://' . APPBASE . $this->viewPath . $this->assetsPath . ( $keyAssets === 0 ? 'css/': 'js/') . $value;
                $path = str_replace('\\', '/', $path);
                $name = $value;

                if (\is_string($key)) {
                    $name = $key;
                }
    
                if ($keyAssets === 0) {
                    $content .= '<link rel="stylesheet" href="' . $path . '.css">';
                } else {
                    $content .= '<script src="' . $path . '.js"></script>';
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

            if (\is_string($key)) {
                $name = $key;
            }

            $content = \file_get_contents(ROOT . $this->viewPath . $this->componentPath . $component . '.php');
            $this->view = \preg_replace('/(?(?=({{\s?' . $name . '\s?}})){{\s?' . $name . '\s?}}|\0)/', $content, $this->view);
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
