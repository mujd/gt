<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\CentroDistribucion;

class CentroDistribucionController extends FOSRestController {

  /**
     * @Rest\Get("/centroDistribucion")
     */
    public function listarAction()
    {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:CentroDistribucion')->findAll();
        if ($restresult === null) {
          return new View("no existen centros de distribucion", Response::HTTP_NOT_FOUND);
     }
        return $restresult;
    }
    /**
 * @Rest\Get("/centroDistribucion/{id}")
 */
 public function buscarIdAction($id)
 {
   $singleresult = $this->getDoctrine()->getRepository('AppBundle:CentroDistribucion')->find($id);
   if ($singleresult === null) {
   return new View("no se encontró el centro de distribucion", Response::HTTP_NOT_FOUND);
   }
 return $singleresult;
 }

 /**
  * @Rest\Post("/centroDistribucion")
  */
  public function crearAction(Request $request)
  {
     $centroDistribucion = new CentroDistribucion;

     $nombre = $request->get('nombre');
     $direccion = $request->get('direccion');
     $comunaId = $request->get('comunaId');

     $centroDistribucion->setNombre($nombre);
     $centroDistribucion->setDireccion($direccion);
     $centroDistribucion->setComunaId($comunaId);

     $em = $this->getDoctrine()->getManager();
     $em->persist($centroDistribucion);
     $em->flush();
     return new View("Centro de distribucion agregado correctamente", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/centroDistribucion/{id}")
   */
  public function actualizarAction($id, Request $request) {
      $centroDistribucion = new CentroDistribucion;

      $nombre = $request->get('nombre');
      $direccion = $request->get('direccion');
      $comunaId = $request->get('comunaId');

      $em = $this->getDoctrine()->getManager();
      $centroDistribucion = $this->getDoctrine()->getRepository('AppBundle:CentroDistribucion')->find($id);
      if (empty($centroDistribucion)) {
          return new View("Centro de distribucion no encontrado", Response::HTTP_NOT_FOUND);
      }elseif (!empty($nombre) && !empty($direccion) && !empty($comunaId)) {
          $centroDistribucion->setNombre($nombre);
          $centroDistribucion->setDireccion($direccion);
          $centroDistribucion->setComunaId($comunaId);
          $em->flush();
          return new View("Centro de distribucion Actualizado Correctamente", Response::HTTP_OK);
      }
    }

  /**
   * @Rest\Delete("/centroDistribucion/{id}")
   */
   public function borrarAction($id){
    $centroDistribucion = new CentroDistribucion;

    $em = $this->getDoctrine()->getManager();
    $centroDistribucion = $this->getDoctrine()->getRepository('AppBundle:CentroDistribucion')->find($id);
  if (empty($centroDistribucion)) {
    return new View("Centro de distribucion no encontrado", Response::HTTP_NOT_FOUND);
   }
   else {
    $em->remove($centroDistribucion);
    $em->flush();
   }
    return new View("Centro de distribucion borrado exitosamente", Response::HTTP_OK);
   }









}
