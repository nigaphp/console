<?php
namespace App\Controller;

use Nigatedev\FrameworkBundle\Controller\AbstractController;
use Nigatedev\FrameworkBundle\Attributes\Route;
use Nigatedev\FrameworkBundle\Http\Request;

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
