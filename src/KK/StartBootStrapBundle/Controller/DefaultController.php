<?php

namespace KK\StartBootStrapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use KK\StartBootStrapBundle\Entity\Post;
use KK\StartBootStrapBundle\Form\Type\ContactType;
use KK\StartBootStrapBundle\Service\EmailManager;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('Bootstrap/index.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('Bootstrap/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        $formHandler = $this->container->get('contact.contact_form_handler');

        if ($email = $formHandler->process($form)) {
//            $this->setFlash('success', 'Your email has been sent! Thanks!');
            $request->getSession()->getFlashBag()->add('success', 'Your email has been sent! Thanks!');
            return $this->redirect($this->generateUrl('contact'));
        }

        return $this->render('Bootstrap/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function blogAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('KKStartBootStrapBundle:Post')
            ->getPosts();

        if (!$posts) {
            throw $this->createAccessDeniedException('No posts were found');
        }

        return $this->render('Bootstrap/blog.html.twig', array(
           'posts' => $posts
        ));
    }

    /**
     * @Route("/show/{slug}", name="show")
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $this->getDoctrine()->getRepository('KKStartBootStrapBundle:Post')
            ->findOneBy(array(
               'slug' => $slug
            ));

        if (!$post) {
            throw $this->createNotFoundException('No post found');
        }

        return $this->render('Bootstrap/show.html.twig', array(
            'post' => $post
        ));
    }
}
