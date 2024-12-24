<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    protected $blockAction = array("create", "edit", "delete", "approve", "req_change");

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
        $session = session();
        $uri     = service('uri');
       
        // set user_group and company id from session
        $UgroupID     = ($session->get(S_USER_GROUP_ID)) ? $session->get(S_USER_GROUP_ID) : 0;
        $CanAccessApp = ($session->get(S_CAN_ACCESS_PAYROLL_APP)) ? $session->get(S_CAN_ACCESS_PAYROLL_APP) : 0;
        $CID          = ($session->get(S_COMPANY_ID)) ? $session->get(S_COMPANY_ID) : 0;
        $isExpired    = ($session->get(S_IS_EXPIRED)) ? $session->get(S_IS_EXPIRED) : 0;
        $AffiliateID  = ($session->get(S_AFFILIATE_ID)) ? $session->get(S_AFFILIATE_ID) : 0;
        $page         = ($uri->setSilent()->getSegment(1)) ? $uri->setSilent()->getSegment(1) : "";
        $action       = ($uri->setSilent()->getSegment(2)) ? $uri->setSilent()->getSegment(2) : "";

        if ($isExpired == 1){ // jika sudah kadaluarsa masa percobaannya, check actionnya
            if (in_array($action, $this->blockAction)){
                return redirect()->to($session->get(S_DEFAULT_LANDING));
            }
        }
        
        if (($CanAccessApp == null || $CanAccessApp == 0) && ($UgroupID == null || $UgroupID == 0)) {
            $session->setFlashdata('redirect_uri', current_url());
            return redirect()->to('login');
        }

        if (($CID == null || $CID == 0) && ($AffiliateID == null || $AffiliateID == 0)) {
            $session->setFlashdata('redirect_uri', current_url());
            return redirect()->to('login');
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