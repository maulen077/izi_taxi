@extends('admin.layouts.admin')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Баннеры</h2>

        {{-- Кнопка для модалки добавления --}}
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createBannerModal">
            + Добавить баннер
        </button>

        {{-- Таблица --}}
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Фото</th>
                <th>Видео</th>
                <th>Заголовок</th>
                <th>Заголовок (EN)</th>
                {{--                <th>Описание</th>--}}
                {{--                <th>Описание (EN)</th>--}}
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($banners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td>
                        @if($banner->photo)
                            <img src="{{ $banner->photo }}" alt="photo" width="80">
                        @endif
                    </td>
                    <td>
                        @if($banner->video)
                            <video width="120" controls>
                                <source src="{{ $banner->video }}" type="video/mp4">
                                Ваш браузер не поддерживает видео.
                            </video>
                        @endif
                    </td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->title_en }}</td>
                    {{--                    <td>{{ $banner->description }}</td>--}}
                    {{--                    <td>{{ $banner->description_en }}</td>--}}
                    <td>
                        {{-- Кнопка редактирования --}}
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editBannerModal{{ $banner->id }}">
                            ✏️
                        </button>

                        {{-- Удаление --}}
                        <form action="{{ route('banner_delete', $banner->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Удалить баннер?')">🗑️</button>
                        </form>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div class="modal fade" id="editBannerModal{{ $banner->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('banner_update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Редактировать баннер</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Заголовок</label>
                                        <input type="text" name="title" class="form-control" value="{{ $banner->title }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Заголовок (EN)</label>
                                        <input type="text" name="title_en" class="form-control" value="{{ $banner->title_en }}" required>
                                    </div>
                                    {{--                                    <div class="form-group">--}}
                                    {{--                                        <label>Описание</label>--}}
                                    {{--                                        <textarea name="description" class="form-control">{{ $banner->description }}</textarea>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="form-group">--}}
                                    {{--                                        <label>Описание (EN)</label>--}}
                                    {{--                                        <textarea name="description_en" class="form-control">{{ $banner->description_en }}</textarea>--}}
                                    {{--                                    </div>--}}
                                    <div class="form-group">
                                        <label>Фото</label>
                                        <input type="file" name="photo" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Видео</label>
                                        <input type="file" name="video" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Тип</label>
                                        <select name="type" class="form-control" required>
                                            <option value="amulet" {{ $banner->type == 'amulet' ? 'selected' : '' }}>Амулет</option>
                                            <option value="meditation" {{ $banner->type == 'meditation' ? 'selected' : '' }}>Медитация</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Сохранить</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>

        {{-- Пагинация --}}
        <div class="mt-3">
            {{ $banners->links() }}
        </div>
    </div>

    {{-- Modal Create --}}
    <div class="modal fade" id="createBannerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('banner_store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Добавить баннер</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Заголовок</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Заголовок (EN)</label>
                            <input type="text" name="title_en" class="form-control" required>
                        </div>
                        {{--                        <div class="form-group">--}}
                        {{--                            <label>Описание</label>--}}
                        {{--                            <textarea name="description" class="form-control"></textarea>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="form-group">--}}
                        {{--                            <label>Описание (EN)</label>--}}
                        {{--                            <textarea name="description_en" class="form-control"></textarea>--}}
                        {{--                        </div>--}}

                        <div class="form-group">
                            <label>Тип</label>
                            <select name="type" class="form-control" required>
                                <option value="amulet">Амулет</option>
                                <option value="meditation">Медитация</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Фото</label>
                            <input type="file" name="photo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Видео</label>
                            <input type="file" name="video" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
