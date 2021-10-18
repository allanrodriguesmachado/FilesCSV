<?php

namespace App\Http;

class Response
{
    /**
     * Status 200
     */
    private $httpCode = 200;

    /**
     *Cabeçalho do response
     */
    private $headers = [];

    /**
     * Conteudo retornado
     */
    private $contentType = 'text/html';

    /**
     *Conteudo do response
     */
    private $content;

    /**
     *Método responsavel por iniciar a classe e definir os valores
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     *Método responsavel por alterar o content type do response
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeaders('Content-Type', $contentType);

    }

    /**
     *Método reponsável por adicionar um registro no cabeçalho do response
     */
    public function addHeaders($key, $values)
    {
        $this->headers[$key] = $values;
    }

    /**
     * Método responsavel por enviar a resposta para o usuário
     */
    private function sendHeaders()
    {
        http_response_code($this->httpCode);
        foreach ($this->headers as $key => $values)
        {
            header($key. ': ' . $values);
        }
    }

    /**
     *Método responsavel por enviar a reposta
     */
    public function sendResponse()
    {
        $this->sendHeaders();
        switch ($this->contentType)
        {
            case 'text/html';
            echo $this->content;
            exit();
        }
    }
}