<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Form\Type\ProductType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    
    /**
     * @Route("/add-product", name="add-product")
     */
    public function addProductAction(Request $request) {
        $product = new Product();
        
        $form = $this->createForm(ProductType::class, $product);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('default/add-product.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/add-category", name="add-category")
     */
    public function addCategoryAction(Request $request) {
        $category = new Category();
        
        $form = $this->createFormBuilder($category)
                ->add('name', TextType::class)
                ->add('save', SubmitType::class, array('label' => 'Zapisz') )
                ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('default/add-category.html.twig', array(
            'form' => $form->createView()
        ));
    }

    
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $category = new Category();
        $form = $this->createFormBuilder()->add('id', 'entity', array(
                'class' => 'AppBundle\Entity\Category',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true
              ))
            ->add('save', 'submit')->getForm();
        $form->handleRequest($request);
        
        $products = $this->getDoctrine()
        ->getRepository('AppBundle:Product');
        
        if ($form->isSubmitted()) {
           $data = $form->get('id')->getData();
           $ids = [];
           foreach($data as $row) {
               array_push($ids, $row->getId());
           }
           
           $products = $products->findByCategoryId($ids);
            
        }
        else {
            $products = $this->getDoctrine()
            ->getRepository('AppBundle:Product')->findAll();
            
        }
        
        
        
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'products' => $products,
            'form' => $form->createView()
        ));
    }
}
