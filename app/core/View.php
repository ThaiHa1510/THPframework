<?php
namespace app\core;

class View
{
    protected $file;
    protected $data;
    
    protected $twig;

    public function __construct($file, $data = array())
    {
        $this->file = $file;
        $this->data = $data;
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/views/');

        // Instantiate our Twig
        $this->twig = new \Twig\Environment($loader);
    }

    public function __toString()
    {
        return $this->parseView();
    }

    public function parseView()
    {
        $file = $this->file . '.php';
        $this->twig->load($file);

        if (is_null($this->data)) {
            return $this->twig->render($file);
        }

        return $this->twig->render($file, $this->data);
    }
}