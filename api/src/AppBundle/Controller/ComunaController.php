<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Comuna;

class ComunaController extends FOSRestController {

  /**
     * @Rest\Get("/comuna")
     */
    public function listarAction()
    {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:Comuna')->findAll();
        if ($restresult === null) {
          return new View("no existen comunas", Response::HTTP_NOT_FOUND);
     }
        return $restresult;
    }
    /**
 * @Rest\Get("/comuna/{id}")
 */
 public function buscarIdAction($id)
 {
   $singleresult = $this->getDoctrine()->getRepository('AppBundle:Comuna')->find($id);
   if ($singleresult === null) {
   return new View("no se encontró la comuna", Response::HTTP_NOT_FOUND);
   }
 return $singleresult;
 }

 /**
  * @Rest\Get("/comuna/provincia/{provinciaId}")
  */
 public function listarAsignadasAction($provinciaId) {
     $resultado = $this->getDoctrine()->getRepository('AppBundle:Comuna')->findBy(array('provinciaId' => $provinciaId));
     if ($resultado === null) {
         return new View("no hay comunas asociadas a esta provincia", Response::HTTP_NOT_FOUND);
     }
     return $resultado;
 }
 /**
  * @Rest\Get("/comuna/provincia/{provinciaId}")
  */
 public function listarRegionesAsignadasAction($regionId) {
     $resultado = $this->getDoctrine()->getRepository('AppBundle:Comuna')->findBy(array('regionId' => $regionId));
     if ($resultado === null) {
         return new View("no hay comunas asociadas a esta region", Response::HTTP_NOT_FOUND);
     }
     return $resultado;
 }


 /**
  * @Rest\Post("/comuna")
  */
  public function crearAction(Request $request)
  {
     $comuna = new Comuna;

     $nombre = $request->get('nombre');
     $provinciaId = $request->get('provinciaId');
     $regionId = $request->get('regionId');

     $comuna->setNombre($nombre);
     $comuna->setProvinciaId($provinciaId);
     $comuna->setRegionId($regionId);

     $em = $this->getDoctrine()->getManager();
     $em->persist($comuna);
     $em->flush();
     return new View("Comuna agregada correctamente", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/comuna/{id}")
   */
  public function actualizarAction($id, Request $request) {
      $comuna = new Comuna;

      $nombre = $request->get('nombre');
      $provinciaId = $request->get('provinciaId');
      $regionId = $request->get('regionId');

      $em = $this->getDoctrine()->getManager();
      $comuna = $this->getDoctrine()->getRepository('AppBundle:Comuna')->find($id);
      if (empty($comuna)) {
          return new View("Comuna no encontrada", Response::HTTP_NOT_FOUND);
      } elseif (!empty($nombre) && !empty($provinciaId) && !empty($regionId)) {
          $comuna->setNombre($nombre);
          $comuna->setProvinciaId($provinciaId);
          $comuna->setRegionId($regionId);
          $em->flush();
          return new View("Comuna Actualizada Correctamente", Response::HTTP_OK);
      }
  }

  /**
   * @Rest\Delete("/comuna/{id}")
   */
   public function borrarAction($id){
    $comuna = new Comuna;

    $em = $this->getDoctrine()->getManager();
    $comuna = $this->getDoctrine()->getRepository('AppBundle:Comuna')->find($id);
  if (empty($comuna)) {
    return new View("Comuna no encontrada", Response::HTTP_NOT_FOUND);
   }
   else {
    $em->remove($comuna);
    $em->flush();
   }
    return new View("comuna borrada exitosamente", Response::HTTP_OK);
   }









}
