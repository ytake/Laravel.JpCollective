<?php
namespace App\Http\Controllers;

use Collective\Annotations\Routing\Annotations\Annotations\Get;

/**
 * Class IndexController
 * @package App\Http\Controllers
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 */
class IndexController extends Controller
{

    /**
     * @Get("/", as="index")
     */
    public function index()
    {

    }
}