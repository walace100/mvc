<?php

namespace Lib\Controllers;

abstract class Controller
{
    private $root;

    private $levels;

    private $path;

    public function __construct()
    {
        $this->path = '/Views/';
        $this->levels = 2;
        $this->getRoot();
    }

    public function render(string $view, array $parameters = []): void
    {
        extract($parameters);
        $rawView = \file_get_contents($this->root . $this->path . $view . '.php');
        $view = \preg_replace('/(?(?=({{.*[\w]+.*}})){{|\0)/', '<?php ', $rawView);
        $middleView = preg_replace('/(?(?=({.*[\w]+.*})){|\0)/', '<?= ', $view);
        $semiView = \preg_grep('/(<\?php|<\?=).*[\w]+.*}}/', compact('middleView'));
        $finalView = array_reduce($semiView, function($acc, $value){
            return \preg_replace('/}}?/', '?>', $value);
        });
        eval('?>'. $finalView);
    }

    private function getRoot(): void
    {
        $this->root = \dirname(__DIR__, $this->levels);
    }
}
