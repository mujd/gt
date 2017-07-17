<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Modulo;
use AppBundle\Entity\CursoModulo;


class ModuloController extends FOSRestController {

  /**
   * @Rest\Get("/modulo")
   */
  public function listarAction() {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:Modulo')->findAll();
      if ($restresult === null) {
          return new View("no hay nada", Response::HTTP_NOT_FOUND);
      }
      return $restresult;
  }

  /**
   * @Rest\Get("/modulo/{id}")
   */
  public function buscarIdAction($id) {
      $singleresult = $this->getDoctrine()->getRepository('AppBundle:Modulo')->find($id);
      if ($singleresult === null) {
          return new View("modulo no encontrado", Response::HTTP_NOT_FOUND);
      }
      return $singleresult;
  }

  /**
 * @Rest\Post("/modulo/")
 */
 public function postAction(Request $request){
    $modulo = new Modulo;

    $codigo = $request->get('codigo');
    $nombre = $request->get('nombre');
    $objetivo = $request->get('objetivo');
    $horas = $request->get('horas');

    $modulo->setCodigo($codigo);
    $modulo->setNombre($nombre);
    $modulo->setObjetivo($objetivo);
    $modulo->setHoras($horas);

    $em = $this->getDoctrine()->getManager();
    $em->persist($modulo);
    $em->flush();
    return new View("Modulo agregado correctamente", Response::HTTP_OK);
}

/**
  * @Rest\Post("/modulo")
  */
 public function crearAsignarAction(Request $request) {

     $modulo = new Modulo;
     $cursoModulo = new CursoModulo;

     $cursoId = $request->get('cursoId');

     $codigo = $request->get('codigo');
     $nombre = $request->get('nombre');
     $objetivo = $request->get('objetivo');
     $horas = $request->get('horas');

     $modulo->setCodigo($codigo);
     $modulo->setNombre($nombre);
     $modulo->setObjetivo($objetivo);
     $modulo->setHoras($horas);

     $em = $this->getDoctrine()->getManager();
     $em->persist($modulo);
     $em->flush();

     $moduloId = $modulo->getId();

     $cursoModulo->setCursoId($cursoId);
     $cursoModulo->setModuloId($moduloId);

     $em = $this->getDoctrine()->getManager();
     $em->persist($cursoModulo);
     $em->flush();

     return new View("Modulo creado y asignado a Curso correctamente", Response::HTTP_OK);

 }

 /**
 * @Rest\Put("/modulo/{id}")
 */
 public function actualizarAction($id, Request $request) {
    $modulo = new Modulo;

    $codigo = $request->get('codigo');
    $nombre = $request->get('nombre');
    $objetivo = $request->get('objetivo');
    $horas = $request->get('horas');

    $em = $this->getDoctrine()->getManager();

    $modulo = $this->getDoctrine()->getRepository('AppBundle:Modulo')->find($id);
    if (empty($modulo)) {
        return new View("Modulo no encontrado", Response::HTTP_NOT_FOUND);
    } else {
        $modulo->setCodigo($codigo);
        $modulo->setNombre($nombre);
        $modulo->setObjetivo($objetivo);
        $modulo->setHoras($horas);
        $em->flush();
        return new View("Modulo Actualizado Correctamente", Response::HTTP_OK);
    }
 }

 /**
  * @Rest\Delete("/modulo/{id}")
  */
 public function borrarAction($id) {
     $modulo = new Modulo;

     $em = $this->getDoctrine()->getManager();
     $modulo = $this->getDoctrine()->getRepository('AppBundle:Modulo')->find($id);
     if (empty($modulo)) {
         return new View("No se encontro el modulo", Response::HTTP_NOT_FOUND);
     } else {
         $em->remove($modulo);
         $em->flush();
     }
     return new View("modulo borrado Exitosamente!", Response::HTTP_OK);
 }

}
