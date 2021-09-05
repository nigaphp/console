<?php
namespace App\Controller;

use Nigatedev\FrameworkBundle\Controller;

class ControllerModel extends Controller
{
   /**
    * @return mixed
    */
    public function index()
    {
        return $this->render("index", [
        "cName" => "ControllerModel",
        "cPath" => "src/Controller/ControllerModel.php"
        ]);
    }
}
