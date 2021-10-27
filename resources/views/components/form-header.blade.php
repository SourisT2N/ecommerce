<div class="row">
    <div class="col-sm-12 p-0">
       <div class="main-header" style="margin-top: 0px;">
          <h4>{{ $name }}</h4>
          <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
             <li class="breadcrumb-item">
                <a href="{{ route('admin.index') }}">
                   <i class="icofont icofont-home"></i>
                </a>
             </li>
             <li class="breadcrumb-item"><a href="{{ $url }}">{{ $name }}</a>
             </li>
          </ol>
       </div>
    </div>
</div>