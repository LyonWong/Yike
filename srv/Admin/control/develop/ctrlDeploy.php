<?php


namespace Admin\develop;


use _\config;
use _\servDeploy;
use Core\library\Http;

class ctrlDeploy extends ctrl_
{
    protected $scopeKey = 'develop-deploy';

    public function _DO_()
    {
        $data = $this->data();
        \view::tpl('page', [
            'page' => 'develop/deploy'
        ])->with('data', $data);
    }

    public function _GET_monitor()
    {
        $_version = \input::get('version')->value();
        $try = 10;
        do {
            $data = $this->data();
            if ($data['updated'] = $data['version'] != $_version) {
                $this->apiSuccess($data);
            }
            sleep(5);
        } while($try--);
        $this->apiSuccess($data);

    }

    public function _POST_release()
    {
        $mode = \input::post('mode')->value();
        $token = config::load('boot', 'system', 'gitlab.token');
        $domain = config::load('boot', 'public', 'domain');
        $URL = "$_SERVER[REQUEST_SCHEME]://$domain/-deploy";
        $URL .= "?mode=$mode";
        $this->apiSuccess($URL, false);
        fastcgi_finish_request();
        Http::inst()->setHeader(["X-GITLAB-TOKEN:$token"])->post($URL,null);
    }

    public function _GET_info()
    {
        $cursor = \input::get('cursor')->toInt();
        $info = servDeploy::sole($this->platform)->info($cursor);
        $data = [
            'info' => $info,
            'cursor' => $cursor
        ];
        if ($info === false) {
            $this->apiFailure(self::ERR_UNDEFINED, [json_encode($info)]);
        } else {
            $this->apiSuccess($data);
        }

    }

    protected function data()
    {
        $verfile = PATH_ROOT . '/version';
        $info = file_get_contents($verfile);
        if (preg_match('#commit-(\w+)#', $info, $matches)) {
            $version = $matches[1];
        } else {
            $version = null;
        }
        return [
            'info' => $info,
            'version' => $version,
        ];
    }

}