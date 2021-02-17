<?php
   /**
   * Establece la conexión con la BD 
   * @access public
   * @return null Si hay algún error.
   */
   function conexion(){
      $con = new mysqli('localhost', 'mst', '1234', 'libros');
      
      if($con->connect_error){
         return null;
      }else{
         if(!$con->set_charset('utf8')){
            return null;
         }
         return $con;
      }
   }
  
   /**
   * Realiza una consulta en la BD del autor cuyo nombre coincida con lo que se pase por paremetro.
   * @access public
   * @param string $s para la busqueda de la información
   * @return null Si hay algún error.
   * @return array $lista_autores Si ha obtenido datos.
   */
   function get_autor($s){
      //Esta información se cargará de la base de datos
      if(conexion() !== null){
         $con = conexion();
         $sql = "SELECT nombre, apellidos, id FROM autor WHERE nombre LIKE '%$s%'";
         if($result = $con->query($sql)){
            $lista_autores[] = '';

            while($row = $result->fetch_assoc()){
               $lista_autores[] = $row;  
            }
            
            
            $result->free();
            $con->close();
            return $lista_autores;
         }   
      }else{
         return null;
      }  
   }
   
   /**
   * Realiza una consulta de todos los libros en la BD que coincidan con el id_autor que se pase por parámetro.
   * @access public
   * @param string id de autor para buscar los libros de ese autor en la tabla libros
   * @return null Si hay algún error.
   * @return array $lista_libros Si ha obtenido datos.
   */
   function get_librosDeAutor($idautor){
      //Esta información se cargará de la base de datos
      if((conexion() !== null) ){
         $con = conexion();
         $sql = "SELECT titulo, f_publicacion FROM libro WHERE id_autor = '$idautor'";
         if($result = $con->query($sql)){
            $lista_libros[] = '';

            while($row = $result->fetch_assoc()){
               $lista_libros[] = $row;  
            }
            

            $result->free();
            $con->close();
            return $lista_libros;
         }   
      }else{
         return null;
      }  
   }   
   if(isset($_GET['s']) && $_GET['s'] != ''){    //volvemos a validar que el input no está en blanco
      $sugerencias = get_autor($_GET['s']);
      if(count($sugerencias) > 1){      //si hay datos sobre el autor...
         $idautor = $sugerencias[1]['id'];  //sacamos el id del autor para usarlo en la siguiente función
         $sugerencias[] = get_librosDeAutor($idautor);
      }
      
      exit(json_encode($sugerencias));   
   }
   
?>
