{{-- Модальное окно для Добавление виджета --}}
<div class="modal fade" id="summary-add-new-widget" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="summary-add-new-widget-form" action="{{ route('project.brief.store') }}">
                @csrf
                <div class="modal-header">
                    <div class="form-title">
                        Добавление виджета
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">

                    <div class="modal-form__content--summary-add">
                        <select class="select w-100" id="modal-type-widget" name="summary_type_widget">
                            <option disabled selected>Тип виджета</option>
                            <option value="Средняя позиция">Средняя позиция</option>
                            <option value="Топы">Топы</option>
                            <option value="Диаграмма">Диаграмма</option>
                        </select>
                    </div>

                    <div class="modal-form__content--summary-top-checkbox mt-4">
                        <p class="summary__top-title"> Топы</p>
                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" id="summary__checkbox-top-all">
                            <label for="summary__checkbox-top-all">Выбрать все</label>
                        </div>
                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-3">
                            <label for="summary__checkbox-top-3">Топ-3</label>
                        </div>

                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-10">
                            <label for="summary__checkbox-top-10">Топ-10</label>
                        </div>


                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-30">
                            <label for="summary__checkbox-top-30">Топ-30</label>
                        </div>


                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-50">
                            <label for="summary__checkbox-top-50">Топ-50</label>
                        </div>
                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-100">
                            <label for="summary__checkbox-top-100">Топ-100</label>
                        </div>
                    </div>

                    <div class="modal-form__content--summary-seacrh mt-4">
                        <select class="select w-100" name="summary_search_engine">
                            <option disabled selected>Поисковая система</option>
                            <option value="google">Гугл</option>
                            <option value="yandex">Яндекс</option>
                        </select>
                    </div>




                    <div class="modal-form__content--summary-region mt-4">
                        <select class="select w-100" name="summary_region_id">
                            <option disabled selected>Регион</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->region->id }}"> {{ $region->region->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="modal-form__content--summary-date mt-4">
                        <input type="text" class="w-100" name="summary_date" placeholder="Срок отображения">
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12 px-2">
                            <button type="submit" class="main-btn modal-form__content--archive--btn w-100 mt-4">
                                {{ __('Добавить виджет') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- //// --}}


{{-- Модальное окно для Удаление виджета --}}
<div class="modal fade" id="summary-add-delete-widget" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="summary-add-new-widget-form" action="{{ route('project.brief.store') }}">
                @csrf
                <div class="modal-header">
                    <div class="form-title">
                        Удаление виджета
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">
                    <div class="modal-form__content--archive text-center">
                        Вы уверены, что хотите удалить виджет “Санкт-Перебург, ср. позиция, 1 год”?
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


{{-- Модальное окно для редактирование виджета --}}
<div class="modal fade" id="summary-add-edit-widget" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="summary-add-new-widget-form" action="{{ route('project.brief.store') }}">
                @csrf
                <div class="modal-header">
                    <div class="form-title">
                        Редактирование виджета
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('/public/images/home/close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-form__content">

                    <div class="modal-form__content--summary-add">
                        <select class="select w-100" id="modal-type-widget" name="summary_type_widget">
                            <option disabled selected>Тип виджета</option>
                            <option value="Средняя позиция">Средняя позиция</option>
                            <option value="Топы">Топы</option>
                            <option value="Диаграмма">Диаграмма</option>
                        </select>
                    </div>

                    <div class="modal-form__content--summary-top-checkbox mt-4">
                        <p class="summary__top-title"> Топы</p>
                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" id="summary__checkbox-top-all">
                            <label for="summary__checkbox-top-all">Выбрать все</label>
                        </div>
                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-3">
                            <label for="summary__checkbox-top-3">Топ-3</label>
                        </div>

                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-10">
                            <label for="summary__checkbox-top-10">Топ-10</label>
                        </div>


                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-30">
                            <label for="summary__checkbox-top-30">Топ-30</label>
                        </div>


                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-50">
                            <label for="summary__checkbox-top-50">Топ-50</label>
                        </div>
                        <div class="summary__checkbox-top-position">
                            <input type="checkbox" class="summary__check" id="summary__checkbox-top-100">
                            <label for="summary__checkbox-top-100">Топ-100</label>
                        </div>
                    </div>

                    <div class="modal-form__content--summary-seacrh mt-4">
                        <select class="select w-100" name="summary_search_engine">
                            <option disabled selected>Поисковая система</option>
                            <option value="google">Гугл</option>
                            <option value="yandex">Яндекс</option>
                        </select>
                    </div>




                    <div class="modal-form__content--summary-region mt-4">
                        <select class="select w-100" name="summary_region_id">
                            <option disabled selected>Регион</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->region->id }}"> {{ $region->region->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="modal-form__content--summary-date mt-4">
                        <input type="text" class="w-100" name="summary_date" placeholder="Срок отображения">
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12 px-2">
                            <button type="submit" class="main-btn modal-form__content--archive--btn w-100 mt-4">
                                {{ __('Изменить виджет') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- //// --}}
