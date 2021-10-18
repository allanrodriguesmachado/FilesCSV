<?php

namespace App\Http;

use \Closure;
use Exception;
use \ReflectionFunction;

class Router
{
    /**
     * URL completa Raiz do projeto
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     */
    private $prefix = '';

    /**
     *Índice de rotas
     */
    private $routes = [];

    /**
     *Instancia de request
     */
    private $resquest;


    public function __construct($url)
    {
        $this->resquest = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     *Método responsavel por definir o prefixo das rotas
     */
    private function setPrefix()
    {
        $parseURL = parse_url($this->url);

        $this->prefix = $parseURL['path'] ?? '';
    }

    /**
     *Método responsavel por adicionar uma rota na classe
     */
    private function addRoute($method, $route, $params = [])
    {
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['variables'] = [];

        $patternVariable = '/{(.*?)}/';

        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $method[1];
        }

        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsavel por definir uma rota de GET
     */
    public function get($route, $params = [])
    {
        $this->addRoute('GET', $route, $params);
    }

    public function post($route, $params = [])
    {
        $this->addRoute('POST', $route, $params);
    }

    public function put($route, $params = [])
    {
        $this->addRoute('PUT', $route, $params);
    }

    public function delete($route, $params = [])
    {
        $this->addRoute('DELETE', $route, $params);
    }


    /**
     * Método responsavel por retornar a URI desonsiderando o prefixo
     */
    private function getUri()
    {
        $uri = $this->resquest->getUri();

        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        return end($xUri);
    }

    /**
     * Método responsavel por retornar os dados da rota atual
     */
    private function getRoute()
    {
        $uri = $this->getUri();
        $httpMethod = $this->resquest->getHttpMethod();
        foreach ($this->routes as $patternRoute => $methods) {
            if(preg_match($patternRoute, $uri, $matches)){
                if(isset($methods[$httpMethod])){
                  unset($matches[0]);
                  $keys = $methods[$httpMethod]['variables'];
                  $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                  $methods[$httpMethod]['variables']['resquest'] = $this->resquest;

                  return $methods[$httpMethod];
                }
                throw new Exception("Método não é permitido", 405);
            }
       }
        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Método responsavel por executar a rota atual
     */
    public function run()
    {
        try {
            $route = $this->getRoute();
            if(!isset($route['controller'])){
                throw new Exception("A URL não pôde ser processada", 500);
            }

            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter){
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return call_user_func($route['controller'], $args);

        }catch (Exception $exception) {
            return new Response($exception->getCode(), $exception->getMessage());
        }
    }
}