<?php

namespace Core;

trait unitAjax
{
    public function ajaxResponse($code, $message, $data, $halt=true)
    {
        $response = [
            'error' => $code,
            'message' => $message,
            'data' => $data
        ];
        header("Content-Type: application/json");
        echo json_encode($response);
        if ($halt) {
            exit;
        }
    }

    public function ajaxSuccess($data=null)
    {
        $this->ajaxResponse(0, 'success', $data);
    }

    public function ajaxFailure($data=null)
    {
        $this->ajaxResponse(1, 'failure', $data);
    }
}
