<?php

namespace Portal\Model;

use Exception;
use Laminas\Http\Client;
use Laminas\Db\Sql\Select;
use PDOException;

class PortalTableModel extends PortalTable
{

    public function getSql(): Select
    {
        $select = $this->sql->select('portal_shibata');
        $this->sql->buildSqlString($select);

        return $select;
    }

    public function select($request): array
    {
        $params = [];

        if ($request->getPost('statusPortal')) {
            $params['statusPortal'] = $request->getPost('statusPortal') == 'A' ? 'ts_cancelamento is null' : 'ts_cancelamento is not null';
        }

        if ($request->getPost('nome')) {
            $params['nome1'] = sprintf('nome ILIKE \'%%%s%%\'', $request->getPost('nome1'));
        }

        if ($request->getPost('endereco')) {
            $params['rua'] = sprintf('rua ILIKE \'%%%s%%\'', $request->getPost('endereco'));
        }

        if (count($params) > 0) {
            $parametros = " where " . join(" AND ", $params);
            $condicao = sprintf(' %s ', $parametros);
        }

        $statement = $this->adapter->query(
            sprintf("
                    SELECT  id_pessoa,
                            endereco.id_endereco,
                            nome,
                            ts_cancelamento as                            active,
                            rua || ', ' ||
                            COALESCE(numero, 'S/N') AS           endereco_completo, rua,
                            numero,
                            ts_cancelamento,
                            TO_CHAR(ts_inclusao, 'DD/MM/YYYY HH24:MI:SS') ts_inclusao
                    FROM pessoa
                             JOIN endereco ON pessoa.id_endereco = endereco.id_endereco
                    %s
                    ", $condicao)
        );

        $users = $statement->execute();

        foreach ($users as $item) {
            $item['active'] = $item['ts_cancelamento'] ? 0 : 1;

            $result[] = $item;
        }

        return $result ?? [];
    }

    public function update($request)
    {
        try {
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $statement = $this->adapter->query("
                   UPDATE pessoa
                   SET nome = $1
                   WHERE id_pessoa = $2
                ");

            $result = $statement->execute([
                'nome' => $request->getPost('nome'),
                'id_pessoa' => $request->getPost('id_pessoa')
            ]);

            $this->response($result);

            $statement = $this->adapter->query("
                    UPDATE endereco
                    SET rua = $1, numero = $2
                    WHERE id_endereco = $3
                ");

            $result = $statement->execute([
                'rua' => $request->getPost('rua'),
                'numero' => $request->getPost('numero'),
                'id_endereco' => $request->getPost('id_endereco')
            ]);

            $this->response($result);

            $this->adapter->getDriver()->getConnection()->commit();

        } catch (PDOException $PDOException) {
            try {
                $this->adapter->getDriver()->getConnection()->rollback();
                echo $PDOException->getMessage();
            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }

    public function create($request)
    {
        $nome = filter_var(empty($request->getPost('nome')), FILTER_SANITIZE_SPECIAL_CHARS);
        $endereco = filter_var(empty($request->getPost('rua')) && empty($request->getPost('numero')), FILTER_SANITIZE_SPECIAL_CHARS);

        if ($nome && $endereco) {
            throw new Exception("Preencha todos os campos");
        }

        try {
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $statement = $this->adapter->query("
               INSERT INTO endereco (rua, numero)
                VALUES ($1, $2);
                ");

            $statement->execute([
                'rua' => $request->getPost('rua'),
                'numero' => $request->getPost('numero')
            ]);

            //            $this->adapter->getDriver()->getConnection()->getLastGeneratedValue();

            $statement = $this->adapter->query("
                    INSERT INTO pessoa (nome, id_endereco)
                    VALUES ($1, currval('address_user_id_seq'))
                ");

            $statement->execute([
                'nome' => $request->getPost('nome'),
            ]);

            $this->adapter->getDriver()->getConnection()->commit();

        } catch (PDOException $PDOException) {
            try {
                $this->adapter->getDriver()->getConnection()->rollback();
                echo $PDOException->getMessage();
            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }

    public function statusUsuario($request)
    {
        $statement = $this->adapter->query(sprintf('
                UPDATE pessoa
                SET ts_cancelamento = $1
                WHERE id_pessoa = $2
            '));

        $statement->execute([
            'ts_cancelamento' => $request->getPost('active') == 0 ? null : 'now()',
            'id_pessoa' => $request->getPost('id_pessoa')
        ]);

        return [];
    }

    public function createValidation($request)
    {
        if ($request->getPost('id_pessoa')) {
            $this->update($request);
        } else {
            $this->create($request);
        }
    }

    /**
     *  ---------- Empresa
     */

    /**
     * @throws Exception
     */
    public function formEmpresa($request)
    {
        $cnpj = $this->validaCnpj($request->getPost('cnpj'));

        if ($cnpj == false) {
            throw new Exception("CNPJ inválido");
        }

        $statement = $this->adapter->query("
             SELECT
                id_empresa,
                razao_social,
                cnpj,
                nome_fantasia,
                ddd_telefone,
                telefone,
                ddd_celular,
                celular
                FROM empresa
                JOIN contato ON empresa.id_contato = contato.id_contato
                WHERE cnpj = $1
            ");

        $empresa = $statement->execute([
            'cnpj' => $request->getPost('cnpj')
        ]);

        return $this->response($empresa);
    }

    /**
     * @throws Exception
     */
    public function createEmpresa($request)
    {
        parse_str($request->getPost('data'), $data);

        $telefone = $this->regex($data['telefone']);
        $celular = $this->regex($data['telefone']);
        $ddd_telefone = $this->regex($data['ddd_telefone']);
        $ddd_celular = $this->regex($data['ddd_celular']);
        $cep = $this->regex($data['cep']);

        $razao_social = $data['razao_social'];
        $nome_fantasia = $data['nome_fantasia'];

        if (empty($razao_social) && empty($nome_fantasia)) {
            throw new Exception("Preencha todos os campos");
        }

        try {
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $statement = $this->adapter->query("
                    INSERT INTO contato (telefone, celular, ddd_telefone, ddd_celular)
                    VALUES ($1, $2, $3, $4);
                ");

            $statement->execute([
                'telefone' => $telefone,
                'celular' => $celular,
                'ddd_telefone' => $ddd_telefone,
                'ddd_celular' => $ddd_celular,
            ]);

            $statement = $this->adapter->query("
                    INSERT INTO endereco_compl(rua, numero, bairro, complemento, cidade, municipio, estado, cep)
                    VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
                ");

            $statement->execute([
                'rua' => $request->getPost('rua'),
                'numero' => $request->getPost('numero'),
                'bairro' => $request->getPost('bairro'),
                'complemento' => $request->getPost('complemento'),
                'cidade' => $request->getPost('cidade'),
                'municipio' => $request->getPost('municipio'),
                'estado' => $request->getPost('estado'),
                'cep' => $cep
            ]);

            $statement = $this->adapter->query("
                    INSERT INTO empresa (cnpj, razao_social, nome_fantasia, id_contato, id_ende)
                    VALUES ($1, $2, $3, currval('contato_id_contato_seq'), currval('endereco_compl_id_endereco_seq'));
                ");

            $statement->execute([
                'cnpj' => $request->getPost('cnpj'),
                'razao_social' => $data['razao_social'],
                'nome_fantasia' => $data['nome_fantasia'],
            ]);

            $this->adapter->getDriver()->getConnection()->commit();

        } catch (Exception $exception) {
            try {
                $this->adapter->getDriver()->getConnection()->rollback();
                echo $PDOException->getMessage();
            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }

    public function httpClient($request) : bool
    {
        $cep = $this->validaCEP($request->getPost('cep'));

        if ($cep == false) {
            throw new Exception("CEP invalido");
        }

        if (empty($cep)) {
            throw new Exception("CEP invalido");
        }

        $client = new Client('http://cep.mercatus.com.br/cep.php?',
            [
                'maxredirects' => 0,
                'tempo limite' => 30
            ]
        );

        $client->setParameterGet(
            [
                'cep' => $request->getPost('cep'),
                'format' => 'json',
                'key' => 'f7a73a53e5e2866c49c57df8583ce1e5'
            ]
        );

        $result = $client->send()->getContent();

        return json_decode($result) ?? [];
    }

    public function validaCnpj(int $cnpj)
    {
        $cnpj = $this->regex($cnpj);

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        if (strlen($cnpj) != 14)
            return false;

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    function validaCPF(int $cpf)
    {
        $cpf = $this->regex($cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    public function validaCEP(int $cep)
    {
        $cep = $this->regex($cep);

        if (preg_match('/(\d)\1{8}/', $cep)) {
            return false;
        }

        if (strlen($cep) != 8) {
            return false;
        }

        return true;
    }

    function regex(bool $input)
    {
        return preg_replace('/[^0-9]/', '', $input);
    }
}

