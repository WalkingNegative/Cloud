<?php

namespace core;

use app\Managers\UserToken;

class Controller
{
    public $view;

    public function getSection()
    {
        $exploded_url = explode('/', $_SERVER['REQUEST_URI']);

        if (count($exploded_url) === 1) {
            return 'home';
        }

        if ((count($exploded_url) > 1) && ($exploded_url[1] !== 'admin')) {
            return $exploded_url[1];
        }
        
        return "{$exploded_url[1]}/{$exploded_url[2]}";
    }

    public function render($view, $data = [])
    {
        $loader = new \Twig_Loader_Filesystem('app/Views/');
        $twig = new \Twig_Environment($loader, array(
            'cache' => 'cache',
            'auto_reload' => true
        ));

        $data['config'] = $this->getConfig();
        $twig->display($view, $data);
    }

    public function render404()
    {
        $this->render('404.html.twig');
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getConfig() {
        return [
            'section' => $this->getSection(),
            'is_logged' => UserToken::isUserTokenValid($_SESSION['user_token'])
        ];
    }
}