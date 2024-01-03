<?php

namespace App\Custom;

use Intervention\Image\ImageManagerStatic as Image;
use phpDocumentor\Reflection\File;

class FilesManager
{
    /**
     * @note Metodo de subida de archivos con destino a carpeta public directamente en la raiz
     * @param object $request request de la petición HTTPS.
     * @param string $ruta ruta dentro de la carpeta public.
     * @param string $FieldName Nombre del campo | parametro que recibe el archivo.
     * @param string $oldFile Nombre del archivo viejo que se desea eliminar de la carpeta de archivos.
     * @param string $defaultImg Nombre del archivo por defecto mostrado cuando el usuario no puso ninguno.
     * @return array retorna el nuevo nombre del archivo que se ha guardado en la carpeta [HashName,OrigiName].
     **/
    public static function UploadFilePublic($request, $ruta = '', $FieldName = '', $oldFile = '', $defaultImg = ''){

        //$ruta = str_replace('/','\\',$ruta);

        if(!empty($oldFile)) {
            $oFile = $ruta.$oldFile;
            if (!empty($defaultImg)) {
                if ($oldFile != $defaultImg) {
                    if (file_exists(public_path(($oFile)))) {
                        unlink(public_path($oFile));
                    }
                }

            }else {
                if (file_exists(public_path(($oFile)))) {
                    unlink(public_path($oFile));
                }
            }

        }

        if ($request->hasFile($FieldName)) {
            $file           = $request->file($FieldName);
            $filename       = $file->getClientOriginalName();
            $filename       = pathinfo($filename, PATHINFO_FILENAME);
            $ext            = $file->getClientOriginalExtension();
            $hash           = self::GeneraHash(20);
            $hashName = $hash.'.'.$ext;
            $file->move(public_path($ruta).$hashName);


            if(file_exists(public_path($ruta."/".$hashName))) {

                return [
                    'status'     => true,
                    'HashName'   => $hashName,
                    'OrigiName'  => $filename.'.'.$ext,
                    'message'    => 'success'
                ];

            }else {
                return [
                    'status'    => false,
                    'HashName'  => '',
                    'OrigiName' => '',
                    'message'   => 'Error al guardar archivo.'
                ];
            }

        }else {
            return [
                'status'    => false,
                'HashName'  => '',
                'OrigiName' => '',
                'message'   => 'Archivo no recibido'
            ];
        }
    }


    /**
     * @note Metodo de subida de archivos con destino a carpeta storage/app/public es necesario configurar el storage link
     * @param object $request request de la petición HTTPS.
     * @param string $ruta ruta dentro de la carpeta public.
     * @param string $FieldName Nombre del campo | parametro que recibe el archivo.
     * @param string $oldFile Nombre del archivo viejo que se desea eliminar de la carpeta de archivos.
     * @return array retorna el nuevo nombre del archivo que se ha guardado en la carpeta.
     **/
    public static function UploadFileStorage($request, $ruta = '', $FieldName = '', $oldFile = ''){

        //$ruta = str_replace('/','\\',$ruta);

        if(!empty($oldFile)) {
            $oFile = 'app/public/'.$ruta.$oldFile; // In STORAGE
            if (file_exists(storage_path(($oFile)))) {  //storage_path
                unlink(storage_path($oFile));
            }
        }

        if ($request->hasFile($FieldName)) {
            $file       = $request->file($FieldName);
            $filename   = $file->getClientOriginalName();
            $filename   = pathinfo($filename, PATHINFO_FILENAME);
            $ext        = $file->getClientOriginalExtension();
            $hashName   = self::GeneraHash(20).'.'.$ext;
            $file->move(storage_path('app/public/'.$ruta),$hashName);

            if(file_exists(storage_path("app/public/".$ruta."/".$hashName))){
                return [
                    'status'     => true,
                    'HashName'   => $hashName,
                    'OrigiName'  => $filename.'.'.$ext,
                    'message'    => 'success'
                ];

            }else {
                return [
                    'status'    => false,
                    'HashName'  => '',
                    'OrigiName' => '',
                    'message'   => 'Error al guardar archivo.'
                ];
            }

        }else {
            return [
                'status'    => false,
                'HashName'  => '',
                'OrigiName' => '',
                'message'   => 'Archivo no recibido'
            ];
        }
    }

    public static function UploadMultipleFile($file, $ruta = '' ){
        //$file       = $request->file($FieldName);
        $filename   = $file->getClientOriginalName();
        $filename   = pathinfo($filename, PATHINFO_FILENAME);
        $ext        = $file->getClientOriginalExtension();
        $hashName   = self::GeneraHash(20).'.'.$ext;
        $file->move(public_path($ruta),$hashName);

        if(file_exists(public_path($ruta."/".$hashName))){
            return [
                'status'     => true,
                'HashName'   => $hashName,
                'OrigiName'  => $filename.'.'.$ext,
                'message'    => 'success'
            ];

        }else {
            return [
                'status'    => false,
                'HashName'  => '',
                'OrigiName' => '',
                'message'   => 'Error al guardar archivo.'
            ];
        }
    }

