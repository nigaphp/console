<?php
namespace App\Controller;

use Niga\Framework\Controller\AbstractController;
use Niga\Framework\Attributes\Route;
use Niga\Framework\Http\Request;

class ControllerModel extends AbstractController
{
    #[Route('/index', name:'index')]
    public function index(Request $request)
    {
        return $this->render("index", [
        "cName" => "ControllerModel",
        "cPath" => "src/Controller/ControllerModel.php"
        ]);
    }
}
