<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CashierController extends AbstractController
{
    public function index() {

        return $this->render('cashier/index.twig');
    }
}