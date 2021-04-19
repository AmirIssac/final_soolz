<?php

namespace App\Http\Controllers;

use App\DataTables\ExtraDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateExtraRequest;
use App\Http\Requests\UpdateExtraRequest;
use App\Models\Restaurant;
use App\Repositories\ExtraRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\FoodRepository;
use App\Repositories\RestaurantRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ExtraController extends Controller
{
    /** @var  ExtraRepository */
    private $extraRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var FoodRepository
     */
    private $foodRepository;


    /**
     * @var RestaurantRepository
     */
    private $restaurantRepository;

    public function __construct(ExtraRepository $extraRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo
        , FoodRepository $foodRepo , RestaurantRepository $resRepo)
    {
        parent::__construct();
        $this->extraRepository = $extraRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->foodRepository = $foodRepo;
        

        $this->restaurantRepository = $resRepo;
    }

    /**
     * Display a listing of the Extra.
     *
     * @param ExtraDataTable $extraDataTable
     * @return Response
     */
    public function index(ExtraDataTable $extraDataTable)
    {
        return $extraDataTable->render('extras.index');
    }

    /**
     * Show the form for creating a new Extra.
     *
     * @return Response
     */
    public function create()
    {
        if (auth()->user()->hasRole('admin')){
            $food = $this->foodRepository->pluck('name', 'id');
        }else{
            $food = $this->foodRepository->myFoods()->pluck('name', 'id');
        }


        if(auth()->user()->hasRole('manager')){
        $restaurants = auth()->user()->restaurants;
        $restaurants = $restaurants->pluck('name','id');
        }


        $hasCustomField = in_array($this->extraRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->extraRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('extras.create')->with("customFields", isset($html) ? $html : false)->with("food", $food)->with('restaurants',$restaurants);
    }

    /**
     * Store a newly created Extra in storage.
     *
     * @param CreateExtraRequest $request
     *
     * @return Response
     */
    public function store(CreateExtraRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->extraRepository->model());
        try {
            $extra = $this->extraRepository->create($input);
            // input in pivot table extra_food
            /*$foods = $request->foods;
            $extra->foods()->sync($foods);*/


            $extra->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                foreach ($this->uploadRepository->collectionsNames() as $mediaName => $value) {
                    
                    $mediaItem = $cacheUpload->getMedia($mediaName)->first();
                    if(isset($mediaItem) && $mediaItem) break;
                }
                $mediaItem->copy($extra, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.extra')]));

        //return redirect(route('extras.index'));

        $resid =$request->restaurant_id;
        $res = $this->restaurantRepository->model()::find($resid);
        $extraname = $extra->name;
        $extraid = $extra->id;
        $resname = $res->name;
        $foods = $res->foods;
        $foods = $foods->pluck('name','id');
        return view('extras.add_foods')->with('resname',$resname)->with('extraname',$extraname)->with('foods',$foods)->with('extraid',$extraid);

    }

    public function storeFoodsForExtra(Request $request,$id){
        $extra = $this->extraRepository->model()::find($id);
        $foodsids = $request->foods;
        $extra->foods()->sync($foodsids);
        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.food')]));

       return redirect(route('extras.index'));
    }

    /**
     * Display the specified Extra.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');

            return redirect(route('extras.index'));
        }

        return view('extras.show')->with('extra', $extra);
    }

    /**
     * Show the form for editing the specified Extra.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.extra')]));

            return redirect(route('extras.index'));
        }
        /*if (auth()->user()->hasRole('admin')){
            $food = $this->foodRepository->pluck('name', 'id');
        }else{
            $food = $this->foodRepository->myFoods()->pluck('name', 'id');
        }*/
        $res = $extra->restaurant;
        if(isset($res->foods))
        $foods = $res->foods->pluck('name','id'); // all foods for this restaurant
        else
        $foods=null;
        $foodsSelected = $extra->foods;



        $customFieldsValues = $extra->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->extraRepository->model());
        $hasCustomField = in_array($this->extraRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('extras.edit')->with('extra', $extra)->with("customFields", isset($html) ? $html : false)->with("foods", $foods)->with('foodsSelected',$foodsSelected);
    }

    /**
     * Update the specified Extra in storage.
     *
     * @param int $id
     * @param UpdateExtraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExtraRequest $request)
    {
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');
            return redirect(route('extras.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->extraRepository->model());
        try {
            $extra = $this->extraRepository->update($input, $id);

            $extra->foods()->sync($request->foodsids);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                foreach ($this->uploadRepository->collectionsNames() as $mediaName => $value) {
                    
                    $mediaItem = $cacheUpload->getMedia($mediaName)->first();
                    if(isset($mediaItem) && $mediaItem) break;
                }
                $mediaItem->copy($extra, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $extra->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.extra')]));

        return redirect(route('extras.index'));
    }

    /**
     * Remove the specified Extra from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            Flash::error('Extra not found');

            return redirect(route('extras.index'));
        }

        $this->extraRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.extra')]));

        return redirect(route('extras.index'));
    }

    /**
     * Remove Media of Extra
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $extra = $this->extraRepository->findWithoutFail($input['id']);
        try {
            if ($extra->hasMedia($input['collection'])) {
                $extra->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
