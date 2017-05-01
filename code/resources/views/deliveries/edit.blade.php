<form class="form-horizontal main-form delivery-editor" method="PUT" action="{{ url('/deliveries/' . $delivery->id) }}">
    <div class="row">
        <div class="col-md-12">
            @include('deliveries.base-edit', ['delivery' => $delivery])
        </div>
    </div>

    @include('commons.formbuttons')
</form>

@stack('postponed')
