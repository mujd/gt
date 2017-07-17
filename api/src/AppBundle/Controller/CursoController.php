<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Curso;
use AppBundle\Entity\ActividadClave;
use AppBundle\Entity\CursoActividadClave;
use AppBundle\Entity\Indicador;
use AppBundle\Entity\CursoIndicador;
use AppBundle\Entity\Modulo;
use AppBundle\Entity\CursoModulo;


class CursoController extends FOSRestController {

  /**
   * @Rest\Get("/curso")
   */
  public function listarAction() {
      $restresult = $this->getDoctrine()->getRepository('AppBundle:Curso')->findAll();
      if ($restresult === null) {
          return new View("no hay nada", Response::HTTP_NOT_FOUND);
      }
      return $restresult;
  }

  /**
   * @Rest\Get("/curso/{id}")
   */
  public function buscarIdAction($id) {
      $singleresult = $this->getDoctrine()->getRepository('AppBundle:Curso')->find($id);
      if ($singleresult === null) {
          return new View("curso no encontrado", Response::HTTP_NOT_FOUND);
      }
      return $singleresult;
  }

  /**
   * @Rest\Post("/curso")
   */
   public function crearAction(Request $request) {

      $em = $this->getDoctrine()->getManager();

      // 1. Creamos el Curso

      $codigo = $request->get('codigo');
      $nombre = $request->get('nombre');
      $objetivo = $request->get('objetivo');
      $horas = $request->get('horas');
      $cantidadParticipantes = $request->get('cantidadParticipantes');

      $curso = new Curso;
      $curso->setCodigo($codigo);
      $curso->setNombre($nombre);
      $curso->setObjetivo($objetivo);
      $curso->setHoras($horas);
      $curso->setCantidadParticipantes($cantidadParticipantes);
      $em->persist($curso);
      $em->flush();

      $cursoId = $curso->getId();

      // 2. Creamos los modulos y los asignamos al Curso recien creado

      $modulos = $request->get('modulos');
      foreach ($modulos as $mod) {

          // Creamos el modulo

          $modulo = new Modulo();
          $modulo->setCodigo($mod["codigo"]);
          $modulo->setNombre($mod["nombre"]);
          $modulo->setObjetivo($mod["objetivo"]);
          $modulo->setHoras($mod["horas"]);
          $em->persist($modulo);
          $em->flush();

          $moduloId = $modulo->getId();

          // Asignamos el modulo recien creado

          $cursoModulo = new CursoModulo();
          $cursoModulo->setCursoId($cursoId);
          $cursoModulo->setModuloId($moduloId);
          $em->persist($cursoModulo);
          $em->flush();

      }
      return new View("El curso y sus modulos fueron creados", Response::HTTP_OK);

  }

