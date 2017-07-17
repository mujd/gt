<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Aprendizaje;

class AprendizajeController extends FOSRestController {

  /**
     * @Rest\Get("/aprendizaje")
     */
    public function listarAction()
    {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:Aprendizaje')->findAll();
        if ($restresult === null) {
          return new View("no existen aprendizajes", Response::HTTP_NOT_FOUND);
     }
        return $restresult;
    }
    /**
 * @Rest\Get("/aprendizaje/{id}")
 */
 public function buscarIdAction($id)
 {
   $singleresult = $this->getDoctrine()->getRepository('AppBundle:Aprendizaje')->find($id);
   if ($singleresult === null) {
   return new View("no se encontró el aprendizaje", Response::HTTP_NOT_FOUND);
   }
 return $singleresult;
 }

 /**
  * @Rest\Post("/aprendizaje")
  */
  public function crearAction(Request $request)
  {
     $aprendizaje = new Aprendizaje;

     $nombre = $request->get('nombre');

     $aprendizaje->setNombre($nombre);

     $em = $this->getDoctrine()->getManager();
     $em->persist($aprendizaje);
     $em->flush();
     return new View("Aprendizaje agregado correctamente", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/aprendizaje/{id}")
   */
  public function actualizarAction($id, Request $request) {
      $aprendizaje = new Aprendizaje;

      $nombre = $request->get('nombre');

      $em = $this->getDoctrine()->getManager();

      $aprendizaje = $this->getDoctrine()->getRepository('AppBundle:Aprendizaje')->find($id);
      if (empty($aprendizaje)) {
          return new View("Aprendizaje no encontrado", Response::HTTP_NOT_FOUND);
      }elseif (!empty($nombre)) {
          $aprendizaje->setNombre($nombre);
          $em->flush();
          return new View("Aprendizaje Actualizado Correctamente", Response::HTTP_OK);
      }
    }

  /**
   * @Rest\Delete("/aprendizaje/{id}")
   */
   public function borrarAction($id){
    $aprendizaje = new Aprendizaje;

    $em = $this->getDoctrine()->getManager();
    $aprendizaje = $this->getDoctrine()->getRepository('AppBundle:Aprendizaje')->find($id);
  if (empty($aprendizaje)) {
    return new View("Aprendizaje no encontrado", Response::HTTP_NOT_FOUND);
   }
   else {
    $em->remove($aprendizaje);
    $em->flush();
   }
    return new View("Aprendizaje borrado exitosamente", Response::HTTP_OK);
   }









}
