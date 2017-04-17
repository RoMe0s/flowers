<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 11.03.17
 * Time: 16:01
 */

namespace App\Http\Controllers;


use App\Http\Requests\Frontend\Feedback\FeedbackRequest;
use Illuminate\Routing\Controller;
use Response;
use App\Models\Set;
use App\Models\Bouquet;
use App\Models\Category;
use File;
use App\Classes\Sitemap;


class ApiController extends Controller
{

    public function help() {
        return abort(404);
    }

    public function marketYML() {

	$sitemap = new Sitemap();
	
        return Response::make($sitemap->getMarketView(), 200)->header('Content-Type', 'text/xml');
    }

    public function feedbackStore(FeedbackRequest $request) {
        $data = $request->all();

        \FlashMessages::add('success', 'Письмо отправлено');

        return redirect()->back();
    }

}
