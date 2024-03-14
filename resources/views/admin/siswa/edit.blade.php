@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.siswa.actions.edit', ['name' => $siswa->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <siswa-form
                :action="'{{ $siswa->resource_url }}'"
                :data="{{ $siswa->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.siswa.actions.edit', ['name' => $siswa->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.siswa.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </siswa-form>

        </div>
    
</div>

@endsection