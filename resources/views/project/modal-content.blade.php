{{-- Большое количество --}}

{{-- Модальное окно для архивирование проекта --}}
<div class="modal fade archive-all-modal" id="all-archive-project" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="all-archive-project-form">
                @csrf
                <div class="modal-header">
                    <div class="form-title">
                        Архивация проекта
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">
                    <div class="modal-form__content--archive text-center">
                        Вы уверены, что хотите перенести проекты <span class="archive__name"></span> в
                        архив?
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 px-2">
                            <button type="submit" class="cancel-btn w-100 mt-4" data-dismiss="modal" aria-label="Close">
                                {{ __('Отменить') }}
                            </button>
                        </div>
                        <div class="col-lg-6 px-2">
                            <button type="submit" id="all-archive-project-btn"
                                class="main-btn archive-btn modal-form__content--archive--btn w-100 mt-4">
                                {{ __('В архив') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- //// --}}


{{-- Модальное окно для Удаление проекта --}}
<div class="modal fade" id="all-delete-project" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="DELETE" id="all-archive-project-form">
                @csrf
                <div class="modal-header">
                    <div class="form-title">
                        Удаление проекта
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">
                    <div class="modal-form__content--archive text-center">
                        Вы уверены, что хотите удалить проект <span class="archive__name">“Битрейд”</span> ?
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 px-2">
                            <button type="submit" class="cancel-btn w-100 mt-4" data-dismiss="modal" aria-label="Close">
                                {{ __('Отменить') }}
                            </button>
                        </div>
                        <div class="col-lg-6 px-2">
                            <button type="submit" id="all-delete-project-btn"
                                class="delete-btn archive-btn modal-form__content--archive--btn w-100 mt-4">
                                {{ __('Удалить') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- //// --}}


{{-- //// --}}



{{-- По одному проекту --}}

{{-- Модальное окно для добавление проектов --}}
<div class="modal fade" id="addproject" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('project.store') }}">
                @csrf
                <div class="modal-header">
                    <div class="form-title">
                        Добавление проекта
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">
                    <div class="form-group row">
                        <div class="form__project-name from__icon w-100 position-relative d-flex align-items-center">
                            <input id="name" type="text" class="form-control form__user " name="name" required autofocus
                                placeholder="Название проекта">
                            @error('name')

                                <strong>{{ $message }}</strong>

                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form__site from__icon w-100 position-relative  d-flex align-items-center">

                            <input id="url" type="text" class="form-control @error('url') is-invalid @enderror"
                                name="url" required placeholder="Сайт">

                            @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="w-100">
                            <button type="submit" class="main-btn w-100 mt-4    ">
                                {{ __('Добавить проект') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- //// --}}

{{-- Модальное окно для редактирование проектов --}}
<div class="modal fade editmodal" id="editproject" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div class="form-title">
                        Редактирование проекта
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">
                    <div class="form-group row">
                        <div class="form__project-name from__icon w-100 position-relative d-flex align-items-center">
                            <input id="name" type="text" class="form-control form__user edit__name" name="name"
                                value="{{ $project->name }}" required autofocus placeholder="Название проекта">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form__site from__icon w-100 position-relative  d-flex align-items-center">

                            <input id="url" type="text"
                                class="form-control edit__url @error('url') is-invalid @enderror" name="url" required
                                placeholder="Сайт" value="{{ $project->url }}">

                            @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="w-100">
                            <button type="submit" class="main-btn w-100 mt-4    ">
                                {{ __('Изменить проект') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- //// --}}

{{-- Модальное окно для редактирование проектов --}}
<div class="modal fade deletemodal" id="deleteproject" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <div class="form-title">
                        Удаление проекта
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">
                    <div class="modal-form__content--delete text-center">
                        Вы уверены, что хотите удалить проект <span class="delete__name">“Битрейд”</span>?
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 px-2">
                            <button type="submit" class="cancel-btn w-100 mt-4" data-dismiss="modal" aria-label="Close">
                                {{ __('Отменить') }}
                            </button>
                        </div>
                        <div class="col-lg-6 px-2">
                            <button type="submit" class="delete-btn w-100 mt-4">
                                {{ __('Удалить') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- //// --}}

{{-- Модальное окно для архивирование проекта --}}
<div class="modal fade archivemodal" id="archiveproject" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="GET" action="">
                @csrf
                <div class="modal-header">
                    <div class="form-title">
                        Архивация проекта
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">
                    <div class="modal-form__content--archive text-center">
                        Вы уверены, что хотите перенести проект <span class="archive__name">“Битрейд”</span> в
                        архив?
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 px-2">
                            <button type="submit" class="cancel-btn w-100 mt-4" data-dismiss="modal" aria-label="Close">
                                {{ __('Отменить') }}
                            </button>
                        </div>
                        <div class="col-lg-6 px-2">
                            <button type="submit"
                                class="main-btn archive-btn modal-form__content--archive--btn w-100 mt-4">
                                {{ __('В архив') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- //// --}}
