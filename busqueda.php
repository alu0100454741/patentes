<?php $a=$_POST[nomb]; ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Proyecto TIO - Buscador de Patentes">
    <meta name="author" content="Equipo DDJE">
    <title>Buscador de Patentes</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/forms.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Fav icons -->
    <link rel="shortcut icon" href="ico/faviconPatent.ico">
    <link rel="icon-precomposed" sizes="144x144" href="ico/patent_icon_144.png">
    <link rel="icon-precomposed" sizes="114x114" href="ico/patent_icon_114.png">
    <link rel="icon-precomposed" sizes="72x72" href="ico/patent_icon_72.png">
    <link rel="icon-precomposed" href="ico/patent_icon_57.png">
  </head>

  <body>
    <!-- Formulario -->
    <div id="top" class="header">
      <div class="vert-text">
        <br>
        <h1>Buscador de Patentes</h1>
        <a name="Volver" type="submit" href="index.html" value="Volver" class="btn btn-lg btn-primary">Volver</a>
        <br>
        <br>
        <form action="busqueda.php" method="post">
          <tr></tr>
          <tr>
          <div class="col-md-6 col-md-offset-3 text-center">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <input name="nomb" type="text" value="<?php echo $a; ?>" />
                <input name="Buscar" type="submit" id="Buscar" value="Buscar" />
                <div id="searchForm" style="display:none;"></div>
                  <select id="restrict" onchange="formSubmit(searchform);return false;"></select>
                  <select id="sort" onchange="formSubmit(searchform);return false;"></select>
                  </td>
              </div>
              <div class="panel-body">
    <!-- /Formulario -->

<!-- /Codigo PHP -->
<?php 
include('google.php');

error_reporting(E_ALL ^ E_DEPRECATED);
//funcion para extraer codigo html entre 2 límites

function cortar($beg, $end, $str) {
   $a = explode($beg, $str, 2);
   $b = explode($end, $a[1]);
   return $beg . $b[0] . $end;
}

//direcciones url para realizar las busquedas
$url1="http://worldwide.espacenet.com/searchResults?compact=false&AB=";
$url2="http://www.oepm.es/es/signos_distintivos/resultados.html?denominacion=Contenga&texto=";
$url3="http://ep.espacenet.com/searchResults?compact=false&AB=";
$url3end="&ST=quick&locale=en_EP&submitted=true&DB=ep.espacenet.com";

//Definimos las urls finales con la cadena a buscar
$f1=$url1.$a;
$f2=$url2.$a;
$f3=$url3.$a.$url3end;

//url de la pagina para arreglar los enlaces relativos
$oldSetting = libxml_use_internal_errors( true );
libxml_clear_errors();
$html = new DOMDocument();
$html->loadHtmlFile("tmp.php");
$xpath = new DOMXPath( $html );
//extraemos todos los enlaces para poder corregirlos
$links = $xpath->query( '//a[starts-with(@href,"/es/signos_distintivos/detalle.html?")] | //td ');
$tabladef='<table width="700" border="1"> <tr> <td>';
$tablacont='</td> </tr> <tr> <td>';
$tablaend='</td> </tr> </table>';
$estilo='<style type="text/css"> body,td,th { font-family: "Trebuchet MS", Helvetica, sans-serif; } </style>';
//----Parte de patentes europeas----

$urleur = file_get_contents($f3);
//echo $urleur;
//extraemos la tabla donde se encuentra el contenido que nos interesa del html obtenido
$urleur= cortar('<table class="application">', '</table>', $urleur);
//eliminamos los checkbox innecesarios
$limpiar_checkbox='<input type="checkbox"[^>]*>';
$urleur = eregi_replace($limpiar_checkbox,'',$urleur);
//arreglamos las url relativas
$url_repair='<a  href="/publicationDetails/[^>]*biblio';
$urleur = eregi_replace($url_repair,'<a  href="http://worldwide.espacenet.com/publicationDetails/biblio',$urleur);
//añadimos el estilo de la fuente
$estilo='<style type="text/css"> body,td,th { font-family: "Trebuchet MS", Helvetica, sans-serif; } </style>';
//Escribimos todos los resultados a fichero y con su estilo, cabecera con la codificacion
$ref = '<p>'.'<a href='.$f3.' title="Enlace" target="new">Mas informacion en la pagina original</a>'.'</p>';

echo $estilo;
echo $tabladef;
echo $urleur;
echo $tablacont;
echo $tablaend;
echo $ref;
?>
</p>
   </body>
</html>

