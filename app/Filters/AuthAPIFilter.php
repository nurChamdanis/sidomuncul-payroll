<?php

namespace App\Filters;

use App\API\Services\AuthenticationService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AuthAPIFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        /** Cek Token Includes or Not */
        $header = $request->getServer('HTTP_AUTHORIZATION');
        if(!$header) return Services::response()
                            ->setJSON([
                                'result' => false,
                                'message' => 'Token Required',
                                'data' => null
                            ])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);

        $token = explode(' ', $header);

        if(!isset($token[1])) return Services::response()
                            ->setJSON([
                                'result' => false,
                                'message' => 'Token Required',
                                'data' => null
                            ])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        
        /** Cek Token Validity */
        $authenticationService = new AuthenticationService();
        if($authenticationService->checkValidityToken($token[1]) === false){
            return Services::response()
                            ->setJSON([
                                'result' => false,
                                'message' => 'Token Invalid',
                                'data' => null
                            ])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        /** Cek Token Expiration */
        if($authenticationService->checkExpirationToken($token[1]) === false){
            return Services::response()
                            ->setJSON([
                                'result' => false,
                                'message' => 'Token Expired',
                                'data' => null
                            ])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}