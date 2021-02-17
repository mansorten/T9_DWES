/**
 * @var element input id = text
 * @var element span id = sugerencias
 * @var element label id = label_sub
 *  
 */ 
 
const texto = document.getElementById('texto');
const sugerencias = document.getElementById('sugerencias');
const label_sug = document.getElementById('label_sug');

/**
 * evento soltar la tecla
 * @access public
 * @param event keyup
 * @param function muestra coincidencias con los libros de la bd usando ajax en función de las teclas pulsadas
 */
texto.addEventListener('keyup', function(){
   
   if(this.value == ''){                         //si el input está en blanco
      sugerencias.textContent = '';              //limpiamos las sugerencias
      label_sug.setAttribute('class', 'show');   //activamos la el atriburo show 
   }else{
      let xhr = new XMLHttpRequest;              //llamamos al objeto 
      //abrimos la comunicación pasando por get el valor de busqueda en la bd
      xhr.open('GET', "http://localhost/T9_DWES/script.php?s="+this.value);  
      /* capturamos el evento 'load' a través del cual nos aseguramos que la respuesta optenida es la correcta. Con la nueva actualización de XMLHttpRequest se ha simplificado mucho esta parte, antes era necesario capturar el evento a través de readystatechange y luego mediante un condicional asegurarse que la respuesta era la correcta con los valores: readyState == 4 y status == 200.
      */
      xhr.addEventListener('load', function(){
         sugerencias.textContent = '';           //limpiamos las sugerencias para que no se acumule la imformación
         const resultado = JSON.parse(xhr.responseText);        //pasamos json a un array
         /* los datos estarán en un array, en la posición 1 estarán los datos del autor en un objeto, y en la posicion 2 estará un array con los datos de todos los libros del autor */
         if(resultado.length > 1){  //si hay coincidencia con un autor...
            sugerencias.innerHTML = `<b>${resultado[1].nombre} ${resultado[1].apellidos}</b></br>`;
            for (let libro of resultado[2]){  //iteramos el array de la posicion 2 que son los libros
                  if(libro != ''){
                     label_sug.removeAttribute('class');          //eliminamos el atributo class=show
                     sugerencias.innerHTML += libro.titulo + ' ' + libro.f_publicacion + '</br>';  //mostramos la imformación de los libros
                  }
            }       

         }else{
            sugerencias.textContent = 'No hay coincidencias.'; 
         }
         
         
      })
      xhr.send();      //se envia la petición
   }
   
})