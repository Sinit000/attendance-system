@if (auth()->user()->isAdmin())
<div class="form-button-action">
    <a href="{{ route('admin.attedance.show', $id) }}"  class="btn btn-sm btn-icon btn-primary" title="Detail">
        <i class="fa fa-eye"></i>
    </a>
</div>
@endif

