<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Zona;

class AprendizajeController extends FOSRestController {

  /**
     * @Rest\Get("/zona")
     */
    public function listarAction()
    {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:Zona')->findAll();
        if ($restresult === null) {
          return new View("no existen zonas", Response::HTTP_NOT_FOUND);
     }
        return $restresult;
    }
    /**
 * @Rest\Get("/zona/{id}")
 */
 public function buscarIdAction($id)
 {
   $singleresult = $this->getDoctrine()->getRepository('AppBundle:Zona')->find($id);
   if ($singleresult === null) {
   return new View("no se encontró el zona", Response::HTTP_NOT_FOUND);
   }
 return $singleresult;
 }

 /**
  * @Rest\Post("/zona")
  */
  public function crearAction(Request $request)
  {
     $zona = new Zona;

     $nombre = $request->get('nombre');

     $zona->setNombre($nombre);

     $em = $this->getDoctrine()->getManager();
     $em->persist($zona);
     $em->flush();
     return new View("Zona agregada correctamente", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/zona/{id}")
   */
  public function actualizarAction($id, Request $request) {
      $zona = new Zona;

      $nombre = $request->get('nombre');

      $em = $this->getDoctrine()->getManager();

      $zona = $this->getDoctrine()->getRepository('AppBundle:Zona')->find($id);
      if (empty($zona)) {
          return new View("Zona no encontrada", Response::HTTP_NOT_FOUND);
      }elseif (!empty($nombre)) {
          $zona->setNombre($nombre);
          $em->flush();
          return new View("Zona Actualizado Correctamente", Response::HTTP_OK);
      }
    }

  /**
   * @Rest\Delete("/zona/{id}")
   */
   public function borrarAction($id){
    $zona = new Zona;

    $em = $this->getDoctrine()->getManager();
    $zona = $this->getDoctrine()->getRepository('AppBundle:Zona')->find($id);
  if (empty($zona)) {
    return new View("Zona no encontrado", Response::HTTP_NOT_FOUND);
   }
   else {
    $em->remove($zona);
    $em->flush();
   }
    return new View("Zona de borrado exitosamente", Response::HTTP_OK);
   }









}
