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

//use Doctrine\ORM\EntityManager;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ));
    }

	// the following is added according to "http://symfony.com/doc/current/book/doctrine.html"
	/**
	* @Route("/todatabase")
	*/
	public function todatabaseAction()
	{
    // assign properties to $product
		$product = new Product();
		$product->setName('A Foo Bar');
		$product->setPrice('19.99');
		$product->setDescription('Lorem ipsum dolor');

    // start Doctrine and put $product into the database
		$em = $this->getDoctrine()->getManager();
		$em->persist($product);
		$em->flush(); // flush (entity into database) >< fetch

		return new Response('Created product id '.$product->getId());
	}
  /**
  * @Route("/fromdatabase/{id}")
  */
  public function showAction($id)
  {
      // take from databse the entity with id=argument $id, assign as $product
      $product = $this->getDoctrine()
          ->getRepository('AppBundle:Product') // fetch (entity from database) >< flush
          ->find($id);

      // raise error if id not found
      if (!$product) {
          throw $this->createNotFoundException(
              'No product found for id '.$id
          );
      }

      // main action
      return new Response('The entity with id of '.$product->getId().' is assigned to $product');
  }

  /**
  * @Route("/updatedatabase/{id}")
  */
  public function updateAction($id)
  {
      // fetch entity Product, assign the one with $id as $product
      $em = $this->getDoctrine()->getManager();
      $product = $em->getRepository('AppBundle:Product')->find($id);

      // raise errors if not found
      if (!$product) {
          throw $this->createNotFoundException(
              'No product found for id '.$id
          );
      }

      // update or modify
      $product->setName('New product name!');

      // flush back databse. But it's not clear! There's fixed link between $product & $em ???
      $em->flush();

      return $this->redirectToRoute('homepage');
  }

  /**
  * @Route("/createformdefault", name="createform")
  */
  public function createformAction(Request $request)
  {
      // create a task and give it some dummy data for this example
      $task = new Task();
      //$task->setTask('Write a blog post');
      //$task->setDueDate(new \DateTime('tomorrow'));

      $form = $this->createFormBuilder($task)
          ->add('task', TextType::class)
          ->add('dueDate', DateType::class, array('widget' => 'single_text'))
          ->add('save', SubmitType::class, array('label' => 'Save'))
          ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and add'))
          ->getForm();

      $form->handleRequest($request);

      if ($form->isSubmitted()) {
          if ($form->isValid()) {
              $nextAction = $form->get('saveAndAdd')->isClicked()
              ? 'createform'
              : 'homepage';
              return $this->redirectToRoute($nextAction);

          }
          else {
              echo "dien ngu vai";
          }
      }

      return $this->render('default/new.html.twig', array(
          'arg_in_twig' => $form->createView(),
      ));
  }

}
