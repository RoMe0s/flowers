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

class ApiController extends Controller
{

    public function help() {
        return abort(404);
    }

    public function marketYML() {
        $sets = Set::with('category', 'translations')->visible()->get();
        $bouquets = Bouquet::with('translations', 'category')->visible()->get();
        $categories = Category::with('translations')->visible()->get();

        $xml = view('yml', [
            'sets' => $sets,
            'bouquets' => $bouquets,
            'categories' => $categories
        ]);

        return Response::make($xml, 200)->header('Content-Type', 'text/xml');
    }

    public function feedbackStore(FeedbackRequest $request) {
        $data = $request->all();

        \FlashMessages::add('success', 'Письмо отправлено');

        return redirect()->back();
    }

}