<?php

namespace App\Http\Controllers;

use App\Criteria\Users\ClientsCriteria;
use App\Criteria\Users\DriversCriteria;
use App\Criteria\Users\DriversOfRestaurantCriteria;
use App\DataTables\FoodOrderDataTable;
use App\DataTables\OrderDataTable;
use App\Events\OrderChangedEvent;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Notifications\AssignedOrder;
use App\Notifications\StatusChangedOrder;
use App\Repositories\CustomFieldRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class OrderController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepository;
    /** @var  NotificationRepository */
    private $notificationRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;

    public function __construct(OrderRepository $orderRepo, CustomFieldRepository $customFieldRepo, UserRepository $userRepo
        , OrderStatusRepository $orderStatusRepo, NotificationRepository $notificationRepo, PaymentRepository $paymentRepo)
    {
        parent::__construct();
        $this->orderRepository = $orderRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->userRepository = $userRepo;
        $this->orderStatusRepository = $orderStatusRepo;
        $this->notificationRepository = $notificationRepo;
        $this->paymentRepository = $paymentRepo;
    }

    /**
     * Display a listing of the Order.
     *
     * @param OrderDataTable $orderDataTable
     * @return Response
     */
    public function index(OrderDataTable $orderDataTable)
    {
        return $orderDataTable->render('orders.index');
    }

    /**
     * Show the form for creating a new Order.
     *
     * @return Response
     */
    public function create()
    {
        $user = $this->userRepository->getByCriteria(new ClientsCriteria())->pluck('name', 'id');
        $driver = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');

        $orderStatus = $this->orderStatusRepository->pluck('status', 'id');

        $hasCustomField = in_array($this->orderRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('orders.create')->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("driver", $driver)->with("orderStatus", $orderStatus);
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param CreateOrderRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        try {
            $order = $this->orderRepository->create($input);
            $order->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.order')]));

        return redirect(route('orders.index'));
    }

    /**
     * Display the specified Order.
     *
     * @param int $id
     * @param FoodOrderDataTable $foodOrderDataTable
     *
     * @return Response
     */

    public function show(FoodOrderDataTable $foodOrderDataTable, $id)
    {
        $order = $this->orderRepository->findWithoutFail($id);
        $subtotal = 0;
        $user = $this->userRepository->findWithoutFail($order->user_id);
        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }
        foreach ($order->foodOrders as $foodOrder) {
            $subtotal += $foodOrder->price * $foodOrder->quantity;
        }

        $total = $subtotal + $order['delivery_fee'];
        // $total = $subtotal;
        $total += ($total * ($order['tax'] / 100));
        $foodOrderDataTable->id = $id;
        
        
        $total = $total  - $order['order_discount'];
        // $total = $total  + $order['delivery_fee'];
        $discount = $order['order_discount'];

        return $foodOrderDataTable->render('orders.show', ["order" => $order, "total" => $total,'discount'=>$discount, "subtotal" => $subtotal,'phone'=>$user->phone]);

        // return $foodOrderDataTable->render('orders.show', ["order" => $order, "total" => $total, "subtotal" => $subtotal]);
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {

        $order = $this->orderRepository->findWithoutFail($id);

        $restaurant = $order->foodOrders()->first();
        $restaurant = isset($restaurant) ? $restaurant->food['restaurant_id'] : 0;

        $user = $this->userRepository->getByCriteria(new ClientsCriteria())->pluck('name', 'id');
        $driver = $this->userRepository->getByCriteria(new DriversOfRestaurantCriteria($restaurant))->pluck('name', 'id');
        $driver = $driver->put(null, trans('lang.order_driver_id_placeholder'))->sortKeysDesc();
        $orderStatus = $this->orderStatusRepository->pluck('status', 'id');


        if (empty($order)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.order')]));

            return redirect(route('orders.index'));
        }
        $customFieldsValues = $order->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        $hasCustomField = in_array($this->orderRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('orders.edit')->with('order', $order)->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("driver", $driver)->with("orderStatus", $orderStatus);
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrderRequest $request)
    {
        $oldOrder = $this->orderRepository->findWithoutFail($id);
        if (empty($oldOrder)) {
            Flash::error('Order not found');
            return redirect(route('orders.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        try {
            $order = $this->orderRepository->update($input, $id);

            if (setting('enable_notifications', false)) {
                if ($input['order_status_id'] != $oldOrder->order_status_id) {
                    Notification::send([$order->user], new StatusChangedOrder($order));
                }

                if (isset($input['driver_id']) && ($input['driver_id'] != $oldOrder['driver_id'])) {
                    $driver = $this->userRepository->findWithoutFail($input['driver_id']);
                    if (!empty($driver)) {
                        Notification::send([$driver], new AssignedOrder($order));
                    }
                }
            }

            $pay = $this->paymentRepository->findWithoutFail($order['payment_id']);

            $this->paymentRepository->update([
                "status" => $input['status'],
            ], $order['payment_id']);

            if($input['status'] == 'Paid' && $pay->status != 'Paid') {
                // return $statut;
                $user =  $order->user;
                $value =  \DB::table('app_settings')->select('value')->where('key','order_money_save')->get();
                $user->wallet += $value[0]->value;
                $user->save();
                // return $user = $this->userRepository->findWithoutFail($id);
            }

            // event(new OrderChangedEvent($order));

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $order->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.order')]));

        return redirect(route('orders.index'));
    }

    /**
     * Remove the specified Order from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }

        $this->orderRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.order')]));

        return redirect(route('orders.index'));
    }

    /**
     * Remove Media of Order
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $order = $this->orderRepository->findWithoutFail($input['id']);
        try {
            if ($order->hasMedia($input['collection'])) {
                $order->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function indexSearch(){
        return view('client_orders.index_search');
    }
    public function makeSearch(Request $request){
        $id = $request->order_id;
        $order = $this->orderRepository->model()::find($id);
        if(!$order){
            $noOrderFound = 1;
            return view('client_orders.index_search')->with('noOrderFound',$noOrderFound);
        }
        else{
        $user = $order->user;
        $order_status = $order->orderStatus;
        $payment = $order->payment;
        $restaurant = $order->restaurant;
        return view('client_orders.index_search')->with(['order'=>$order,'user'=>$user,'order_status'=>$order_status,'payment'=>$payment,'restaurant'=>$restaurant]);
        }
    }
}
