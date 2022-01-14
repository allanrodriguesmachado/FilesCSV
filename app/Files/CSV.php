<?php

namespace App\Files;

class CSV
{

    public static function lerArquivo($arquivo, bool $cabecalho = true, string $delimitador = ',')
    {
       //Verificar se o arquivo existe
        if (!file_exists($arquivo)) {
            die("\n arquivo não encontrado");
        }

        // Dados das linhas do arquivo
        $dados = [];

        //Abre o arquivo
        $csv = fopen($arquivo, 'r');

        //Cabecalho dos dados
        $cabecalhoDados = $cabecalho ? fgetcsv($csv, 0, $delimitador) : [];


        //Itera o arquivo, lendo todas as linhas
        while ($linha = fgetcsv($csv, 0, $delimitador)) {
            $dados[] = $cabecalho ? array_combine($cabecalhoDados, $linha) : $linha;
        }

        fclose($csv);

        return $dados;
    }


    public static function criarArquivo (string $arquivo, array $dados, string $delimitador = ','): bool
    {
        //Abre o arquivo para escrita
        $csv = fopen($arquivo, 'w');

        //Cria o corpo do arquivo CSV
        foreach ($dados as $linha) {
            fputcsv($csv, $linha, $delimitador);
        }

        //Fechar o arquivo
        fclose($csv);

        return true;
    }

}