<?php

namespace Portal\Controller;

use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Portal\Model\PortalTableModel;

class PortalController extends AbstractActionController
{
    private PortalTableModel $PortalTableModel;

    public function __construct(PortalTableModel $PortalTableModel)
    {
        $this->PortalTableModel = $PortalTableModel;
    }

    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }

    public function empresaAction(): ViewModel
    {
        return new ViewModel();
    }

    public function usuarioAction(): ViewModel
    {
        return new ViewModel();
    }

    public function formAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $result = $this->PortalTableModel->select($request);
            return new JsonModel([
                'data' => isset($result) ? array_values($result) : NULL,
                'success' => true
            ]);
        }
        return new JsonModel();
    }

    public function updateAction(): JsonModel
    {
        try {
            $request = $this->getRequest();

            if ($request->isPost()) {
                $this->PortalTableModel->createValidation($request);
                return new JsonModel ([
                    'success' => true
                ]);
            }
        } catch (Exception $exception) {
            return new JsonModel  ([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
        return new JsonModel();
    }

    public function statusUsuarioAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->getPost()) {
            $result = $this->PortalTableModel->statusUsuario($request);
        }
        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => true
        ]);
    }

    public function formEmpresaAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {

            try {
                $result = $this->PortalTableModel->formEmpresa($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function createEmpresaAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {

            try {
                $result = $this->PortalTableModel->createEmpresa($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function httpClientAction(): JsonModel
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->httpClient($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => $result ?? NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }
}
