<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Region;

class RegionController extends FOSRestController {

  /**
     * @Rest\Get("/region")
     */
    public function listarAction()
    {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:Region')->findAll();
        if ($restresult === null) {
          return new View("no existen regiones", Response::HTTP_NOT_FOUND);
     }
        return $restresult;
    }
    /**
 * @Rest\Get("/region/{id}")
 */
 public function buscarIdAction($id)
 {
   $singleresult = $this->getDoctrine()->getRepository('AppBundle:Region')->find($id);
   if ($singleresult === null) {
   return new View("no se encontró la region", Response::HTTP_NOT_FOUND);
   }
 return $singleresult;
 }

 /**
  * @Rest\Get("/region/{zonaId}")
  */
 public function listarAsignadasAction($zonaId) {
     $resultado = $this->getDoctrine()->getRepository('AppBundle:Region')->findBy(array('zonaId' => $zonaId));
     if ($resultado === null) {
         return new View("no hay regiones con esa zona", Response::HTTP_NOT_FOUND);
     }
     return $resultado;
 }

 /**
  * @Rest\Post("/region")
  */
  public function crearAction(Request $request)
  {
     $region = new Region;

     $nombre = $request->get('nombre');

     $region->setNombre($nombre);

     $em = $this->getDoctrine()->getManager();
     $em->persist($region);
     $em->flush();
     return new View("Region agregada correctamente", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/region/{id}")
   */
  public function actualizarAction($id, Request $request) {
      $region = new Region;

      $nombre = $request->get('nombre');

      $em = $this->getDoctrine()->getManager();
      $region = $this->getDoctrine()->getRepository('AppBundle:Region')->find($id);
      if (empty($region)) {
          return new View("Region no encontrada", Response::HTTP_NOT_FOUND);
      }elseif (!empty($nombre)) {
          $region->setNombre($nombre);
          $em->flush();
          return new View("Region Actualizada Correctamente", Response::HTTP_OK);
      }
    }

  /**
   * @Rest\Delete("/region/{id}")
   */
   public function borrarAction($id){
    $region = new Region;

    $em = $this->getDoctrine()->getManager();
    $region = $this->getDoctrine()->getRepository('AppBundle:Region')->find($id);
  if (empty($region)) {
    return new View("Region no encontrada", Response::HTTP_NOT_FOUND);
   }
   else {
    $em->remove($region);
    $em->flush();
   }
    return new View("region borrada exitosamente", Response::HTTP_OK);
   }









}