  /**
 * @Rest\Post("/curso/")
 */
 public function postAction(Request $request){
    $curso = new Curso;

    $codigo = $request->get('codigo');
    $nombre = $request->get('nombre');
    $objetivo = $request->get('objetivo');
    $horas = $request->get('horas');
    $cantidadParticipantes = $request->get('cantidadParticipantes');

    $curso->setCodigo($codigo);
    $curso->setNombre($nombre);
    $curso->setObjetivo($objetivo);
    $curso->setHoras($horas);
    $curso->setCantidadParticipantes($cantidadParticipantes);

    $em = $this->getDoctrine()->getManager();
    $em->persist($curso);
    $em->flush();
    return new View("Curso agregado correctamente", Response::HTTP_OK);
}



/**
 * @Rest\Post("/curso/actividadClave/{id}")
 */
 public function asignarActividadClaveAction($id, Request $request) {

    $actividadesClave = $request->get('actividadesClave');
    foreach ($actividadesClave as $actividad) {

        // Creamos la actividad clave

        $actividadClave = new ActividadClave();
        $actividadClave->setNombre($actividad);
        $em = $this->getDoctrine()->getManager();
        $em->persist($actividadClave);
        $em->flush();

        $actividadClaveId = $actividadClave->getId();

        // Asignamos la actividad clave recien creada

        $cursoActividadClave = new CursoActividadClave();
        $cursoActividadClave->setCursoId($id);
        $cursoActividadClave->setActividadClaveId($actividadClaveId);
        $em->persist($cursoActividadClave);
        $em->flush();

    }
    return new View("Las actividades clave fueron creadas y asignadas a Curso", Response::HTTP_OK);

}

/**
 * @Rest\Post("/curso/indicador/{id}")
 */
 public function asignarIndicadorAction($id, Request $request) {

    $indicadores = $request->get('indicadores');
    foreach ($indicadores as $indi) {

        // Creamos el indicador

        $indicador = new Indicador();
        $indicador->setNombre($indi);
        $em = $this->getDoctrine()->getManager();
        $em->persist($indicador);
        $em->flush();

        $indicadorId = $indicador->getId();

        // Asignamos el indicador recien creado

        $cursoIndicador = new cursoIndicador();
        $cursoIndicador->setCursoId($id);
        $cursoIndicador->setIndicadorId($indicadorId);
        $em->persist($cursoIndicador);
        $em->flush();

    }
    return new View("Los indicadores fueron creados y asignados a Curso", Response::HTTP_OK);

}

/**
 * @Rest\Post("/curso/modulo/{id}")
 */
 public function asignarModuloAction($id, Request $request) {

    $modulos = $request->get('modulos');
    foreach ($modulos as $mod) {

        // Creamos el modulo

        $modulo = new Modulo();
        $modulo->setCodigo($mod["codigo"]);
        $modulo->setNombre($mod["nombre"]);
        $modulo->setObjetivo($mod["objetivo"]);
        $modulo->setHoras($mod["horas"]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($modulo);
        $em->flush();

        $moduloId = $modulo->getId();

        // Asignamos el modulo recien creado

        $cursoModulo = new CursoModulo();
        $cursoModulo->setCursoId($id);
        $cursoModulo->setModuloId($moduloId);
        $em->persist($cursoModulo);
        $em->flush();

    }
    return new View("Los modulos fueron creados y asignados a Curso", Response::HTTP_OK);

}

/**
  * @Rest\Put("/curso/{id}")
  */
  public function actualizarAction($id, Request $request) {

      $em = $this->getDoctrine()->getManager();

      $codigo = $request->get('codigo');
      $nombre = $request->get('nombre');
      $objetivo = $request->get('objetivo');
      $horas = $request->get('horas');
      $cantidadParticipantes = $request->get('cantidadParticipantes');

      $curso = $this->getDoctrine()->getRepository('AppBundle:Curso')->find($id);
      if (!empty($curso)) {

          // Actualizamos el Curso

          $curso->setCodigo($codigo);
          $curso->setNombre($nombre);
          $curso->setObjetivo($objetivo);
          $curso->setHoras($horas);
          $curso->setCantidadParticipantes($cantidadParticipantes);
          $em->flush();

          // Eliminamos los modulos relacionados originalmente

          $idMod = array();
          $modul = $this->getDoctrine()->getRepository('AppBundle:CursoModulo')->findBy(array('cursoId' => $id));
          if (!empty($modul)) {
              foreach ($modul as $mod) {
                  array_push($idMod, $mod->getModuloId());
                  $em->remove($mod);
                  $em->flush();
              }
          }

          foreach ($idMod as $modId) {
              $mod = $this->getDoctrine()->getRepository('AppBundle:Modulo')->find($modId);
              if (!empty($mod)) {
                  $em->remove($mod);
                  $em->flush();
              }
          }

          // Registramos y asignamos los modulos

          $modulos = $request->get('modulos');
          foreach ($modulos as $mod) {

              // Creamos el modulo

              $modulo = new Modulo();
              $modulo->setCodigo($mod["codigo"]);
              $modulo->setNombre($mod["nombre"]);
              $modulo->setObjetivo($mod["objetivo"]);
              $modulo->setHoras($mod["horas"]);
              $em->persist($modulo);
              $em->flush();

              $moduloId = $modulo->getId();

              // Asignamos el modulo recien creado

              $cursoModulo = new CursoModulo();
              $cursoModulo->setCursoId($id);
              $cursoModulo->setModuloId($moduloId);
              $em->persist($cursoModulo);
              $em->flush();

          }
          return new View("Modificación del Curso y modulos realizada con exito", Response::HTTP_OK);

      }

  }

 /**
 * @Rest\Put("/curso/old/{id}")
 */
 public function putAction($id, Request $request) {
    $curso = new Curso;

    $codigo = $request->get('codigo');
    $nombre = $request->get('nombre');
    $objetivo = $request->get('objetivo');
    $horas = $request->get('horas');
    $cantidadParticipantes = $request->get('cantidadParticipantes');

    $em = $this->getDoctrine()->getManager();

    $curso = $this->getDoctrine()->getRepository('AppBundle:Curso')->find($id);
    if (empty($curso)) {
        return new View("Curso no encontrado", Response::HTTP_NOT_FOUND);
    } else {
        $curso->setCodigo($codigo);
        $curso->setNombre($nombre);
        $curso->setObjetivo($objetivo);
        $curso->setHoras($horas);
        $curso->setCantidadParticipantes($cantidadParticipantes);
        $em->flush();
        return new View("Curso Actualizado Correctamente", Response::HTTP_OK);
    }
 }

 /**
  * @Rest\Delete("/curso/{id}")
  */
 public function borrarAction($id) {
     $curso = new Curso;

     $em = $this->getDoctrine()->getManager();
     $curso = $this->getDoctrine()->getRepository('AppBundle:Curso')->find($id);
     if (empty($curso)) {
         return new View("No se encontro el curso", Response::HTTP_NOT_FOUND);
     } else {
         $em->remove($curso);
         $em->flush();
     }
     return new View("curso borrado Exitosamente!", Response::HTTP_OK);
 }

 /**
  * @Rest\Delete("/curso/modulo/{id}")
  */
  public function borradoConAsignadoAction($id, Request $request) {

      $em = $this->getDoctrine()->getManager();

      $curso = $this->getDoctrine()->getRepository('AppBundle:Curso')->find($id);
      if (!empty($curso)) {

          // Eliminamos los modulos relacionados originalmente

          $idMod = array();
          $modul = $this->getDoctrine()->getRepository('AppBundle:CursoModulo')->findBy(array('cursoId' => $id));
          if (!empty($modul)) {
              foreach ($modul as $mod) {
                  array_push($idMod, $mod->getModuloId());
                  $em->remove($mod);
                  $em->flush();
              }
          }

          foreach ($idMod as $modId) {
              $mod = $this->getDoctrine()->getRepository('AppBundle:Modulo')->find($modId);
              if (!empty($mod)) {
                  $em->remove($mod);
                  $em->flush();
              }
          }

          //eliminamos el curso
              $curso = new Curso;
              $curso = $this->getDoctrine()->getRepository('AppBundle:Curso')->find($id);

              $em->remove($curso);
              $em->flush();
          return new View("Borrado del Curso y sus asignaciones realizado con exito", Response::HTTP_OK);

      }

  }

}
