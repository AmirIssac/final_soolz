<?php

namespace App\Http\Controllers\API;


use App\Models\FoodOrder;
use App\Repositories\FoodOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * Class FoodOrderController
 * @package App\Http\Controllers\API
 */

class FoodOrderAPIController extends Controller
{
    /** @var  FoodOrderRepository */
    private $foodOrderRepository;

    public function __construct(FoodOrderRepository $foodOrderRepo)
    {
        $this->foodOrderRepository = $foodOrderRepo;
    }

    /**
     * Display a listing of the FoodOrder.
     * GET|HEAD /foodOrders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->foodOrderRepository->pushCriteria(new RequestCriteria($request));
            $this->foodOrderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $foodOrders = $this->foodOrderRepository->all();

        return $this->sendResponse($foodOrders->toArray(), 'Food Orders retrieved successfully');
    }

    /**
     * Display the specified FoodOrder.
     * GET|HEAD /foodOrders/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var FoodOrder $foodOrder */
        if (!empty($this->foodOrderRepository)) {
            $foodOrder = $this->foodOrderRepository->findWithoutFail($id);
        }

        if (empty($foodOrder)) {
            return $this->sendError('Food Order not found');
        }

        return $this->sendResponse($foodOrder->toArray(), 'Food Order retrieved successfully');
    }

    public function getFoodOrderById($id,$lang){   //test
        $food = $this->foodOrderRepository->findWithoutFail($id);
        //return response()->json($food); true statement too
        //$current_lang =  LaravelLocalization::getCurrentLocale();
        if($lang == 'en')
        return $this->sendResponse($food->toArray(), 'Food Order retrieved successfully in english');
        else
        return $this->sendResponse($food->toArray(), 'Food Order retrieved successfully in arabic');
    }

    public function changeLangTo($lang){     //test
    /*LaravelLocalization::setLocale('ar');
    App::setLocale('ar');
    App::setLocale('ar');
    Session::put('locale', 'ar');
    LaravelLocalization::setLocale('ar');*/
    App::setlocale($lang);
    return response()->json(App::getLocale());
    }
}
