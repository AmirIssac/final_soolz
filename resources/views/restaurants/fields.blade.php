@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('name', trans("lang.restaurant_name"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_name_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.restaurant_name_help") }}
    </div>
  </div>
</div>

@hasrole('admin')
<!-- Users Field -->
<div class="form-group row ">
    {!! Form::label('users[]', trans("lang.restaurant_users"),['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        {!! Form::select('users[]', $user, $usersSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
        <div class="form-text text-muted">{{ trans("lang.restaurant_users_help") }}</div>
    </div>
</div>
@endhasrole

@hasanyrole('admin|manager')
<!-- Users Field -->
    <div class="form-group row ">
        {!! Form::label('drivers[]', trans("lang.restaurant_drivers"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('drivers[]', $drivers, $driversSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
            <div class="form-text text-muted">{{ trans("lang.restaurant_drivers_help") }}</div>
        </div>
    </div>
<!-- delivery_fee Field -->
    <div class="form-group row ">
        {!! Form::label('delivery_fee', trans("lang.restaurant_delivery_fee"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('delivery_fee', 0,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_delivery_fee_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_delivery_fee_help") }}
            </div>
        </div>
    </div>

<!-- admin_commission Field -->
    <div class="form-group row ">
        {!! Form::label('admin_commission', trans("lang.restaurant_admin_commission"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('admin_commission', 0,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_admin_commission_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_admin_commission_help") }}
            </div>
        </div>
    </div>
@endhasrole

<!-- Phone Field -->
    <div class="form-group row ">
        {!! Form::label('phone', trans("lang.restaurant_phone"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_phone_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_phone_help") }}
            </div>
        </div>
    </div>

<!-- Mobile Field -->
    <div class="form-group row ">
        {!! Form::label('mobile', trans("lang.restaurant_mobile"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('mobile', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_mobile_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_mobile_help") }}
            </div>
        </div>
    </div>

<!-- Address Field -->
<div class="form-group row ">
  {!! Form::label('address', trans("lang.restaurant_address"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('address', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_address_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.restaurant_address_help") }}
    </div>
  </div>
</div>


    <div style="margin-left: 128px">
    <input style="margin-top: 10px;" type="url" name="url" placeholder="أدخل هنا الرابط الكامل لموقعك على الخريطة" class="form-control" id="maplink">
     <button style="margin-top: 10px;" onclick="confirmMapLink();" type="button" class="btn btn-success"> تأكيد الرابط </button>
    {{-- we declare confirmMapLink in script in create.blade.php --}}
    </div>

<!-- Latitude Field -->
    <div style="margin-top: 10px" class="form-group row ">
        {!! Form::label('latitude', trans("lang.restaurant_latitude"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('latitude', null,  ['id'=> 'lat','class' => 'form-control','placeholder'=>  'أدخل خط العرض يدويا في حال عدم توافر رابط']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_latitude_help") }}
            </div>
            
        </div>
    </div>

<!-- Longitude Field -->
    <div class="form-group row ">
        {!! Form::label('longitude', trans("lang.restaurant_longitude"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('longitude', null,  ['id'=> 'long','class' => 'form-control','placeholder'=>  'أدخل خط الطول يدويا في حال عدم توافر رابط']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_longitude_help") }}
            </div>
            <!-- Button trigger modal -->
<button onclick="validateCoordinate(document.getElementById('long').value,document.getElementById('lat').value);" style="margin-top: 10px" type="button" class="btn btn-warning" id="validatebtn">  
    {{-- we declare validateCoordinate in script in create.blade.php --}}
    تحقق من الإحداثيات
  </button>
  
  <style>
    .map {
        margin-top:25px;
        height: 300px;
        width: 850px;
        border: 2px solid #007bff;
        border-radius: 10px;
    }
  </style>
  <div id="map" class="map">
    <div id="popup" class="ol-popup">   <!-- clicable popup -->
        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
        <div id="popup-content"></div>
    </div>
   </div>
  {{--
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">موقع مطعمك على الخريطة</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            edrgerghe
           <div style="z-index: 2" id="map" class="map">
            <div id="popup" class="ol-popup">   <!-- clicable popup -->
                <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                <div id="popup-content"></div>
            </div>
           </div>
           bdfbdrbbttr
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>--}}
  
        </div>
        
    </div> 

<!-- 'Boolean state of user Field' -->
<div class="form-group row ">
    {!! Form::label('enable', trans("lang.enable"),['class' => 'col-3 control-label text-right']) !!}
    <div class="checkbox icheck">
        <label class="col-9 ml-2 form-check-inline">
            {!! Form::hidden('enable', 0) !!}
            {!! Form::checkbox('enable', 1, null) !!}
        </label>
    </div>
</div>
<!-- 'Boolean state of user Field' -->
<div class="form-group row ">
    {!! Form::label('available', trans("lang.available"),['class' => 'col-3 control-label text-right']) !!}
    <div class="checkbox icheck">
        <label class="col-9 ml-2 form-check-inline">
            {!! Form::hidden('available', 0) !!}
            {!! Form::checkbox('available', 1, null) !!}
        </label>
    </div>
</div>



</div>

<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Image Field -->
    <div class="form-group row">
        {!! Form::label('image', trans("lang.restaurant_image"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                <input type="hidden" name="image">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <div class="form-text text-muted w-50">
                {{ trans("lang.restaurant_image_help") }}
            </div>
        </div>
    </div>
    @prepend('scripts')
        <script type="text/javascript">
            var var15671147011688676454ble = '';
            @if(isset($restaurant) && $restaurant->hasMedia('image'))
                var15671147011688676454ble = {
                name: "{!! $restaurant->getFirstMedia('image')->name !!}",
                size: "{!! $restaurant->getFirstMedia('image')->size !!}",
                type: "{!! $restaurant->getFirstMedia('image')->mime_type !!}",
                collection_name: "{!! $restaurant->getFirstMedia('image')->collection_name !!}"};
                    @endif
            var dz_var15671147011688676454ble = $(".dropzone.image").dropzone({
                    url: "{!!url('uploads/store')!!}",
                    addRemoveLinks: true,
                    maxFiles: 1,
                    init: function () {
                        @if(isset($restaurant) && $restaurant->hasMedia('image'))
                        dzInit(this,var15671147011688676454ble,'{!! url($restaurant->getFirstMediaUrl('image','thumb')) !!}')
                        @endif
                    },
                    accept: function(file, done) {
                        dzAccept(file,done,this.element,"{!!config('medialibrary.icons_folder')!!}");
                    },
                    sending: function (file, xhr, formData) {
                        dzSending(this,file,formData,'{!! csrf_token() !!}');
                    },
                    maxfilesexceeded: function (file) {
                        dz_var15671147011688676454ble[0].mockFile = '';
                        dzMaxfile(this,file);
                    },
                    complete: function (file) {
                        dzComplete(this, file, var15671147011688676454ble, dz_var15671147011688676454ble[0].mockFile);
                        dz_var15671147011688676454ble[0].mockFile = file;
                    },
                    removedfile: function (file) {
                        dzRemoveFile(
                            file, var15671147011688676454ble, '{!! url("restaurants/remove-media") !!}',
                            'image', '{!! isset($restaurant) ? $restaurant->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                        );
                    }
                });
            dz_var15671147011688676454ble[0].mockFile = var15671147011688676454ble;
            dropzoneFields['image'] = dz_var15671147011688676454ble;
        </script>
        
@endprepend

<!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('description', trans("lang.restaurant_description"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('description', 'null', ['class' => 'form-control','placeholder'=>
             trans("lang.restaurant_description_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.restaurant_description_help") }}</div>
        </div>
    </div>
<!-- Information Field -->
<div class="form-group row ">
  {!! Form::label('information', trans("lang.restaurant_information"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::textarea('information', 'null', ['class' => 'form-control','placeholder'=>
     trans("lang.restaurant_information_placeholder")  ]) !!}
    <div class="form-text text-muted">{{ trans("lang.restaurant_information_help") }}</div>
  </div>
</div>

</div>
@if($customFields)
<div class="clearfix"></div>
<div class="col-12 custom-field-container">
  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
  {!! $customFields !!}
</div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.restaurant')}}</button>
  <a href="{!! route('restaurants.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
