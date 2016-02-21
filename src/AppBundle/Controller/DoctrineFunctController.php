<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Product;
use AppBundle\Entity\Task;
use AppBundle\Entity\Customer;
/**
use Doctrine\ORM\EntityManager;
*/

class DoctrineFunctController extends Controller
{
/**
http://localhost:8000/app_dev.php/CustomerList/table
*/
    /**
    * @Route("/CustomerList/table", name="doctrinetable")
    */
    public function PrintTableAction()
    {
      $repository = $this->getDoctrine()->getRepository('AppBundle:Product'); // fetch (entity from database) >< flush
      $arr_ = $repository->findAll();

        return $this->render(
            'default/doctrine_table.html.twig',
            array('arr_' => $arr_)
        );
    }
}
?>
