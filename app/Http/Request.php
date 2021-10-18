<?php

namespace App\Http;

class Request
{
    /**
     * Método HTTP da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * URI da pagina
     * @var string
     */
    private $uri;

    /**
     * Pârametros URI ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Variáveis recebidas no POST da pagina ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '' ;
    }

    /**
     * Método responsavel por retornar o método HTTP da requisição
     */
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    /**
     * Método responsavel por retornar o método URI da requisição
     */
    public function getUri(){
        return $this->uri;
    }

    /**
     * Método responsavel por retornar o método headers da requisição
     */
    public function getHeaders(){
        return $this->headers;
    }

    /**
     * Método responsavel por retornar os parâmetro da URL da requisição
     */
    public function getQueryParams(){
        return $this->queryParams;
    }

    /**
     * Método responsavel por retornar o método HTTP da requisição
     */
    public function getPostVars(){
        return $this->postVars;
    }
}