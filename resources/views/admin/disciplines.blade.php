@extends('layouts.manager')
@section('title', 'Дисциплины')
@section('javascript')
    <script src="{{ URL::asset('js/min/manager-disciplines.js')}}"></script>
@endsection

@section('content')
<div class="content">
    <div class="items">
        <div class="items-head">
            <h1>Администрирование дисциплин</h1>
            <!-- ko if: $root.user.role() === role.admin.name -->
            <label class="adder" data-bind="click: $root.actions.discipline.start.add">Добавить</label>
            <!-- /ko -->
        </div>
        <!-- ko if: $root.mode() === state.create-->
        <div class="details" data-bind="template: {name: 'edit-mode', data:  $root.current.discipline}"></div>
        <!-- /ko -->
        <div class="items-body">
            <!-- ko foreach: $root.current.disciplines -->
            <div class="item" data-bind="click: $root.actions.discipline.show, css: {'current': $root.current.discipline().id() === id()}">
                <span class="fa tag float-right" data-bind="click: $root.actions.discipline.move" title="Перейти к тестам">&#xf022;</span>
                <span data-bind="text: name"></span>
            </div>
            <!-- ko if: $root.mode() !== state.none && $data.id() === $root.current.discipline().id()-->

                <!-- ko if: $root.mode() === state.info || $root.mode() === state.remove -->
                <div class="details" data-bind="template: {name: 'info-mode', data: $data}"></div>
                <!-- /ko -->

                <!-- ko if: $root.mode() === state.update-->
                <div class="details" data-bind="template: {name: 'edit-mode', data: $data}"></div>
                <!-- /ko -->

            <div class="details discipline">
                <div class="details-row">
                    <table class="werewolf themes">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Темы</th>
                            <th>Вопросы</th>
                            <th>Время</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- ko if: $root.current.theme().mode() !== state.create-->
                        <tr class="adder-row" class="width-100p">
                            <td colspan="5" data-bind="click: $root.actions.theme.start.add">
                                <span class="fa">&#xf067;</span>&nbsp;Добавить тему
                            </td>
                        </tr>
                        <!-- /ko -->
                        <!-- ko if: $root.current.theme().mode() === state.create -->
                        <tr class="input-row">
                            <td colspan="4" class="width-100p">
                                <input type="text" placeholder="Название темы"
                                       data-bind="value: $root.current.theme().name,
                                   event: {keyup: $root.events.theme}"/>
                            </td>
                            <td class="action-holder">
                                <button data-bind="click: $root.actions.theme.end.add" class="fa approve mini">&#xf00c;</button>
                                <button data-bind="click: $root.actions.theme.cancel" class="fa remove mini">&#xf00d;</button>
                            </td>
                        </tr>
                        <!-- /ko -->
                        <!-- ko foreach: $root.current.themes-->
                        <tr data-bind="click: $root.actions.theme.move">
                            <td data-bind="text: $index()+1"></td>
                            <td data-bind="text: name" class="width-100p"><a data-bind="text: name, click: $root.actions.theme.move"></a></td>
                            <td data-bind="text: questionsCount" class="text-right"></td>
                            <td data-bind="text: totalTimeInSeconds" class="text-right"></td>
                            <td class="action-holder">
                                <button data-bind="click: $root.actions.theme.start.remove" class="fa remove mini actions">&#xf014;</button>
                            </td>
                        </tr>
                        <!-- /ko -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /ko -->
            <!-- /ko -->
        </div>
        @include('shared.pagination')
    </div>
    <div class="filter">
        <div class="filter-block">
            <label class="title">Наименование/аббревиатура&nbsp;дисциплины</label>
            <input type="text" data-bind="value: $root.filter.discipline, valueUpdate: 'keyup'" placeholder="Наименование/аббревиатура">
        </div>
        <div class="filter-block">
            <label class="title">Направление</label>
            <select data-bind="options: $root.multiselect.data,
                       optionsText: 'fullname',
                       value: $root.filter.profile,
                       optionsCaption: 'Выберите профиль'"></select>
        </div>
        <div class="filter-block">
            <span class="clear" data-bind="click: $root.filter.clear">Очистить</span>
        </div>
    </div>
</div>

<div class="g-hidden">
    <div class="box-modal removal-modal" id="remove-theme-modal">
        <div class="layer zero-margin width-auto">
            <div class="layer-head">
                <h3>Удалить выбранную тему?</h3>
            </div>
            <div class="layer-body">
                <div class="details-row float-buttons">
                    <button class="cancel arcticmodal-close" data-bind="click: $root.actions.theme.cancel">Отмена</button>
                    <button class="remove arcticmodal-close" data-bind="click: $root.actions.theme.end.remove">Удалить</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ko if: $root.user.role() === role.admin.name -->
<div class="g-hidden">
    <div class="box-modal removal-modal" id="remove-discipline-modal">
        <div class="layer zero-margin width-auto">
            <div class="layer-head">
                <h3>Удалить выбранную дисциплину?</h3>
            </div>
            <div class="layer-body">
                <div class="details-row float-buttons">
                    <button class="cancel arcticmodal-close">Отмена</button>
                    <button class="remove arcticmodal-close" data-bind="click: $root.actions.discipline.end.remove">Удалить</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /ko -->
@endsection

<script type="text/html" id="info-mode">
    <div class="details-row">
        <div class="details-column">
            <label class="title">Аббревиатура</label>
            <span class="info" data-bind="text: abbreviation"></span>
        </div>
        <div class="details-column">
            <label class="title">Полное название дисциплины</label>
            <span class="info" data-bind="text: name"></span>
        </div>
    </div>
    <!-- ko if: $root.user.role() === role.admin.name -->
    <div class="details-row float-buttons">
        <div class="details-column width-100p">
            <button data-bind="click: $root.actions.discipline.start.remove" class="remove"><span class="fa">&#xf014;</span>&nbsp;Удалить</button>
            <button data-bind="click: $root.actions.discipline.start.update" class="approve"><span class="fa">&#xf040;</span>&nbsp;Редактировать</button>
        </div>
    </div>
    <!-- /ko -->
</script>
<script type="text/html" id="edit-mode">
    <div class="details-row">
        <div class="details-column width-20p">
            <label class="title">Аббревиатура&nbsp;<span class="required">*</span></label>
            <input id="iAbbreviation" validate type="text"
                   data-bind="value: abbreviation,validationElement: abbreviation,
                       event: {focusout: $root.events.focusout, focusin: $root.events.focusin}">
        </div>
        <div class="details-column width-75p">
            <label class="title">Полное название дисциплины&nbsp;<span class="required">*</span></label>
            <input id="iFullName" validate type="text"
                   data-bind="value: name,validationElement: name,
                       event: {focusout: $root.events.focusout, focusin: $root.events.focusin}">
        </div>
    </div>
    <div class="details-row" data-bind="with: $root.multiselect">
        <div class="details-column width-98p" id="dProfiles" validate special
             title="Пожалуйста, укажите хотя бы один профиль">
            <label class="title">Профили&nbsp;<span class="required">*</span></label>
            <multiselect params="source: data, tags: tags, textField: 'fullname'"></multiselect>
        </div>
    </div>
    <div class="details-row float-buttons">
        <div class="details-column width-100p">
            <button data-bind="click: $root.actions.discipline.cancel" class="cancel">Отмена</button>
            <button id="bAcceptDiscipline" title="Проверьте правильность заполнения полей"
                    data-bind="click: $root.actions.discipline.end.update"
                    accept-validation class="approve">Сохранить
            </button>
        </div>
    </div>
</script>