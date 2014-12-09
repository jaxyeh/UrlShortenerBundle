<?php

namespace Jaxyeh\UrlShortenerBundle\Controller;

use Jaxyeh\UrlShortenerBundle\Entity\Url;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Hashids\Hashids;

class UrlController extends Controller
{
  private function getShortUrl($address)
  {
    // Check if URL existed
    $repository = $this->getDoctrine()->getRepository('JaxyehUrlShortenerBundle:Url');
    $existed = $repository->findOneByUrl($address);

    if($existed) {
      $slug = $existed->getSlug();
    } else {

      // Create new Url Entity
      $url = new Url();
      $url->setUrl($address);

      // Save URL to Database
      $em = $this->getDoctrine()->getManager();
      $em->persist($url);
      $em->flush();

      // Create Hash based on database ID
      $salt = $this->container->getParameter('jaxyeh_url.hashids.salt');
      $min = $this->container->getParameter('jaxyeh_url.hashids.min_length');
      $hashids = new Hashids($salt, $min);
      $slug = $hashids->encode($url->getId());

      // Save Slug to database
      $url->setSlug($slug);
      $em->persist($url);
      $em->flush();
    }
    return $slug;
  }

  public function createAction(Request $request)
  {
    $url = new Url();

    $form = $this->createFormBuilder($url)
      ->add('url', 'url', array(
        'label' => 'Web Address',
        'default_protocol' => 'http'
      ))
      ->add('save', 'submit', array('label' => 'Create Short Url'))
      ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
      return $this->redirect($this->generateUrl('jaxyeh_url_info', array(
        'slug' => $this->getShortUrl($url->getUrl())
      )));
    }

    return $this->render('JaxyehUrlShortenerBundle:Url:index.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function infoAction($slug)
  {
    // var_dump($_SERVER);die();
    $repository = $this->getDoctrine()->getRepository('JaxyehUrlShortenerBundle:Url');
    $url = $repository->findOneBySlug($slug);
    if(!$url) {
      throw $this->createNotFoundException('Url not found!');
    }
    return $this->render('JaxyehUrlShortenerBundle:Url:info.html.twig', array(
      'data' => $url,
    ));
  }

  public function processAction($slug)
  {
    $repository = $this->getDoctrine()->getRepository('JaxyehUrlShortenerBundle:Url');
    $url = $repository->findOneBySlug($slug);
    if(!$url) {
      throw $this->createNotFoundException('Url not found!');
    }
    $url->setHits(($url->getHits() + 1));
    $em = $this->getDoctrine()->getManager();
    $em->persist($url);
    $em->flush();

    return $this->redirect($url->getUrl(), 301);
  }

  public function shortenAction(Request $request)
  {
    // TODO: RESTful API to create shorten url
  }
}
