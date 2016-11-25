<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Common controller.
 *
 */
class CommonController extends Controller
{
    /**
     * @param $code
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeLocaleAction($code, Request $request)
    {
        $response = new Response();
        $response->headers->setCookie(new Cookie('_locale', $code, $expire = 0, $path = '/', $domain = $this->get('router.request_context')->getHost(), $secure = false, $httpOnly = false));
        $session = $this->get('session');
        $request->setLocale($code);
        $session->set('_locale', $code);
        $session->set('_locale_prefered', new \DateTime());

        return $this->redirect(empty($referer) ? "/" : $referer);
    }
}
