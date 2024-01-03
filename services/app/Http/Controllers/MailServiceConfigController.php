<?php

namespace App\Http\Controllers;

use App\Models\MailServiceConfig;
use Illuminate\Http\Request;
use Exception;

class MailServiceConfigController extends Controller {

    public function findAll (Request $request){
        try {
            $isSimple = isset($request->isSimple) ? $request->isSimple : 0;
            $multiWhere = array();

            if(!empty($request->get('TipoServicio'))){
                $multiWhere[]  = array ('TipoServicio', $request->get('TipoServicio'));
            }

            if ($isSimple == 1){
                $oData = MailServiceConfig::where($multiWhere)
                ->get();

            }else {
                $oData = MailServiceConfig::where($multiWhere)
                ->paginate(
                    $request->query("Entrada"),
                    "*",
                    "page",
                    $request->query("Pag")
                );
            }

            return response([
                'status'    => true,
                'typemsj'   => 1,
                'message'   => 'success',
                'data'      => $oData,
            ],200);

        }catch (Exception $e){
            return response([
                "status"    => false,
                "message"   => 'Internal_error',
                //'error'     => $e->getMessage(),
            ],500);
        }
    }

    public function findOne (Request $request){
        
        try {

            $dataParams = $request->only('KeyService');
            $validation = $this->getOnlyValidador($dataParams);

            if($validation->fails()) return response([
                "status"  => false,
                "message" => "parametros invalidos",
                "errors"  => $validation->errors(),
                "typemsj" => 2,
            ],400);

            $registro = MailServiceConfig::where('KeyService', $dataParams['KeyService'])->first();

            if (!empty($registro)) {

                return response([
                    "status"  => true,
                    "typemsj" => 1,
                    "message" => 'success',
                    "data"    => $registro,
                ],200);

            } else {
                return response([
                    "status"  => false,
                    "typemsj" => 1,
                    "message" => 'Registro no encontrado.',
                    "data"    => '',
                ],404);
            }

        } catch (Exception $element){
            return response([
                "status"  => false,
                "message" => 'Internal_error',
                // 'error'   => $element->getMessage(),
            ],500);
        }

    }

    public function update (Request $request, int $IdMailServeConfig) {
        
        try {

            $ReqData    = $request->only("TipoServicio","KeyService","Driver","Host","Port","Username","Password","Encryption","Remitente","NameApp","Alias");
            $validation = $this->updateValidador($ReqData);

            if($validation->fails()) return response([
                "message" => "Correctamente",
                "errors"  => $validation->errors(),
            ],400);

            $update = MailServiceConfig::find($IdMailServeConfig);

            if (!empty($update)) {

                $update->Driver     = trim($ReqData['Driver']);
                $update->Host       = trim($ReqData['Host']);
                $update->Port       = trim($ReqData['Port']);
                $update->Username   = trim($ReqData['Username']);
                $update->Password   = trim($ReqData['Password']);
                $update->Encryption = (!empty($ReqData['Encryption'])) ? trim($ReqData['Encryption']) : '';
                $update->Remitente  = $ReqData['Remitente'];
                $update->NameApp    = $ReqData['NameApp'];
                $update->Alias      = $ReqData['Alias'];

                if (!$update->save()) return response([
                    "status"  => false,
                    "message" => "Update_error"
                ], 500);

                return response([
                    "status"  => true,
                    "message" => "Correctamente",
                    "data"    => $update
                ], 200);

            } else {
                return response([
                    "status"  => false,
                    "message" => 'Registro no encontrado',
                ], 400);
            }

        } catch (Exception $element){
            return response([
                "status"  => false,
                "message" => 'Internal_error',
                "error"   => $element->getMessage(),
            ],500);
        }

    }

    public function delete(int $IdMailServeConfig) {

        try {

            $registro = MailServiceConfig::find($IdMailServeConfig);

            if (!empty($registro)) {

                $registro->Host         = '';
                $registro->Port         = '';
                $registro->Username     = '';
                $registro->Password     = '';
                $registro->Encryption   = '';
                $registro->Remitente    = '';
                $registro->NameApp      = '';
                $registro->Destinatario = '';

                if (!$registro->save()) return response([
                    "status"  => false,
                    "message" => "update_error"
                ], 500);

                return response([
                    "status"  => true,
                    "message" => "success",
                ], 200);

            } else {
                return response([
                    "status"  => false,
                    "message" => 'Registro no encontrado',
                ], 400);
            }

        } catch (Exception $element){
            return response([
                "status"  => false,
                "message" => 'Internal_error',
                // 'error'   => $element->getMessage(),
            ],500);
        }

    }

    public function updateValidador($params = []) {
        return validator($params,
            [   
                "Driver"     => "required|string",
                "Host"       => "required|string",
                "Port"       => "required|numeric|min:1",
                "Username"   => "required|email",
                "Password"   => "required|string",
                "Encryption" => "required|string",
                "Remitente"  => "required|email",
                "NameApp"    => "required|string",
                "Alias"      => "required|string",
            ],
            [
                "required" => "El campo debe requerido",
                "numeric"  => "El campo debe ser numerico",
                "string"   => "El campo debe ser de tipo string",
                "email"    => "El campo debe ser un email valido",
            ]
        );
    }

    public function getOnlyValidador($params = []) {
        return validator($params, 
            [
                "KeyService" => "required|string",
            ], 
            [
                "required" =>  "Parametro requerido",
                "string"   =>  "Parametro debe ser de tipo string",
            ]
        );
    }
}
