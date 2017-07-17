<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Provincia;

class ProvinciaController extends FOSRestController {

  /**
     * @Rest\Get("/provincia")
     */
    public function listarAction()
    {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:Provincia')->findAll();
        if ($restresult === null) {
          return new View("no existen provincias", Response::HTTP_NOT_FOUND);
     }
        return $restresult;
    }
    /**
 * @Rest\Get("/provincia/{id}")
 */
 public function buscarIdAction($id)
 {
   $singleresult = $this->getDoctrine()->getRepository('AppBundle:Provincia')->find($id);
   if ($singleresult === null) {
   return new View("no se encontró la provincia", Response::HTTP_NOT_FOUND);
   }
 return $singleresult;
 }

 /**
  * @Rest\Get("/provincia/region/{regionId}")
  */
 public function listarAsignadasAction($regionId) {
     $resultado = $this->getDoctrine()->getRepository('AppBundle:Provincia')->findBy(array('regionId' => $regionId));
     if ($resultado === null) {
         return new View("no hay provincias asociadas a esta region", Response::HTTP_NOT_FOUND);
     }
     return $resultado;
 }

 /**
  * @Rest\Post("/provincia")
  */
  public function crearAction(Request $request)
  {
     $provincia = new Provincia;

     $nombre = $request->get('nombre');
     $regionId = $request->get('regionId');

     $provincia->setNombre($nombre);
     $provincia->setRegionId($regionId);

     $em = $this->getDoctrine()->getManager();
     $em->persist($provincia);
     $em->flush();
     return new View("Provincia agregada correctamente", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/provincia/{id}")
   */
  public function actualizarAction($id, Request $request) {
      $provincia = new Provincia;

      $nombre = $request->get('nombre');
      $regionId = $request->get('regionId');

      $em = $this->getDoctrine()->getManager();
      $provincia = $this->getDoctrine()->getRepository('AppBundle:Provincia')->find($id);
      if (empty($provincia)) {
          return new View("Provincia no encontrada", Response::HTTP_NOT_FOUND);
      } elseif (!empty($nombre && !empty($regionId))) {
          $provincia->setNombre($nombre);
          $provincia->setRegionId($regionId);
          $em->flush();
          return new View("Provincia Actualizada Correctamente", Response::HTTP_OK);
      }
  }

  /**
   * @Rest\Delete("/provincia/{id}")
   */
   public function borrarAction($id){
    $provincia = new Provincia;

    $em = $this->getDoctrine()->getManager();
    $provincia = $this->getDoctrine()->getRepository('AppBundle:Provincia')->find($id);
  if (empty($provincia)) {
    return new View("Provincia no encontrada", Response::HTTP_NOT_FOUND);
   }
   else {
    $em->remove($provincia);
    $em->flush();
   }
    return new View("provincia borrada exitosamente", Response::HTTP_OK);
   }









}
