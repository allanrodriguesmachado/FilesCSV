<?php

namespace Auth\Controller;

use Auth\Authentication\AuthService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Exception;

class AuthController extends AbstractActionController
{
    private AuthService $AuthService;

    public function __construct(AuthService $authService)
    {
        $this->AuthService = $authService;
    }

    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }

    public function loginAction(): JsonModel
    {
        try {
            $result = $this->AuthService->authenticate($this->getRequest());
            $response = [
                'success' => true,
                'data' => $result ?? NULL
            ];
        } catch (Exception $exception) {
            $response = [
                'success' => false,
                'message' => $exception->getMessage()
            ];
            print_r($result);
        }
        return new JsonModel($response);
    }

    public function logoutAction(): JsonModel
    {
        $this->AuthService->logout($this->getRequest());

        return new JsonModel();
    }
}
