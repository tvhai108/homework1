<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Product;
use AppBundle\Entity\Task;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class HomeworkController extends Controller
{
    //Step1: Create a Doctrine Entity in CMD window
    /**
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:generate:entities AppBundle/Entity/Product
    php app/console doctrine:schema:update --force
    */

    // Step2: Create a form
/**
http://localhost:8000/app_dev.php/Homework/createform
*/
    /**
    * @Route("Homework/createform", name="createform")
    */
    public function createformAction(Request $request)
    {

        // create a new entity
        $product = new Product();

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, array('label' => 'name'))
            ->add('price', MoneyType::class, array('divisor' => 100,))
            ->add('description', TextType::class, array('label' => 'description'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and Add'))
            ->add('Submit & DONE', SubmitType::class, array('label' => 'Submit & DONE'))
            ->add('Reset_table', ButtonType::class, array('label' => 'reset table'))
            // ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and add'))
            ->getForm();

        $form->handleRequest($request);

        // if ($form->get('Reset_table')->isClicked())
        // {
        //     // Refresh - delete all first
        //     $em = $this->getDoctrine()->getManager();
        //     $arr_old_product = $em->getRepository('AppBundle:Product')->findAll();
        //     foreach ($arr_old_product as $old_product)
        //         $em->remove($old_product);
        //         $em->flush();
        //     return $this->redirectToRoute('createform');
        // }


        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
              $product = $form->getData();

              $em = $this->getDoctrine()->getManager();
              $em->persist($product);
              $em->flush();

              if ($form->get('saveAndAdd')->isClicked())
              {
                return $this->redirectToRoute('createform');
              }
              if ($form->get('Submit & DONE')->isClicked())
              {
                return $this->redirectToRoute('doctrinetable');
              }
            }


            else
            {
              return new Response ('Hello world!');
              // die('not valid');
            }
        }

        return $this->render('default/homework.html.twig', array(
            'form' => $form->createView(),
        ));

        // return new Response ('Hello world!');
    }
}
?>
