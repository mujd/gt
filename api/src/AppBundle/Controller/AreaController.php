<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Area;

class AprendizajeController extends FOSRestController {

  /**
     * @Rest\Get("/area")
     */
    public function listarAction()
    {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:Area')->findAll();
        if ($restresult === null) {
          return new View("no existen areas", Response::HTTP_NOT_FOUND);
     }
        return $restresult;
    }
    /**
 * @Rest\Get("/area/{id}")
 */
 public function buscarIdAction($id)
 {
   $singleresult = $this->getDoctrine()->getRepository('AppBundle:Area')->find($id);
   if ($singleresult === null) {
   return new View("no se encontró el area de negocio", Response::HTTP_NOT_FOUND);
   }
 return $singleresult;
 }

 /**
  * @Rest\Post("/area")
  */
  public function crearAction(Request $request)
  {
     $area = new Area;

     $nombre = $request->get('nombre');

     $area->setNombre($nombre);

     $em = $this->getDoctrine()->getManager();
     $em->persist($area);
     $em->flush();
     return new View("Area de negocio agregada correctamente", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/area/{id}")
   */
  public function actualizarAction($id, Request $request) {
      $area = new Area;

      $nombre = $request->get('nombre');

      $em = $this->getDoctrine()->getManager();

      $area = $this->getDoctrine()->getRepository('AppBundle:Area')->find($id);
      if (empty($area)) {
          return new View("Area no encontrado", Response::HTTP_NOT_FOUND);
      }elseif (!empty($nombre)) {
          $area->setNombre($nombre);
          $em->flush();
          return new View("Area de negocio Actualizado Correctamente", Response::HTTP_OK);
      }
    }

  /**
   * @Rest\Delete("/area/{id}")
   */
   public function borrarAction($id){
    $area = new Area;

    $em = $this->getDoctrine()->getManager();
    $area = $this->getDoctrine()->getRepository('AppBundle:Area')->find($id);
  if (empty($area)) {
    return new View("Area no encontrado", Response::HTTP_NOT_FOUND);
   }
   else {
    $em->remove($area);
    $em->flush();
   }
    return new View("Area de negocio borrado exitosamente", Response::HTTP_OK);
   }









}
