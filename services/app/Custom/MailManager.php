<?php


namespace App\Custom;
use App\Models\MailServiceConfig;
use Illuminate\Support\Facades\Config;

class MailManager {
    #Admin
    public static function getAdminSystemURL() {
        return 'http://127.0.0.1:8080/';
    }

    public static function SetConfig($KeyService = '') {
        $MailConfig = MailServiceConfig::query()->where('KeyService', $KeyService)
            ->where('TipoServicio', 1)
            ->first();

        if(!empty($MailConfig->Host)) {
            Config::set('mail.driver',             $MailConfig->Driver);
            Config::set('mail.host',               $MailConfig->Host);
            Config::set('mail.port',               $MailConfig->Port);
            Config::set('mail.username',           $MailConfig->Username);
            Config::set('mail.password',           $MailConfig->Password);
            Config::set('mail.encryption',         $MailConfig->Encryption);
            Config::set('mail.from',               ['address' => $MailConfig->Remitente, 'name' => $MailConfig->NameApp]);

            return true;

        }else {
            return false;
        }
    }

    public static function getAddressee($KeyService = '') {
        $MailConfig = MailServiceConfig::where('KeyService', $KeyService)
            ->where('TipoServicio', 2)
            ->first();

        if (!empty($MailConfig) && !empty($MailConfig->Destinatario) ) {
            return [
                "status" => true,
                "data"   => $MailConfig->Destinatario
            ];
        }else {
            return [
                "status" => false,
                "data"   => '',
                "error"  => 'No existe destinatario o el registro, verifique informacion'
            ];
        }

    }

    public static function getNameApp() {
        $MailConfig = MailServiceConfig::where('KeyService', 'PROVIDER')
            ->where('TipoServicio', 1)
            ->first();

        return $MailConfig->NameApp;
    }

}