    /**
     * @note Metodo de subida y creacion de miniaturas de imagenes con destino a carpeta public directamente en la raiz
     * @param object $request request de la petición HTTPS.
     * @param string $ruta ruta dentro de la carpeta public.
     * @param string $FieldName Nombre del campo | parametro que recibe el archivo.
     * @param string $oldFile Nombre del archivo viejo que se desea eliminar de la carpeta de archivos.
     * @param int    $width Ancho final de la miniatura, default 200px.
     * @param int    $height Alto final de la miniatura, default 200px.
     * @param int    $quality Calidad con la que se guarda la imagen.
     * @param string $defaultImg Nombre del archivo por defecto mostrado cuando el usuario no puso ninguno.
     * @return array retorna el nuevo nombre del archivo que se ha guardado en la carpeta [thumbnail,origiName].
     **/
    public static function UploadThumbnailImage($request, $ruta = '', $FieldName = '', $width = 200, $height = 200, $quality = 75 , $oldFile = '', $defaultImg = '') {

        //$ruta = str_replace('/','\\',$ruta);

        if(!is_dir(storage_path('/app/public/'.$ruta))){
            mkdir(storage_path('/app/public/'.$ruta),0777);
        }

        if(!empty($oldFile)) {
            $oFile = 'app/public/'.$ruta.$oldFile; // In STORAGE
            if ( file_exists(storage_path($oFile)) ) {
                unlink(storage_path($oFile));
            }

        }

        if ($request->hasFile($FieldName)) {
            $file           = $request->file($FieldName);
            $filename       = $file->getClientOriginalName();
            $filename       = pathinfo($filename, PATHINFO_FILENAME);
            $ext            = $file->getClientOriginalExtension();
            $hash           = self::GeneraHash(20);
            $thumb_hashName = $hash.'_thumb.'.$ext;

            //Image::make($file)->resize($width,$height)->save(storage_path($ruta).$thumb_hashName,$quality);
            Image::make($file)->save( storage_path("app/public/".$ruta."/".$thumb_hashName), $quality);

            if(file_exists(storage_path("app/public/".$ruta."/".$thumb_hashName))) {

                return [
                    'status'     => true,
                    'HashName'   => $thumb_hashName,
                    'OrigiName'  => $filename.'.'.$ext,
                    'message'    => 'success'
                ];

            }else {
                return [
                    'status'    => false,
                    'HashName'  => '',
                    'OrigiName' => '',
                    'message'   => 'Error al guardar archivo.'
                ];
            }

        }else {
            return [
                'status'    => false,
                'HashName'  => '',
                'OrigiName' => '',
                'message'   => 'Archivo no recibido'
            ];
        }
    }

    /**
     * @note Metodo de subida y creacion de multiples miniaturas de imagenes con destino a carpeta public directamente en la raiz
     * @param File   $file archivo proveniente del arreglo de archivos.
     * @param string $ruta ruta dentro de la carpeta public.
     * @param string $oldFile Nombre del archivo viejo que se desea eliminar de la carpeta de archivos.
     * @param int    $width Ancho final de la miniatura, default 200px.
     * @param int    $height Alto final de la miniatura, default 200px.
     * @param int    $quality Calidad con la que se guarda la imagen.
     * @param string $defaultImg Nombre del archivo por defecto mostrado cuando el usuario no puso ninguno.
     * @return array retorna el nuevo nombre del archivo que se ha guardado en la carpeta [thumbnail,originalName].
     *
     **/
    public static function UploadMultiThumbnailImages($file, $ruta = '', $width = 200, $height = 200, $quality = 75, $oldFile = '', $defaultImg = '') {

        //$ruta = str_replace('/','\\',$ruta);

        if(!is_dir(storage_path('/app/public/'.$ruta))){
            mkdir(storage_path('/app/public/'.$ruta),0777);
        }

        if(!empty($oldFile)) {
            $oFile = 'app/public/'.$ruta.$oldFile; // In STORAGE
            if ( file_exists(storage_path($oFile)) ) {
                unlink(storage_path($oFile));
            }
        }

        //if ($file->hasFile($FieldName)) {
        //$file           = $request->file($FieldName);
        $filename       = $file->getClientOriginalName();
        $filename       = pathinfo($filename, PATHINFO_FILENAME);
        $ext            = $file->getClientOriginalExtension();
        $hash           = self::GeneraHash(20);
        $thumb_hashName = $hash.'_thumb.'.$ext;

        //Image::make($file)->resize($width,$height)->save( storage_path("app/public/".$ruta."/".$thumb_hashName), $quality);
        Image::make($file)->save( storage_path("app/public/".$ruta."/".$thumb_hashName), $quality);

        if(file_exists(storage_path("app/public/".$ruta."/".$thumb_hashName))) {

            return [
                'status'        => true,
                'HashName'   => $thumb_hashName,
                'OrigiName'  => $filename.'.'.$ext,
                'message'       => 'success'
            ];

        }else {
            return [
                'status'        => false,
                'HashName'  => '',
                'OrigiName' => '',
                'message'       => 'Error al guardar archivo.'
            ];
        }

        /*}else {
            return [
                'status'    => false,
                'thumbnail'  => '',
                'origiName' => '',
                'message'   => 'Archivo no recibido'
            ];
        }*/
    }

    /**
     * @param int $HashLength largo que se espera que tenga la cadena random generada.
     * @return string retorna una cadena de texto random del largo solicitado sumado a la fecha del servidor.
     *
     * */
    public static function GeneraHash (int $HashLength) {
        $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
        $QuantidadeCaracteres = strlen($Caracteres);
        $QuantidadeCaracteres--;

        $Hash=NULL;
        for($x=1;$x<=$HashLength;$x++){
            $Posicao = rand(0,$QuantidadeCaracteres);
            $Hash .= substr($Caracteres,$Posicao,1);
        }

        return date('Ymdhis').'_'.$Hash;
    }
}
