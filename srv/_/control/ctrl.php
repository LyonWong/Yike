<?php


namespace _;

use Core\unitDoAction;
use Core\unitFile;

class ctrl extends ctrlSess
{
    use unitDoAction;
    use unitFile;

    public function runBefore()
    {
        $callbackURI = \input::get('callbackURI', '/')->value();
        $cookieToken = \input::get('cookieToken')->value();
        if ($cookieToken) {
            self::setCookie(self::SESS_COOKIE, $cookieToken);
            setcookie(self::SESS_COOKIE, '', strtotime('-1 seconds'), '/', '', null, true);
            list($usn) = explode('-', $cookieToken);
            setcookie('usn', $usn, strtotime('+1 year'), '/');
            header("Location: " . $callbackURI);
            return true;
        }
        return true;
    }

    public function _DO_()
    {
//        echo 'building...';

        /*
        $studentUrl = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');
        $teacherUrl = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Teacher');
        \view::tpl('index', [
                'info' => \config::load('info', 'basic'),
                'studentUrl' => $studentUrl,
                'teacherUrl' => $teacherUrl
            ]
        );
        */
        $this->_DO_index();
    }

    public function _DO_ok()
    {
        echo 'ok';
    }

    public function _DO_index()
    {
        $teacherUrl = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Teacher');
        $lessonList = servLessonHub::sole($this->platform)->latest('', '--', 8);
        $blogList = servBlog::sole($this->platform)->slice('_', '--', 8);
        \view::tpl('-index', [
            'info' => \config::load('info', 'basic'),
            'lesson_url' => '/lesson/',
            'teacher_url' => $teacherUrl,
            'blog_url' => '/blog',
            'lessonList' => $lessonList,
            'blogList' => $blogList,
        ]);
        $_SERVER['data'] = $blogList;
    }

    public function _DO_version()
    {
        $fileVersion = PATH_ROOT . '/version';
        if (is_file($fileVersion)) {
            echo file_get_contents($fileVersion);
        } else {
            echo "No version file.";
        }
    }

    public function _DO_deploy()
    {
        $token = \config::load('boot', 'system', 'gitlab.token');
//        \output::debug('deploy', $_SERVER, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        if ($_SERVER['HTTP_X_GITLAB_TOKEN'] != $token) {
            header("HTTP/1.1 400 Error");
            exit("Illegal token");
        } else {
            echo "deploying";
            fastcgi_finish_request();
        }
        $cmd = PATH_ROOT . '/cmd/deploy';
        switch ($_GET['mode'] ?? null) {
            case 'build':
                $cmd .= ' -m build';
                break;
            case 'wiki':
                $cmd .= ' -m wiki';
                break;
            default:
                break;
        }
        $log = config::load('boot', 'path', 'runtime') . '/' . servDeploy::FILE;
        $cmd .= " >> $log 2>&1";
        exec($cmd);
        sleep(3);
        rename($log, $log . '.done');
    }

    public function _DO_link()
    {
        $path = \input::get('path', '/')->value();
        $hash = \input::get('hash')->value();
        $path = urlencode($path);
        $hash = urlencode($hash);
        $this->httpLocation("/sess-link?path=$path&hash=$hash");
    }

    public function _DO_redeem($sn, $force = 0)
    {
        $domain = config::load('boot', 'public', 'domain', null, 'Student');
        $url = $_SERVER['REQUEST_SCHEME'] . "://$domain/promote-redeem?sn=$sn&force=$force";
        $this->httpLocation($url);
    }


}
