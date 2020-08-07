<?php

namespace Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * View
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view The view file
     *
     * @param array $args
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP); // 연관 배열 -> 변수 나누기

        $file = "../App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            echo "View 파일 중 [ $file ] 파일을 찾을 수 없음";
        }
    }

    /**
     * @param string $template
     * @param array $args
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function renderTemplate(string $template, array $args = [])
    {
        static $twig = null;

        if ($twig === null)
        {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader);
        }

        echo $twig->render($template, $args);
    }

}
